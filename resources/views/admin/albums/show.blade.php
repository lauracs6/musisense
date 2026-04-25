<x-app-layout>
    <x-slot name="header">
        Album Details
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow border-l-4 border-indigo-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Album: {{ $album->title }}</h2>
            <a href="{{ route('admin.albums.index') }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Back</a>
        </div>

        <!-- Cover Image -->
        @if($album->cover)
            <img src="{{ asset('storage/'.$album->cover) }}" class="w-32 h-32 object-cover rounded-lg mb-4">
        @endif

        <!-- Album Info -->
        <dl class="grid grid-cols-1 gap-2 text-sm">
            <div><dt class="font-semibold inline">ID:</dt> <dd class="inline">{{ $album->id }}</dd></div>
            <div><dt class="font-semibold inline">Title:</dt> <dd class="inline">{{ $album->title }}</dd></div>
            <div><dt class="font-semibold inline">Artist:</dt> <dd class="inline">{{ $album->artists->first()->name ?? 'Unknown' }}</dd></div>
            <div><dt class="font-semibold inline">Year:</dt> <dd class="inline">{{ $album->year }}</dd></div>
            <div><dt class="font-semibold inline">Type:</dt> <dd class="inline">{{ $album->type }}</dd></div>
            <div><dt class="font-semibold inline">Genre:</dt> <dd class="inline">{{ $album->genre->name ?? 'N/A' }}</dd></div>
            <div><dt class="font-semibold inline">Status:</dt> <dd class="inline">{{ $album->status === 'y' ? 'Active' : 'Inactive' }}</dd></div>
        </dl>

        <hr class="my-4">

        <!-- Tracks List -->
        <h3 class="font-semibold mb-2">Tracks</h3>
        <ul class="divide-y divide-gray-200">
            @foreach($album->tracks as $track)
                <li class="py-2 text-sm">{{ $track->id }}. {{ $track->title }}</li>
            @endforeach
        </ul>

        <!-- Actions -->
        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.albums.edit', $album) }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Edit</a>
        </div>
    </div>
</x-app-layout>