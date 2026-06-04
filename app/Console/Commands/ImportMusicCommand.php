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
    protected $description = 'Importa canciones etiquetadas con MusicBrainz Picard';

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
            $this->warn("No se encontraron archivos.");
            return 0;
        }

        $this->info("Se encontraron " . count($files) . " archivos.");
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

        $this->info("Importación completada. $imported canciones, $errors errores.");

        return 0;
    }

    // FILE SCAN //
    private function getAudioFiles(string $folder): array
    {
        $extensions = ['mp3', 'flac', 'm4a', 'ogg'];
        $pattern = $folder . '/*.{' . implode(',', $extensions) . '}';

        $files = glob($pattern, GLOB_BRACE);

        foreach (glob($folder . '/*', GLOB_ONLYDIR) as $sub) {
            $files = array_merge($files, $this->getAudioFiles($sub));
        }

        return $files;
    }

    // IMPORT CORE //
    private function importFile(getID3 $getID3, string $filePath)
    {
        $info = $getID3->analyze($filePath);
        $tags = $this->extractTags($info);

        // defaults seguros
        $tags['title'] ??= pathinfo($filePath, PATHINFO_FILENAME);

        // Prioridad artista
        $tags['artist'] =
            $tags['artist']
            ?? $tags['album_artist']
            ?? 'Artista desconocido';

        $tags['album_artist'] ??= $tags['artist'];

        // ARTISTA
        $artist = Artist::firstOrCreate([
            'name' => $tags['album_artist']
        ]);

        // GÉNERO
        $genreName = $tags['genre']
            ?? $this->guessGenreFromPath($filePath)
            ?? 'Unknown';

        $genre = Genre::firstOrCreate(
            ['name' => $genreName],
            ['status' => 'y']
        );

        // ÁLBUM
        $album = Album::firstOrCreate(
            [
                'title' => $tags['album'] ?? 'Álbum desconocido',
                'genre_id' => $genre->id,
            ],
            [
                'year' => $tags['year'] ?? null,
                'type' => $this->normalizeAlbumType($tags['releasetype'] ?? 'album'),
            ]
        );

        if (!$album->wasRecentlyCreated) {
            $album->update(array_filter([
                'year' => $tags['year'] ?? null,
                'type' => $this->normalizeAlbumType($tags['releasetype'] ?? 'album'),
                'country' => $tags['country'] ?? null,
                'genre_id' => $genre->id,
            ]));
        }

        // artista principal
        $album->artists()->syncWithoutDetaching([
            $artist->id => ['role' => 'main']
        ]);

        // CARÁTULA
        if (!$album->cover) {
            $cover = $this->extractCover($info, $album->id)
                ?? $this->findExternalCover(dirname($filePath), $album->id);

            if ($cover) {
                $album->update(['cover' => $cover]);
            }
        }

        // TRACK
        $trackNumber = $tags['track_number'] ?? 0;
        if ($trackNumber == 0) {
            $filename = pathinfo($filePath, PATHINFO_FILENAME);
            if (preg_match('/^(\d+)/', $filename, $matches)) {
                $trackNumber = (int) $matches[1];
            }
        }

        Track::updateOrCreate(
            ['file_path' => realpath($filePath)],
            [
                'title' => $tags['title'],
                'artist' => $tags['artist'],
                'album_id' => $album->id,
                'track_number' => $trackNumber,
                'duration' => (int)($info['playtime_seconds'] ?? 0),
            ]
        );

        // REORDENAR TRACKS
        $this->reorderTracks($album->id);
    }

    // Reordenar tracks empezando en 1
    private function reorderTracks(int $albumId): void
    {
        $tracks = Track::where('album_id', $albumId)
            ->orderBy('track_number')
            ->orderBy('id')
            ->get();

        $i = 1;
        foreach ($tracks as $track) {
            if ($track->track_number != $i) {
                $track->track_number = $i;
                $track->save();
            }
            $i++;
        }
    }

    // TAG EXTRACTION //
    private function extractTags(array $info): array
    {
        $tags = [];

        $tagSources = [];

        // QuickTime normal
        if (!empty($info['tags']['quicktime'])) {
            $tagSources = $info['tags']['quicktime'];
        }

        // QuickTime KEYS (clave para M4A)
        if (isset($info['quicktime']['keys'])) {
            foreach ($info['quicktime']['keys'] as $key => $value) {
                $tagSources[$key] = [$value];
            }
        }

        // fallback otros formatos
        if (empty($tagSources)) {
            $tagSources = $info['tags']['id3v2']
                ?? $info['tags']['vorbiscomment']
                ?? $info['tags']['id3v1']
                ?? [];
        }

        $map = [
            'title' => ['title', '©nam'],
            'artist' => ['artist', '©ART'],
            'album' => ['album', '©alb'],
            'album_artist' => ['albumartist', 'aART', '©ART'],
            'track_number' => ['tracknumber', 'track'],
            'genre' => ['genre', '©gen'],
            'year' => ['year', 'date', '©day', 'creation_date'],
            'country' => ['country'],
            'releasetype' => ['releasetype'],
        ];

        foreach ($map as $field => $keys) {
            foreach ($keys as $key) {
                if (!empty($tagSources[$key][0])) {
                    $value = $tagSources[$key][0];

                    if ($field === 'track_number') {
                        $parts = explode('/', $value);
                        $tags[$field] = (int) trim($parts[0]);
                        break;
                    }

                    if ($field === 'year') {
                        if (preg_match('/\d{4}/', $value, $m)) {
                            $tags[$field] = $m[0];
                        }
                        break;
                    }

                    $tags[$field] = $value;
                    break;
                }
            }
        }

        // fallback artista desde ©ART
        if (empty($tags['artist']) && !empty($tagSources['©ART'][0])) {
            $tags['artist'] = $tagSources['©ART'][0];
        }

        if (empty($tags['album_artist']) && !empty($tagSources['©ART'][0])) {
            $tags['album_artist'] = $tagSources['©ART'][0];
        }

        // fallback year global
        if (empty($tags['year'])) {
            $raw = json_encode($info);
            if (preg_match('/\b(19|20)\d{2}\b/', $raw, $m)) {
                $tags['year'] = $m[0];
            }
        }

        return $tags;
    }

    // GENRE HELPER
    private function guessGenreFromPath(string $filePath): ?string
    {
        $path = strtolower($filePath);

        return match (true) {
            str_contains($path, 'rock') => 'Rock',
            str_contains($path, 'pop') => 'Pop',
            str_contains($path, 'jazz') => 'Jazz',
            str_contains($path, 'electronic') => 'Electronic',
            default => null,
        };
    }

    // ALBUM TYPE
    private function normalizeAlbumType(?string $type): string
    {
        return in_array(strtolower($type), ['album', 'single', 'ep'])
            ? strtolower($type)
            : 'album';
    }

    // COVER
    private function extractCover(array $info, int $albumId): ?string
    {
        $picture = $info['comments']['picture'][0]
            ?? $info['id3v2']['APIC'][0]
            ?? null;

        if (!$picture) return null;

        $ext = str_contains($picture['image_mime'] ?? '', 'png') ? 'png' : 'jpg';

        $name = "album_{$albumId}_" . time() . ".$ext";
        $path = storage_path("app/public/covers/$name");

        @mkdir(dirname($path), 0755, true);
        file_put_contents($path, $picture['data']);

        return "covers/$name";
    }

    private function findExternalCover(string $folder, int $albumId): ?string
    {
        foreach (['cover', 'folder', 'front'] as $name) {
            foreach (['jpg', 'png'] as $ext) {

                $file = "$folder/$name.$ext";

                if (file_exists($file)) {

                    $new = "album_{$albumId}_" . time() . ".$ext";
                    $dest = storage_path("app/public/covers/$new");

                    @mkdir(dirname($dest), 0755, true);
                    copy($file, $dest);

                    return "covers/$new";
                }
            }
        }

        return null;
    }
}
