<x-app-layout>
    <div class="min-h-screen p-6 bg-white text-gray-800">

        <div class="max-w-xl mx-auto border p-6 rounded-2xl hover:shadow-md hover:shadow-indigo-800">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Edit Album</h1>

                <a href="{{ route('admin.albums.index') }}"
                class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                    Back
                </a>
            </div>

            <form method="POST" enctype="multipart/form-data"
                action="{{ route('admin.albums.update', $album) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm">Title</label>
                    <input type="text" name="title"
                        value="{{ old('title', $album->title) }}"
                        class="w-full border rounded p-2 mt-1">
                </div>

                <div class="mb-4">
                    <label class="text-sm">Year</label>
                    <input type="number" name="year"
                        value="{{ old('year', $album->year) }}"
                        class="w-full border rounded p-2 mt-1">
                </div>

                <div class="mb-4">
                    <label class="text-sm">Type</label>
                    <input type="text" name="type"
                        value="{{ old('type', $album->type) }}"
                        class="w-full border rounded p-2 mt-1">
                </div>

                <div class="mb-4">
                    <label class="text-sm">Genre</label>
                    <select name="genre_id" class="w-full border rounded p-2 mt-1">
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}"
                                {{ $album->genre_id == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- COVER -->
                <div class="mb-4">
                    <label class="text-sm">Cover</label>

                    @if($album->cover)
                        <img src="{{ asset('storage/'.$album->cover) }}"
                            class="w-full h-40 object-cover rounded mb-2">
                    @endif

                    <input type="file" name="cover"
                        class="w-full border rounded p-2">
                </div>

                <!-- STATUS -->
                <div class="mb-4">
                    <label class="text-sm">Status</label>
                    <select name="status" class="w-full border rounded p-2 mt-1">
                        <option value="y" {{ $album->status === 'y' ? 'selected' : '' }}>Active</option>
                        <option value="n" {{ $album->status === 'n' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.albums.index') }}"
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