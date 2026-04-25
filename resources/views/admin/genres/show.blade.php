<x-app-layout>
    <x-slot name="header">
        Genre Details
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow border-l-4 border-indigo-500">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
                Genre: {{ $genre->name }}
            </h2>

            <a href="{{ route('admin.genres.index') }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Back
            </a>
        </div>

        <!-- INFO -->
        <div class="text-sm space-y-2">
            <p>
                <span class="font-semibold">ID:</span> {{ $genre->id }}
            </p>

            <p>
                <span class="font-semibold">Name:</span> {{ $genre->name }}
            </p>

            <p>
                <span class="font-semibold">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs {{ $genre->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $genre->status === 'y' ? 'Active' : 'Inactive' }}
                </span>
            </p>

            <p>
                <span class="font-semibold">Total Albums:</span>
                {{ $genre->albums->count() }}
            </p>
        </div>

        <!-- ALBUMS -->
        <hr class="my-4">

        <h3 class="text-sm font-semibold mb-2">Albums</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($genre->albums as $album)
                <div class="border p-3 rounded-lg hover:bg-gray-50">

                    <p class="font-semibold">
                        {{ $album->title }}
                    </p>
                    <p class="text-sm">
                        Artist: {{ $album->artists->first()->name ?? 'Unknown' }}
                    </p>

                    <p class="text-sm mt-1">
                        <span class="px-2 py-1 rounded-full text-xs {{ $album->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $album->status === 'y' ? 'Active' : 'Inactive' }}
                        </span>
                    </p>

                    <p class="text-sm mt-1">
                        Total tracks: {{ $album->tracks->count() ?? 0 }}
                    </p>

                </div>
            @endforeach
        </div>

        <!-- ACTIONS -->
        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.genres.edit', $genre) }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Edit
            </a>
        </div>

    </div>
</x-app-layout>