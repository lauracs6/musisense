<x-app-layout>
    <x-slot name="header">
        Edit Playlist
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 border-l-4 border-indigo-500 shadow">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Edit Playlist</h2>

            <a href="{{ route('admin.playlists.index') }}"
               class="bg-white text-gray-600 hover:bg-gray-100 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.playlist.update', $playlist) }}">
            @csrf
            @method('PUT')

            <!-- NAME -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name"
                       value="{{ old('name', $playlist->name) }}"
                       class="w-full border rounded p-2 mt-1">
            </div>

            <!-- STATUS -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full border rounded p-2 mt-1">
                    <option value="y" {{ $playlist->status === 'y' ? 'selected' : '' }}>Active</option>
                    <option value="n" {{ $playlist->status === 'n' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- ACTIONS -->
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.playlists.index') }}"
                   class="bg-white text-gray-600 border-2 border-gray-300 px-4 py-2 rounded-xl">
                    Cancel
                </a>

                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-xl">
                    Save
                </button>
            </div>
        </form>

        @if (session('error'))
            <div id="toast-error"
                class="fixed bottom-6 left-1/2 transform -translate-x-1/2
                        bg-red-600 text-white px-6 py-3 rounded-xl shadow-lg z-50
                        flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">
                    {{ session('error') }}
                </span>
            </div>
            <script>
                setTimeout(() => {
                    const el = document.getElementById('toast-error');
                    if (el) {
                        el.classList.add('opacity-0', 'transition', 'duration-300');
                        setTimeout(() => el.remove(), 300);
                    }
                }, 4000);
            </script>
        @endif
    </div>
</x-app-layout>
