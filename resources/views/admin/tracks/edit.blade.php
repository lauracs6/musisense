<x-app-layout>
    <x-slot name="header">
        Edit Track
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 border-l-4 border-indigo-500 shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Edit Track</h2>

            <a href="{{ route('admin.tracks.index') }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.tracks.update', $track) }}">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm">Title</label>
                <input type="text" name="title"
                    value="{{ old('title', $track->title) }}"
                    class="w-full border rounded p-2 mt-1">
            </div>    

            <!-- Status -->
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
        @if (session('error'))
            <div id="toast-error"
                class="fixed bottom-6 left-1/2 transform -translate-x-1/2
                        bg-red-600 text-white px-6 py-3 rounded-xl shadow-lg z-50
                        flex items-center gap-2">

                <!-- icon -->
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