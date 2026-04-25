<x-app-layout>
    <x-slot name="header">
        User Details
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow border-l-4 border-indigo-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
                User: {{ $user->username }}
            </h2>

            <a href="{{ route('admin.users.index') }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Back
            </a>
        </div>

        <!-- Info -->
        <dl class="grid grid-cols-1 gap-2 text-sm">
            <div><dt class="font-semibold inline">ID:</dt> <dd class="inline">{{ $user->id }}</dd></div>
            <div><dt class="font-semibold inline">Username:</dt> <dd class="inline">{{ $user->username }}</dd></div>
            <div><dt class="font-semibold inline">Email:</dt> <dd class="inline">{{ $user->email }}</dd></div>
            <div>
                <dt class="font-semibold inline">Status:</dt>
                <dd class="inline">
                    <span class="px-2 py-1 rounded-full text-xs {{ $user->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->status === 'y' ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>
        </dl>

        <hr class="my-4">

        <!-- Playlists -->
        <h3 class="font-semibold mb-2">Playlists</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($user->playlists as $playlist)
                <div class="border p-3 rounded-lg">
                    <p class="font-semibold">{{ $playlist->name }}</p>

                    <span class="px-2 py-1 rounded-full text-xs {{ $playlist->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $playlist->status === 'y' ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            @endforeach
        </div>

        <!-- Actions -->
        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Edit
            </a>
        </div>
    </div>
</x-app-layout>