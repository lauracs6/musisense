<x-app-layout>
<div class="min-h-screen p-6 bg-white text-gray-800">

    <div class="max-w-xl mx-auto border p-6 rounded-2xl hover:shadow-md hover:shadow-indigo-800">

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">{{ $track->title }}</h1>

            <a href="{{ route('admin.tracks.index') }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Back
            </a>
        </div>

        <p><strong>ID:</strong> {{ $track->id }}</p>
        <p><strong>Artist:</strong> {{ $track->artist }}</p>
        <p><strong>Album:</strong> {{ $track->album->title ?? 'N/A' }}</p>
        <p><strong>Track #:</strong> {{ $track->track_number }}</p>
        <p><strong>Duration:</strong> {{ gmdate("i:s", $track->duration) }}</p>

        <p class="mt-2">
            <strong>Status:</strong>
            <span class="{{ $track->status === 'y' ? 'text-green-600' : 'text-red-600' }}">
                {{ $track->status === 'y' ? 'Active' : 'Inactive' }}
            </span>
        </p>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('admin.tracks.edit', $track) }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Edit
            </a>
        </div>

    </div>

</div>
</x-app-layout>