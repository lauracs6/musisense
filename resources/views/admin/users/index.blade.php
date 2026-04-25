<x-app-layout>
    <x-slot name="header">
        Users
    </x-slot>

    <!-- Filters -->
    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search by name or email..."
               class="border-2 border-sky-400 p-2 w-64">

        <select name="status" class="border-2 border-yellow-300 p-2 pr-8 w-48">
            <option value="">All statuses</option>
            <option value="y" {{ request('status') === 'y' ? 'selected' : '' }}>Active</option>
            <option value="n" {{ request('status') === 'n' ? 'selected' : '' }}>Inactive</option>
        </select>

        <button class="bg-gray-700 text-gray-300 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-500 px-4 py-2 rounded-xl">
            Filter
        </button>

        <a href="{{ route('admin.users.index') }}"
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-3/12">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/12">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-3/12">Playlists</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $user->id }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700 truncate">
                        {{ $user->username }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700 truncate">
                        {{ $user->email }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs {{ $user->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->status === 'y' ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        <p class="font-semibold">
                            {{ $user->playlists_count }} playlists
                        </p>

                        <div class="text-xs text-gray-600 mt-1 space-y-1">
                            @foreach($user->playlists as $playlist)
                                <div class="truncate">
                                    - {{ $playlist->name }}
                                </div>
                            @endforeach
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                            Show
                        </a>

                        <a href="{{ route('admin.users.edit', $user) }}"
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
        {{ $users->links() }}
    </div>
</x-app-layout>