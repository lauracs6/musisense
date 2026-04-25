<x-app-layout>
    <x-slot name="header">
        Albums
    </x-slot>

    <!-- Filters -->
    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, artist or year" class="border-2 border-sky-400 p-2 w-64">
        <select name="status" class="border-2 border-yellow-300 p-2 pr-8 w-48">
            <option value="">All statuses</option>
            <option value="y" {{ request('status') === 'y' ? 'selected' : '' }}>Active</option>
            <option value="n" {{ request('status') === 'n' ? 'selected' : '' }}>Inactive</option>
        </select>
        <select name="type" class="border-2 border-indigo-300 p-2 pr-8 w-48">
            <option value="">All types</option>
            <option value="album" {{ request('type') === 'album' ? 'selected' : '' }}>Album</option>
            <option value="ep" {{ request('type') === 'ep' ? 'selected' : '' }}>EP</option>
            <option value="single" {{ request('type') === 'single' ? 'selected' : '' }}>Single</option>
        </select>
        <button class="bg-gray-700 text-gray-300 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-500 px-4 py-2 rounded-xl">Filter</button>
        <a href="{{ route('admin.albums.index') }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-500 px-4 py-2 rounded-xl">Reset</a>
    </form>

    <!-- Albums Table -->
    <div class="overflow-x-auto bg-white shadow">
        <table class="w-full table-fixed divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Artist</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($albums as $album)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $album->id }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $album->title }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $album->artists->first()->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $album->year ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ ucfirst($album->type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs {{ $album->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $album->status === 'y' ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.albums.show', $album) }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Show</a>
                        <a href="{{ route('admin.albums.edit', $album) }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $albums->links() }}
    </div>
</x-app-layout>