<x-app-layout>
    <x-slot name="header">
        Artists
    </x-slot>

    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="border-2 border-sky-400 p-2 w-64">
        <select name="status" class="border-2 border-yellow-300 p-2 pr-8 w-48">
            <option value="">All</option>
            <option value="y" @selected(request('status')=='y')>Active</option>
            <option value="n" @selected(request('status')=='n')>Inactive</option>
        </select>
        <button class="bg-gray-700 text-gray-300 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-500 px-4 py-2 rounded-xl">Filter</button>
        <a href="{{ route('admin.artists.index') }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-500 px-4 py-2 rounded-xl">Reset</a>
    </form>

    <div class="overflow-x-auto bg-white shadow">
        <table class="w-full table-fixed divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/12">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-4/12">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Total Albums</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-3/12">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($artists as $artist)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $artist->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 truncate">{{ $artist->name }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs {{ $artist->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $artist->status === 'y' ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $artist->albums_count ?? $artist->albums->count() }}</td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.artists.show', $artist) }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Show</a>
                        <a href="{{ route('admin.artists.edit', $artist) }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
