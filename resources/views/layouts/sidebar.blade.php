<!-- Sidebar oscura -->
<aside class="fixed top-0 left-0 z-40 w-64 h-screen bg-gray-700 text-white transition-transform">
    <div class="h-full px-3 py-4 overflow-y-auto">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center mb-5">
            <img src="{{ asset('images/MS.png') }}" alt="Logo" class="h-24 w-auto">
        </a>

        <!-- Menu -->
        <ul class="space-y-2 font-medium">

            <li class="text-md uppercase text-indigo-400 mt-4">MANAGEMENT</li>

            <!-- GENRES -->
            <li>
                <a href="{{ route('admin.genres.index') }}"
                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-900 {{ request()->routeIs('admin.genres.*') ? 'bg-gray-700' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 6h.008v.008H6V6Z"/>
                    </svg>

                    <span>Genres</span>
                </a>
            </li>

            <!-- ARTISTS -->
            <li>
                <a href="{{ route('admin.artists.index') }}"
                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-900 {{ request()->routeIs('admin.artists.*') ? 'bg-gray-700' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>Artists</span>
                </a>
            </li>

            <!-- ALBUMS -->
            <li>
                <a href="{{ route('admin.albums.index') }}"
                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-900 {{ request()->routeIs('admin.albums.*') ? 'bg-gray-700' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                    </svg>

                    <span>Albums</span>
                </a>
            </li>

            <!-- TRACKS -->
            <li>
                <a href="{{ route('admin.tracks.index') }}"
                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-900 {{ request()->routeIs('admin.tracks.*') ? 'bg-gray-700' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z"/>
                    </svg>

                    <span>Tracks</span>
                </a>
            </li>

            <!-- USERS -->
            <li>
                <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-900 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>

                    <span>Users</span>
                </a>
            </li>

            <!-- PLAYLISTS -->
            <li>
                <a href="{{ route('admin.playlists.index') }}"
                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-900 {{ request()->routeIs('admin.playlists.*') ? 'bg-gray-700' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                    </svg>

                    <span>Playlists</span>
                </a>
            </li>

        </ul>

        <!-- User  -->
        <div class="absolute bottom-4 left-0 w-full px-3">
            <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-700">

                <!-- Icon -->
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-5 h-5 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                <!-- Logout -->
                <div class="flex-1 flex items-center justify-between">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-md font-semibold text-white hover:text-indigo-400">
                            Logout
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</aside>
