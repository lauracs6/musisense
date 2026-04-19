<x-app-layout>
    <div class="min-h-screen p-6 bg-white text-gray-800">

        <h1 class="text-2xl font-bold mb-6">Users</h1>

        <!-- FILTERS -->
        <form method="GET" class="mb-6 flex flex-col md:flex-row gap-4">

            <!-- SEARCH -->
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search by name or email..."
                   class="border rounded-lg p-2 w-full md:w-1/3">

            <!-- STATUS FILTER -->
            <select name="status" class="border rounded-lg p-2 w-full md:w-1/4">
                <option value="">All statuses</option>
                <option value="y" {{ request('status') === 'y' ? 'selected' : '' }}>Active</option>
                <option value="n" {{ request('status') === 'n' ? 'selected' : '' }}>Inactive</option>
            </select>

            <!-- BUTTONS -->
            <div class="flex gap-2">
                <button class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                    Filter
                </button>

                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-300 px-4 py-2 rounded-lg">
                    Reset
                </a>
            </div>

        </form>

        <!-- USERS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($users as $user)
                <div class="border rounded-2xl p-4 shadow hover:shadow-md hover:shadow-indigo-800 transition flex flex-col justify-between">

                    <div>
                        <h2 class="text-lg font-semibold">{{ $user->username }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>

                        <p class="mt-2">
                            Status:
                            <span class="font-semibold {{ $user->status === 'y' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->status === 'y' ? 'Active' : 'Inactive' }}
                            </span>
                        </p>

                        <p class="mt-2">
                            <strong>Playlists:</strong> {{ $user->playlists_count }}
                            @foreach($user->playlists as $playlist)
                                <span class="block text-md text-gray-700 ms-4">
                                    - {{ $playlist->name }}
                                </span>
                            @endforeach
                        </p>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="bg-indigo-700 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-800">
                            Show
                        </a>

                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="bg-indigo-700 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-800">
                            Edit
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>

    </div>
</x-app-layout>