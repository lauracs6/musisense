<x-app-layout>
    <x-slot name="header">
        Artist Details
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow border-l-4 border-indigo-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Artist: {{ $artist->name }}</h2>
            <a href="{{ route('admin.artists.index') }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Back</a>
        </div>

        <div class="text-sm space-y-2">
            <p><span class="font-semibold">ID:</span> {{ $artist->id }}</p>
            <p><span class="font-semibold">Name:</span> {{ $artist->name }}</p>
            <p>
                <span class="font-semibold">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs {{ $artist->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $artist->status === 'y' ? 'Active' : 'Inactive' }}
                </span>
            </p>
            <p><span class="font-semibold">Total Albums:</span> {{ $artist->albums->count() }}</p>
        </div>

        <hr class="my-4">

        <h3 class="text-sm font-semibold mb-2">Albums</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($artist->albums as $album)
                <div class="border p-3 rounded-lg hover:bg-gray-50">
                    <p class="font-semibold">{{ $album->title }}</p>
                    <p class="text-sm">Year: {{ $album->year }}</p>
                    <p class="text-sm mt-1">
                        <span class="px-2 py-1 rounded-full text-xs {{ $album->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $album->status === 'y' ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p class="text-sm mt-1">Tracks: {{ $album->tracks->count() }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.artists.edit', $artist) }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Edit</a>
        </div>
    </div>
</x-app-layout>
