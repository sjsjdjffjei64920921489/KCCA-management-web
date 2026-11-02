<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('venues.index') }}" class="flex items-center gap-3 shrink-0">
                <div class="w-9 h-9 bg-green-700 rounded-lg flex items-center justify-center text-white font-black text-sm">KC</div>
                <div class="hidden sm:block">
                    <div class="font-bold text-gray-900 leading-tight text-sm">KCCA Venues</div>
                    <div class="text-xs text-gray-500 leading-tight">Community & Sports</div>
                </div>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('venues.index') }}"
                   class="{{ request()->routeIs('venues.index') ? 'text-green-700 border-b-2 border-green-700 pb-0.5' : 'text-gray-600 hover:text-green-700' }} transition">
                    Browse Venues
                </a>
                <a href="{{ route('venues.index', ['location' => 'Lugogo']) }}"
                   class="text-gray-600 hover:text-green-700 transition">Lugogo</a>
                <a href="{{ route('venues.index', ['location' => 'Kamwokya']) }}"
                   class="text-gray-600 hover:text-green-700 transition">Kamwokya</a>
                <a href="{{ route('venues.index', ['location' => 'Kabalagala']) }}"
                   class="text-gray-600 hover:text-green-700 transition">Kabalagala</a>
            </div>

            {{-- Auth buttons --}}
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex inline-flex items-center gap-1 px-4 py-2 rounded-lg font-semibold text-xs bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 transition">Login</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg font-semibold text-xs bg-green-700 hover:bg-green-800 text-white transition">Register</a>
                @endguest

                @auth
                    <a href="{{ route('bookings.dashboard') }}"
                       class="{{ request()->routeIs('bookings.dashboard') ? 'text-green-700 font-semibold' : 'text-gray-600 hover:text-green-700' }} hidden sm:inline text-sm font-medium transition">
                        My Bookings
                    </a>
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-green-700 transition focus:outline-none">
                            <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <span class="hidden sm:inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute right-0 top-full mt-1 w-52 bg-white border border-gray-200 rounded-xl shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('bookings.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">📂 My Bookings</a>
                            <a href="{{ route('profile.show') }}"       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">👤 My Account</a>
                            <a href="{{ route('profile.show') }}#edit-profile" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">✏️ Edit Profile</a>
                            <a href="{{ route('profile.show') }}#password"     class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">🔒 Change Password</a>
                            <div class="border-t border-gray-100 mt-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">🚪 Sign Out</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- Mobile menu button --}}
            <button id="mobile-menu-btn" class="md:hidden ml-2 p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="md:hidden hidden border-t border-gray-100 py-3 space-y-1">
            <a href="{{ route('venues.index') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Browse Venues</a>
            <a href="{{ route('venues.index', ['location' => 'Lugogo']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Lugogo</a>
            <a href="{{ route('venues.index', ['location' => 'Kamwokya']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Kamwokya</a>
            <a href="{{ route('venues.index', ['location' => 'Kabalagala']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Kabalagala</a>
            @auth
                <a href="{{ route('bookings.dashboard') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">📂 My Bookings</a>
                <a href="{{ route('profile.show') }}"       class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">👤 My Account</a>
                <form method="POST" action="{{ route('logout') }}" class="px-4">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 font-medium py-2">🚪 Sign Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Login</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm font-semibold text-green-700 hover:bg-green-50 rounded-lg">Register</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function () {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
