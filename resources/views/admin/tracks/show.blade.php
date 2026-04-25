<x-app-layout>
    <x-slot name="header">
        Track Details
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow border-l-4 border-indigo-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
                Track: {{ $track->title }}
            </h2>

            <a href="{{ route('admin.tracks.index') }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Back
            </a>
        </div>

        <!-- Info -->
        <dl class="grid grid-cols-1 gap-2 text-sm">
            <div><dt class="font-semibold inline">ID:</dt> <dd class="inline">{{ $track->id }}</dd></div>
            <div><dt class="font-semibold inline">Title:</dt> <dd class="inline">{{ $track->title }}</dd></div>
            <div><dt class="font-semibold inline">Artist:</dt> <dd class="inline">{{ $track->artist }}</dd></div>
            <div><dt class="font-semibold inline">Album:</dt> <dd class="inline">{{ $track->album->title ?? 'N/A' }}</dd></div>
            <div><dt class="font-semibold inline">Track #:</dt> <dd class="inline">{{ $track->track_number }}</dd></div>
            <div><dt class="font-semibold inline">Duration:</dt> <dd class="inline">{{ gmdate("i:s", $track->duration) }}</dd></div>
            <div>
                <dt class="font-semibold inline">Status:</dt>
                <dd class="inline">
                    <span class="px-2 py-1 rounded-full text-xs {{ $track->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $track->status === 'y' ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>
        </dl>

        <!-- Actions -->
        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.tracks.edit', $track) }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Edit
            </a>
        </div>
    </div>
</x-app-layout>