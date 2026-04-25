<x-app-layout>
<div class="min-h-screen p-6 bg-white text-gray-800">

    <div class="max-w-xl mx-auto border p-6 rounded-2xl shadow hover:shadow-md hover:shadow-indigo-800">

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Edit Playlist</h1>

            <a href="{{ route('admin.playlists.index') }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.playlist.update', $playlist) }}">
            @csrf
            @method('PUT')

            <!-- INFO SOLO LECTURA -->
            <div class="mb-4">
                <p><strong>Name:</strong> {{ $playlist->name }}</p>
                <p><strong>User:</strong> {{ $playlist->user->username }}</p>
            </div>

            <!-- STATUS -->
            <div class="mb-4">
                <label class="text-sm">Status</label>

                <select name="status" class="w-full border rounded p-2 mt-1">
                    <option value="y" {{ $playlist->status === 'y' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="n" {{ $playlist->status === 'n' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.playlists.index') }}"
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