<x-app-layout>
<div class="min-h-screen p-6 bg-white text-gray-800">

    <div class="max-w-xl mx-auto border p-6 rounded-2xl shadow hover:shadow-md hover:shadow-indigo-800">

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">{{ $playlist->name }}</h1>

            <a href="{{ route('admin.playlists.index') }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Back
            </a>
        </div>

        <p><strong>User:</strong> {{ $playlist->user->username }}</p>

        <p class="mt-2">
            Status:
            <span class="{{ $playlist->status === 'y' ? 'text-green-600' : 'text-red-600' }}">
                {{ $playlist->status === 'y' ? 'Active' : 'Inactive' }}
            </span>
        </p>

        <div class="mt-4">
            <h2 class="font-semibold mb-2">Tracks</h2>

            @foreach($playlist->tracks->sortBy('pivot.position') as $track)
                <p class="text-sm text-gray-600">
                    {{ $track->pivot->position }}.
                    {{ $track->title }} - {{ $track->artist }}
                </p>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.playlists.edit', $playlist) }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Edit
            </a>
        </div>

    </div>

</div>
</x-app-layout>