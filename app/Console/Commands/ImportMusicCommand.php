<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Track;
use Illuminate\Console\Command;
use getID3;

class ImportMusicCommand extends Command
{
    protected $signature = 'music:import {folder : Ruta a la carpeta que contiene las canciones}';
    protected $description = 'Importa canciones etiquetadas con MusicBrainz Picard a la base de datos (álbumes, artistas, géneros y pistas)';

    public function handle()
    {
        $folder = $this->argument('folder');
        if (!is_dir($folder)) {
            $this->error("La carpeta '$folder' no existe.");
            return 1;
        }

        $getID3 = new getID3();
        $files = $this->getAudioFiles($folder);

        if (empty($files)) {
            $this->warn("No se encontraron archivos de audio en la carpeta.");
            return 0;
        }

        $this->info("Se encontraron " . count($files) . " archivos de audio.");
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $imported = 0;
        $errors = 0;

        foreach ($files as $filePath) {
            try {
                $this->importFile($getID3, $filePath);
                $imported++;
            } catch (\Exception $e) {
                $this->error("\nError en " . basename($filePath) . ": " . $e->getMessage());
                $errors++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Importación completada. $imported canciones importadas, $errors errores.");

        return 0;
    }

    private function getAudioFiles(string $folder): array
    {
        $extensions = ['mp3', 'flac', 'm4a', 'ogg'];
        $pattern = $folder . '/*.{' . implode(',', $extensions) . '}';
        $files = glob($pattern, GLOB_BRACE);
        $subFolders = glob($folder . '/*', GLOB_ONLYDIR);
        foreach ($subFolders as $sub) {
            $files = array_merge($files, $this->getAudioFiles($sub));
        }
        return $files;
    }

    private function importFile(getID3 $getID3, string $filePath)
    {
        $info = $getID3->analyze($filePath);
        $tags = $this->extractTags($info);

        if (empty($tags['title'])) {
            $tags['title'] = pathinfo($filePath, PATHINFO_FILENAME);
        }

        if (empty($tags['artist'])) {
            $tags['artist'] = $tags['album_artist'] ?? 'Artista desconocido';
        }

        // --- 1. Artista principal del álbum ---
        $artistName = $tags['album_artist'] ?? $tags['artist'] ?? 'Artista desconocido';
        $artist = Artist::firstOrCreate(['name' => $artistName]);

        // --- 2. Álbum ---
        $albumData = [
            'title' => $tags['album'] ?? 'Álbum desconocido',
            'year' => $tags['year'] ?? null,
            'type' => $this->normalizeAlbumType($tags['releasetype'] ?? 'album'),
            'country' => $tags['country'] ?? null,
        ];

        $album = Album::firstOrCreate(
            ['title' => $albumData['title']],
            $albumData
        );

        if ($album->wasRecentlyCreated === false) {
            $album->update(array_filter($albumData));
        }

        // Asignar artista principal
        $album->artists()->syncWithoutDetaching([$artist->id => ['role' => 'main']]);

        // --- 3. Género ---
        $genreName = $tags['genre'] ?? null;
        if (!empty($genreName)) {
            $genre = Genre::firstOrCreate(['name' => $genreName]);
            $album->genres()->syncWithoutDetaching([$genre->id => ['role' => 'main']]);
        }

        // --- 4. Carátula (prioridad: incrustada -> externa) ---
        if (!$album->cover) {
            $coverPath = $this->extractAndSaveCover($filePath, $album->id);
            if (!$coverPath) {
                // Buscar archivo externo en la carpeta del álbum
                $albumFolder = dirname($filePath);
                $coverPath = $this->findExternalCover($albumFolder, $album->id);
            }
            if ($coverPath) {
                $album->cover = $coverPath;
                $album->save();
                $this->line("  Carátula guardada para '{$album->title}'");
            }
        }

        // --- 5. Pista ---
        $trackData = [
            'title' => $tags['title'],
            'artist' => $tags['artist'],
            'album_id' => $album->id,
            'track_number' => $tags['track_number'] ?? 0,
            'duration' => (int)($info['playtime_seconds'] ?? 0),
            'file_path' => realpath($filePath),
        ];

        Track::updateOrCreate(
            ['file_path' => $trackData['file_path']],
            $trackData
        );
    }

    private function extractTags(array $info): array
    {
        $tags = [];

        $tagSources = [];
        if (isset($info['tags']['quicktime'])) {
            $tagSources = $info['tags']['quicktime'];
        } elseif (isset($info['tags']['id3v2'])) {
            $tagSources = $info['tags']['id3v2'];
        } elseif (isset($info['tags']['vorbiscomment'])) {
            $tagSources = $info['tags']['vorbiscomment'];
        } elseif (isset($info['tags']['id3v1'])) {
            $tagSources = $info['tags']['id3v1'];
        }

        if (empty($tagSources) && isset($info['quicktime']['keys'])) {
            $tagSources = [];
            foreach ($info['quicktime']['keys'] as $key => $value) {
                $tagSources[$key] = [$value];
            }
        }

        $map = [
            'title' => ['title', 'titulo', '©nam'],
            'artist' => ['artist', 'artista', '©ART', '©aut'],
            'album' => ['album', '©alb'],
            'album_artist' => ['albumartist', 'album artist', 'band', 'orchestra', 'aART', '©day', 'album_artist'],
            'track_number' => ['tracknumber', 'track_number', 'track', 'trkn'],
            'genre' => ['genre', 'genero', '©gen'],
            'year' => ['year', 'date', 'originalyear', '©day', 'creation_date'],
            'country' => ['country', '©cpr'],
            'releasetype' => ['releasetype'],
        ];

        foreach ($map as $field => $possibleKeys) {
            foreach ($possibleKeys as $key) {
                if (isset($tagSources[$key][0]) && !empty($tagSources[$key][0])) {
                    $value = $tagSources[$key][0];
                    if ($field === 'track_number') {
                        if (is_array($value)) {
                            $tags[$field] = (int)($value[0] ?? 0);
                        } else {
                            $parts = explode('/', $value);
                            $tags[$field] = (int)$parts[0];
                        }
                    } elseif ($field === 'year') {
                        preg_match('/\d{4}/', $value, $match);
                        $tags[$field] = $match[0] ?? null;
                    } else {
                        $tags[$field] = $value;
                    }
                    break;
                }
            }
        }

        if (empty($tags['album_artist']) && !empty($tags['artist'])) {
            $tags['album_artist'] = $tags['artist'];
        }

        return $tags;
    }

    private function normalizeAlbumType(?string $type): string
    {
        $type = strtolower(trim($type ?? ''));
        return in_array($type, ['album', 'single', 'ep']) ? $type : 'album';
    }

    private function extractAndSaveCover($filePath, $albumId): ?string
    {
        $album = Album::find($albumId);
        if ($album && $album->cover) {
            return $album->cover;
        }

        $getID3 = new getID3();
        $info = $getID3->analyze($filePath);

        $pictureData = null;
        $pictureMime = null;

        if (isset($info['comments']['picture'])) {
            $picture = $info['comments']['picture'][0];
            $pictureData = $picture['data'];
            $pictureMime = $picture['image_mime'];
        } elseif (isset($info['id3v2']['APIC'][0]['data'])) {
            $pictureData = $info['id3v2']['APIC'][0]['data'];
            $pictureMime = $info['id3v2']['APIC'][0]['image_mime'];
        } elseif (isset($info['quicktime']['covr'][0]['data'])) {
            $pictureData = $info['quicktime']['covr'][0]['data'];
            $pictureMime = 'image/jpeg';
        }

        if (!$pictureData) {
            return null;
        }

        $extension = match($pictureMime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            default => 'jpg'
        };

        $coverName = 'album_' . $albumId . '_' . time() . '.' . $extension;
        $relativePath = 'covers/' . $coverName;
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        file_put_contents($fullPath, $pictureData);
        return $relativePath;
    }

    /**
     * Busca una carátula externa en la carpeta del álbum.
     * Nombre: cover.jpg
     */
    private function findExternalCover(string $albumFolder, int $albumId): ?string
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $candidates = ['cover', 'folder', 'front', 'album', 'artwork'];

        foreach ($candidates as $candidate) {
            foreach ($extensions as $ext) {
                $possiblePath = $albumFolder . '/' . $candidate . '.' . $ext;
                if (file_exists($possiblePath)) {
                    $coverName = 'album_' . $albumId . '_' . time() . '.' . $ext;
                    $relativePath = 'covers/' . $coverName;
                    $fullPath = storage_path('app/public/' . $relativePath);

                    if (!is_dir(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }

                    copy($possiblePath, $fullPath);
                    return $relativePath;
                }
            }
        }

        return null;
    }
}