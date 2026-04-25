<x-app-layout>
    <x-slot name="header">
        Tracks
    </x-slot>

    <!-- Filters -->
    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search by title, artist or album"
               class="border-2 border-sky-400 p-2 w-64">

        <select name="status" class="border-2 border-yellow-300 p-2 pr-8 w-48">
            <option value="">All statuses</option>
            <option value="y" {{ request('status') === 'y' ? 'selected' : '' }}>Active</option>
            <option value="n" {{ request('status') === 'n' ? 'selected' : '' }}>Inactive</option>
        </select>

        <button class="bg-gray-700 text-gray-300 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-500 px-4 py-2 rounded-xl">
            Filter
        </button>

        <a href="{{ route('admin.tracks.index') }}"
           class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-500 px-4 py-2 rounded-xl">
            Reset
        </a>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto bg-white shadow">
        <table class="w-full table-fixed divide-y divide-gray-200">

            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/12">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-3/12">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Artist</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-3/12">Album</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/12">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/12">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/12">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @foreach($tracks as $track)
                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $track->id }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700 truncate">
                        {{ $track->title }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700 truncate">
                        {{ $track->artist }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700 truncate">
                        {{ $track->album->title ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ gmdate("i:s", $track->duration) }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs {{ $track->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $track->status === 'y' ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.tracks.show', $track) }}"
                           class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                            Show
                        </a>

                        <a href="{{ route('admin.tracks.edit', $track) }}"
                           class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                            Edit
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tracks->links() }}
    </div>
</x-app-layout>