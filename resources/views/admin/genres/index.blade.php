<x-app-layout>
<div class="min-h-screen p-6 bg-white text-gray-800">

    <h1 class="text-2xl font-semibold mb-6">Genres</h1>

    <!-- Filters -->
    <form method="GET" class="mb-6 flex gap-3">
        <input type="text" name="search" placeholder="Search..."
            value="{{ request('search') }}"
            class="border rounded px-3 py-2 w-64">

        <select name="status" class="border rounded px-3 py-2">
            <option value="">All</option>
            <option value="y" @selected(request('status')=='y')>Active</option>
            <option value="n" @selected(request('status')=='n')>Inactive</option>
        </select>

        <button class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
            Filter
        </button>

        <a href="{{ route('admin.genres.index') }}"
            class="bg-gray-300 px-4 py-2 rounded-lg">
            Reset
        </a>
    </form>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($genres as $genre)
        <div class="border rounded-2xl p-4 hover:shadow-md hover:shadow-indigo-800 transition flex flex-col justify-between">

            <div class="flex justify-between items-center">

                <div>
                    <h2 class="text-lg font-semibold">
                        {{ $genre->name }}
                    </h2>

                    <p class="mt-2">
                        Status:
                        <span class="font-semibold {{ $genre->status === 'y' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $genre->status === 'y' ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">

                    <!-- Edit -->
                    <a href="{{ route('admin.genres.edit', $genre) }}"
                       class="bg-indigo-700 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-800">
                        Edit
                    </a>                    

                </div>
            </div>

            <!-- Albums -->
            <div class="mt-3">
                <p class="text-md font-bold text-gray-800 mb-1">Albums</p>

                <ul class="text-sm text-gray-800 space-y-1">
                    @foreach($genre->albums as $album)
                        <li class="{{ $album->status === 'n' ? 'line-through text-gray-900' : '' }}">
                            #{{ $album->id }} - {{ $album->title }}
                        </li>                        
                    @endforeach
                </ul>
            </div>

        </div>
        @endforeach
    </div>

</div>
</x-app-layout>