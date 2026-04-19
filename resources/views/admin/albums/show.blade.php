<x-app-layout>
<div class="min-h-screen p-6 bg-white text-gray-800">

    <div class="max-w-3xl mx-auto border p-6 rounded-2xl hover:shadow-md hover:shadow-indigo-800">

        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">Album Details</h1>            

            <a href="{{ route('admin.albums.index') }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Back
            </a>
        </div>

        @if($album->cover)
            <img src="{{ asset('storage/'.$album->cover) }}"
                 class="w-20 h-20 object-cover rounded-lg mb-4">
        @endif

        <p><strong>Id:</strong> {{ $album->id }}</p>
        <p><strong>Title:</strong> {{ $album->title }}</p>
        <p><strong>Artist:</strong> {{ $album->artists->first()->name ?? 'Unknown' }}</p>
        <p><strong>Year:</strong> {{ $album->year }}</p>
        <p><strong>Type:</strong> {{ $album->type }}</p>
        <p><strong>Genre:</strong> {{ $album->genre->name ?? 'N/A' }}</p>

        </br>
        <hr class="my-4">
        </br>

        <h2 class="font-semibold mb-2">Tracks</h2>

        @foreach($album->tracks as $track)
            <div class="text-md text-gray-700 border-b py-2">
                {{ $track->id }}. {{ $track->title }}
            </div>
        @endforeach

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('admin.albums.edit', $album) }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Edit
            </a>
        </div>

    </div>

</div>
</x-app-layout>