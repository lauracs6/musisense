<x-app-layout>
<div class="min-h-screen p-6 bg-white text-gray-800">

    <div class="max-w-xl mx-auto border p-6 rounded-2xl hover:shadow-md hover:shadow-indigo-800">

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Edit Track</h1>

            <a href="{{ route('admin.tracks.index') }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.tracks.update', $track) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm">Title</label>
                <input type="text" name="title"
                    value="{{ old('title', $track->title) }}"
                    class="w-full border rounded p-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="block text-sm">Artist</label>
                <select name="artist_id" class="w-full border rounded p-2 mt-1">
                    @foreach($artists as $artist)
                        <option value="{{ $artist->id }}"
                            {{ $track->artist_id == $artist->id ? 'selected' : '' }}>
                            {{ $artist->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm">Album</label>
                <select name="album_id" class="w-full border rounded p-2 mt-1">
                    @foreach($albums as $album)
                        <option value="{{ $album->id }}"
                            {{ $track->album_id == $album->id ? 'selected' : '' }}>
                            {{ $album->title }}
                        </option>
                    @endforeach
                </select>
            </div>            

            <div class="mb-4">
                <label class="block text-sm">Duration (seconds)</label>
                <input type="number" name="duration"
                    value="{{ old('duration', $track->duration) }}"
                    class="w-full border rounded p-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="block text-sm">Status</label>
                <select name="status" class="w-full border rounded p-2 mt-1">
                    <option value="y" {{ $track->status === 'y' ? 'selected' : '' }}>Active</option>
                    <option value="n" {{ $track->status === 'n' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.tracks.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded text-sm">
                    Cancel
                </a>

                <button class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                    Save
                </button>
            </div>

        </form>

    </div>

</div>
</x-app-layout>