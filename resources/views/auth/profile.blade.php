@extends('layouts.app')
@section('title', 'My Account – ' . Auth::user()->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Account</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your personal details, password and bookings</p>
        </div>
        <a href="{{ route('bookings.dashboard') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm bg-green-700 hover:bg-green-800 text-white transition shadow">
            📂 My Bookings
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm flex items-center gap-2 mb-6">
            ✅ <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── LEFT COLUMN ──────────────────────────────────────── --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Avatar card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                {{-- Avatar circle --}}
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-600 to-teal-500 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-white font-black text-3xl">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? 'X', 0, 1)) }}
                    </span>
                </div>
                <h2 class="font-bold text-gray-900 text-lg leading-tight">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                @if(Auth::user()->organization)
                    <p class="text-xs text-green-700 font-medium mt-1 bg-green-50 rounded-full px-3 py-1 inline-block">
                        🏢 {{ Auth::user()->organization }}
                    </p>
                @endif
                @if(Auth::user()->phone)
                    <p class="text-xs text-gray-500 mt-2">📞 {{ Auth::user()->phone }}</p>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        {{ Auth::user()->isAdmin() ? '👑 Administrator' : '👤 Member' }}
                    </span>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Member since {{ Auth::user()->created_at->format('M Y') }}
                </p>
            </div>

            {{-- Stats card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 text-sm mb-4">📊 Booking Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Total Bookings</span>
                        <span class="font-bold text-gray-900 text-lg">{{ $stats['total'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Upcoming</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ $stats['upcoming'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Confirmed</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">{{ $stats['confirmed'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Cancelled</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">{{ $stats['cancelled'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">Quick Links</h3>
                </div>
                <div class="divide-y divide-gray-100 text-sm">
                    <a href="{{ route('bookings.dashboard') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-gray-700">
                        <span>📂</span> My Bookings
                    </a>
                    <a href="{{ route('venues.index') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-gray-700">
                        <span>🏛️</span> Browse Venues
                    </a>
                    <a href="#edit-profile" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-gray-700">
                        <span>✏️</span> Edit Profile
                    </a>
                    <a href="#password" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-gray-700">
                        <span>🔒</span> Change Password
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 hover:bg-red-50 transition text-red-600 text-sm text-left">
                            <span>🚪</span> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── RIGHT COLUMN ─────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Edit profile --}}
            <div id="edit-profile" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">✏️ Edit Profile</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Update your personal information</p>
                </div>
                <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-5">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                                <input type="text" name="name"
                                       value="{{ old('name', Auth::user()->name) }}"
                                       class="w-full rounded-xl border border-gray-300 pl-9 pr-4 py-2.5 text-sm focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                       required>
                            </div>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                                <input type="email" name="email"
                                       value="{{ old('email', Auth::user()->email) }}"
                                       class="w-full rounded-xl border border-gray-300 pl-9 pr-4 py-2.5 text-sm focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                       required>
                            </div>
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">📞</span>
                                <input type="tel" name="phone"
                                       value="{{ old('phone', Auth::user()->phone) }}"
                                       class="w-full rounded-xl border border-gray-300 pl-9 pr-4 py-2.5 text-sm focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                       placeholder="+256 700 000 000">
                            </div>
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Organisation</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🏢</span>
                                <input type="text" name="organization"
                                       value="{{ old('organization', Auth::user()->organization) }}"
                                       class="w-full rounded-xl border border-gray-300 pl-9 pr-4 py-2.5 text-sm focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                       placeholder="Company / NGO / School">
                            </div>
                            @error('organization')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-xl transition shadow">
                            💾 Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change password --}}
            <div id="password" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">🔒 Change Password</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Choose a strong password to protect your account</p>
                </div>
                <form method="POST" action="{{ route('profile.password') }}" class="p-6 space-y-5">
                    @csrf @method('PUT')

                    @error('current_password')
                        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                            ❌ {{ $message }}
                        </div>
                    @enderror

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔑</span>
                            <input type="password" name="current_password" id="cur_pwd"
                                   class="w-full rounded-xl border border-gray-300 pl-9 pr-12 py-2.5 text-sm focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                   placeholder="Your current password" required>
                            <button type="button" onclick="toggleField('cur_pwd','eye_cur')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <span id="eye_cur">👁️</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                                <input type="password" name="password" id="new_pwd"
                                       class="w-full rounded-xl border border-gray-300 pl-9 pr-12 py-2.5 text-sm focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                       placeholder="Min 8 characters" required
                                       oninput="pwdStrength(this.value)">
                                <button type="button" onclick="toggleField('new_pwd','eye_new')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <span id="eye_new">👁️</span>
                                </button>
                            </div>
                            <div class="h-1.5 bg-gray-200 rounded-full mt-2 overflow-hidden">
                                <div id="pwd-bar" class="h-full rounded-full transition-all duration-300 w-0 bg-red-400"></div>
                            </div>
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                                <input type="password" name="password_confirmation" id="conf_pwd"
                                       class="w-full rounded-xl border border-gray-300 pl-9 pr-12 py-2.5 text-sm focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                       placeholder="Repeat new password" required>
                                <button type="button" onclick="toggleField('conf_pwd','eye_conf')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <span id="eye_conf">👁️</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-xl transition shadow">
                            🔒 Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Recent bookings --}}
            @if($recentBookings->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">📋 Recent Bookings</h3>
                    <a href="{{ route('bookings.dashboard') }}" class="text-xs text-green-700 font-semibold hover:underline">View all →</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($recentBookings as $b)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-lg shrink-0">
                                @switch($b->venue->type)
                                    @case('sports_venue') ⚽ @break
                                    @case('community_hall') 🏛️ @break
                                    @case('conference_center') 🏢 @break
                                    @case('outdoor_space') 🌳 @break
                                    @default 🏟️
                                @endswitch
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-gray-900 truncate">{{ $b->venue->name }}</p>
                                <p class="text-xs text-gray-500">{{ $b->event_date->format('d M Y') }} · {{ ucwords(str_replace('_',' ',$b->event_type)) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="
                                @if($b->status==='confirmed') inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800
                                @elseif($b->status==='pending') inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800
                                @else inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($b->status) }}
                            </span>
                            <a href="{{ route('bookings.show', $b->reference_number) }}"
                               class="text-xs text-green-700 font-semibold hover:underline">View</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Danger zone --}}
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-red-100 bg-red-50">
                    <h3 class="font-bold text-red-700 flex items-center gap-2">⚠️ Danger Zone</h3>
                    <p class="text-xs text-red-500 mt-0.5">Permanently delete your account and all associated data</p>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        Once you delete your account, all your booking history will be removed and any upcoming bookings will be cancelled.
                        <strong>This action cannot be undone.</strong>
                    </p>
                    <button onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl transition">
                        🗑️ Delete My Account
                    </button>
                </div>
            </div>

        </div>{{-- end right col --}}
    </div>
</div>

{{-- Delete confirmation modal --}}
<div id="delete-modal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-7 max-w-md w-full shadow-2xl">
        <div class="text-center mb-5">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-3xl">⚠️</span>
            </div>
            <h3 class="font-bold text-gray-900 text-lg">Delete Your Account?</h3>
            <p class="text-sm text-gray-500 mt-1">This will permanently remove your account and cancel all upcoming bookings.</p>
        </div>
        <form method="POST" action="{{ route('profile.delete') }}" class="space-y-4">
            @csrf @method('DELETE')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Type <span class="font-mono bg-red-100 text-red-700 px-1.5 py-0.5 rounded">DELETE</span> to confirm
                </label>
                <input type="text" name="confirm_delete"
                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-2 focus:ring-red-100 focus:outline-none transition font-mono"
                       placeholder="DELETE" required>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit"
                        class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition">
                    Yes, Delete Account
                </button>
                <button type="button"
                        onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="flex-1 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-xl text-sm transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleField(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    if (inp.type === 'password') { inp.type = 'text';     eye.textContent = '🙈'; }
    else                         { inp.type = 'password'; eye.textContent = '👁️'; }
}
function pwdStrength(val) {
    let s = 0;
    if (val.length >= 8) s++;
    if (/[A-Z]/.test(val)) s++;
    if (/[0-9]/.test(val)) s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const bar = document.getElementById('pwd-bar');
    const cols = ['w-0 bg-gray-300','w-1/4 bg-red-400','w-2/4 bg-orange-400','w-3/4 bg-yellow-400','w-full bg-green-500'];
    bar.className = `h-full rounded-full transition-all duration-300 ${cols[s]}`;
}
</script>
@endpush
