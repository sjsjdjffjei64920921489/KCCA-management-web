<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – KCCA Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- ── Sidebar ──────────────────────────────────────────────── --}}
    <aside id="sidebar" class="w-64 bg-gray-900 text-white min-h-screen flex flex-col fixed top-0 left-0 z-40 transition-transform duration-300">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center font-black text-sm">KC</div>
                <div>
                    <div class="font-bold text-sm">KCCA Admin</div>
                    <div class="text-xs text-gray-400">Management Portal</div>
                </div>
            </div>
        </div>

        {{-- Admin info --}}
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-green-700 flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
        </div>

        {{-- Nav links --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <span class="text-lg">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.bookings') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('admin.bookings') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <span class="text-lg">📋</span> Bookings
                @php $pending = \App\Models\Booking::where('status','pending')->count(); @endphp
                @if($pending > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.venues') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('admin.venues') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <span class="text-lg">🏛️</span> Venues
            </a>
            <a href="{{ route('admin.users') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('admin.users') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <span class="text-lg">👥</span> Users
            </a>

            <div class="pt-4 border-t border-gray-700">
                <a href="{{ route('venues.index') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition">
                    <span class="text-lg">🌐</span> View Website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-400 hover:bg-red-900/30 transition">
                        <span class="text-lg">🚪</span> Sign Out
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- ── Main content ─────────────────────────────────────────── --}}
    <div class="flex-1 ml-64 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-500">@yield('page-subtitle', 'KCCA Venues Management System')</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs text-gray-400">{{ now()->format('D, d M Y') }}</span>
                <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm flex items-center gap-2">
                ❌ {{ session('error') }}
            </div>
        @endif

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 px-6 py-3 text-xs text-gray-400 text-center">
            KCCA Venues Admin © {{ date('Y') }} · Kampala Capital City Authority
        </footer>
    </div>

</body>
</html>
