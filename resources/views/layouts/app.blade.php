<body class="font-sans antialiased">

<div class="flex min-h-screen bg-gray-100">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col">

        <div class="p-5 text-lg font-bold border-b border-gray-800">
            Music Admin
        </div>

        <nav class="flex-1 p-4 space-y-1 text-sm">

            <a href="{{ route('admin.genres.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.genres.*') ? 'bg-gray-800' : '' }}">
                Genres
            </a>

            <a href="{{ route('admin.albums.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.albums.*') ? 'bg-gray-800' : '' }}">
                Albums
            </a>

            <a href="{{ route('admin.tracks.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.tracks.*') ? 'bg-gray-800' : '' }}">
                Tracks
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                Users
            </a>

            <a href="{{ route('admin.playlists.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.playlists.*') ? 'bg-gray-800' : '' }}">
                Playlists
            </a>

        </nav>

        {{-- USER --}}
        <div class="p-4 border-t border-gray-800 text-sm">

            <div class="mb-2 text-gray-300">
                {{ Auth::user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="text-red-400 hover:text-red-300">
                    Logout
                </button>
            </form>

        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

</div>

</body>