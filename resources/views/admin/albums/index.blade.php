<x-app-layout>
<div class="min-h-screen p-6 bg-white text-gray-800">

    <h1 class="text-2xl font-bold mb-6">Albums</h1>

    <!-- FILTERS -->
    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-4">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search by title or artist..."
               class="border rounded-lg p-2 w-full md:w-1/3">

        <select name="status" class="border rounded-lg p-2 w-full md:w-1/4">
            <option value="">All statuses</option>
            <option value="y" {{ request('status') === 'y' ? 'selected' : '' }}>Active</option>
            <option value="n" {{ request('status') === 'n' ? 'selected' : '' }}>Inactive</option>
        </select>

        <div class="flex gap-2">
            <button class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Filter
            </button>

            <a href="{{ route('admin.albums.index') }}"
               class="bg-gray-300 px-4 py-2 rounded-lg">
                Reset
            </a>
        </div>
    </form>

    <!-- GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($albums as $album)
            <div class="border rounded-2xl p-4 shadow hover:shadow-md hover:shadow-indigo-800 transition flex flex-col justify-between">

                <div>
                    <h2 class="text-lg font-semibold">{{ $album->id }}. {{ $album->title }}</h2>

                        <p class="mt-2">
                            Status:
                            <span class="font-semibold {{ $album->status === 'y' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $album->status === 'y' ? 'Active' : 'Inactive' }}
                            </span>
                        </p> 

                        <p class="mt-2"><strong>Artist:</strong> {{ $album->artists->first()->name ?? 'Unknown artist' }}</p>
                        
                        <p class="mt-2">
                            <strong>Year:</strong> {{ $album->year ?? 'N/A' }}
                        </p>

                        <p class="mt-2">
                            <strong>Type:</strong> {{ ucfirst($album->type) }}
                        </p>                         
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <a href="{{ route('admin.albums.show', $album) }}"
                       class="bg-indigo-700 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-800">
                        Show
                    </a>

                    <a href="{{ route('admin.albums.edit', $album) }}"
                       class="bg-indigo-700 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-800">
                        Edit
                    </a>
                </div>

            </div>
        @endforeach

    </div>

    <div class="mt-6">
        {{ $albums->links() }}
    </div>

</div>
</x-app-layout>