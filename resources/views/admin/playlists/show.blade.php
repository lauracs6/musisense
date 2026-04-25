<x-app-layout>
    <x-slot name="header">
        Playlist Details
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow border-l-4 border-indigo-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
                Playlist: {{ $playlist->name }}
            </h2>

            <a href="{{ route('admin.playlists.index') }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Back
            </a>
        </div>

        <!-- Info -->
        <dl class="grid grid-cols-1 gap-2 text-sm">
            <div>
                <dt class="font-semibold inline">ID:</dt>
                <dd class="inline">{{ $playlist->id }}</dd>
            </div>

            <div>
                <dt class="font-semibold inline">Name:</dt>
                <dd class="inline">{{ $playlist->name }}</dd>
            </div>

            <div>
                <dt class="font-semibold inline">User:</dt>
                <dd class="inline">{{ $playlist->user->username ?? 'Unknown' }}</dd>
            </div>

            <div>
                <dt class="font-semibold inline">Status:</dt>
                <dd class="inline">
                    <span class="px-2 py-1 rounded-full text-xs {{ $playlist->status === 'y' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $playlist->status === 'y' ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>
        </dl>

        <hr class="my-4">

        <!-- Tracks -->
        <h3 class="font-semibold mb-2">Tracks</h3>
        <ul class="divide-y divide-gray-200">
            @foreach($playlist->tracks->sortBy('pivot.position') as $track)
                <li class="py-2 text-sm">
                    {{ $track->pivot->position }}.
                    {{ $track->title }} - {{ $track->artist }}
                </li>
            @endforeach
        </ul>

        <!-- Actions -->
        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.playlists.edit', $playlist) }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Edit
            </a>
        </div>
    </div>
</x-app-layout>