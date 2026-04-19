<x-app-layout>
    <div class="min-h-screen p-6 bg-white text-gray-800">

        <h1 class="text-2xl font-bold mb-6">User details</h1>

        <div class="border rounded-2xl p-6 hover:shadow-md hover:shadow-indigo-800 flex justify-between items-start">

            <div>
                <h1 class="text-2xl font-bold mb-4">{{ $user->username }}</h1>

                <p><strong>Email:</strong> {{ $user->email }}</p>

                <p class="mt-2">
                    <strong>Status:</strong>
                    <span class="{{ $user->status === 'y' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $user->status === 'y' ? 'Active' : 'Inactive' }}
                    </span>
                </p>

                </br>

                <!-- PLAYLISTS -->
                <p class="text-xl font-semibold ">Playlists</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($user->playlists as $playlist)
                        <div class="border p-4 rounded-xl">
                            <p class="font-semibold">{{ $playlist->name }}</p>

                            <p class="text-sm">
                                Status:
                                <span class="{{ $playlist->status === 'y' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $playlist->status === 'y' ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>         

            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                    Edit
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                    Back
                </a>
            </div> 

        </div>        

    </div>
</x-app-layout>