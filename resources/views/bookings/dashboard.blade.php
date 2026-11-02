@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Personal welcome banner ──────────────────────────────── --}}
    <div class="bg-gradient-to-r from-green-700 via-green-600 to-teal-600 rounded-2xl p-6 sm:p-8 mb-8 text-white shadow-lg relative overflow-hidden">
        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-4 right-8 text-8xl">🏛️</div>
            <div class="absolute bottom-2 right-32 text-6xl">⚽</div>
            <div class="absolute top-8 right-48 text-5xl">📋</div>
        </div>

        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                {{-- Avatar --}}
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0 shadow-inner">
                    <span class="text-white font-black text-2xl">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? 'X', 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="text-green-200 text-sm font-medium">
                        {{ now()->hour < 12 ? '🌅 Good morning' : (now()->hour < 17 ? '☀️ Good afternoon' : '🌙 Good evening') }},
                    </p>
                    <h1 class="text-2xl font-extrabold leading-tight">{{ explode(' ', Auth::user()->name)[0] }}!</h1>
                    <p class="text-green-200 text-sm mt-0.5">
                        @if($upcoming->count() > 0)
                            You have <strong class="text-white">{{ $upcoming->count() }}</strong> upcoming {{ Str::plural('booking', $upcoming->count()) }}.
                        @else
                            No upcoming bookings yet. Ready to book a venue?
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('venues.index') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-white text-green-700 font-bold text-sm rounded-xl hover:bg-green-50 transition shadow shrink-0">
                + Book a Venue
            </a>
        </div>

        {{-- Mini stats strip --}}
        <div class="relative grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-white/20">
            <div class="text-center">
                <p class="text-2xl font-extrabold">{{ Auth::user()->bookings()->count() }}</p>
                <p class="text-green-200 text-xs mt-0.5">Total</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-extrabold">{{ $upcoming->count() }}</p>
                <p class="text-green-200 text-xs mt-0.5">Upcoming</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-extrabold">{{ Auth::user()->bookings()->where('status','confirmed')->count() }}</p>
                <p class="text-green-200 text-xs mt-0.5">Confirmed</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-extrabold">{{ Auth::user()->bookings()->where('status','cancelled')->count() }}</p>
                <p class="text-green-200 text-xs mt-0.5">Cancelled</p>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm flex items-center gap-2 mb-6">
            ✅ <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ── Upcoming bookings ────────────────────────────────────── --}}
    <section class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">📅 Upcoming Bookings</h2>
            @if($upcoming->count() > 0)
                <span class="text-xs text-gray-400">{{ $upcoming->count() }} {{ Str::plural('booking', $upcoming->count()) }}</span>
            @endif
        </div>

        @if($upcoming->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-14 text-center">
                <div class="text-5xl mb-4">📭</div>
                <p class="font-semibold text-gray-700 mb-1">No upcoming bookings</p>
                <p class="text-sm text-gray-400 mb-6">Browse our venues and book your next event.</p>
                <a href="{{ route('venues.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-xl transition">
                    🏛️ Browse Venues
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($upcoming as $booking)
                    @include('partials.booking-row', ['booking' => $booking])
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Past & cancelled ─────────────────────────────────────── --}}
    @if($past->isNotEmpty())
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">🗂️ Past & Cancelled</h2>
            <span class="text-xs text-gray-400">{{ $past->count() }} records</span>
        </div>
        <div class="space-y-3">
            @foreach($past as $booking)
                @include('partials.booking-row', ['booking' => $booking, 'compact' => true])
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── Account quick access ─────────────────────────────────── --}}
    <div class="mt-10 bg-gray-50 rounded-2xl p-6 border border-gray-200">
        <h3 class="font-bold text-gray-900 text-sm mb-4">⚙️ Account</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('profile.show') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:border-green-400 hover:bg-green-50 text-gray-700 font-medium text-sm rounded-xl transition">
                👤 My Profile
            </a>
            <a href="{{ route('profile.show') }}#edit-profile"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:border-green-400 hover:bg-green-50 text-gray-700 font-medium text-sm rounded-xl transition">
                ✏️ Edit Details
            </a>
            <a href="{{ route('profile.show') }}#password"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:border-green-400 hover:bg-green-50 text-gray-700 font-medium text-sm rounded-xl transition">
                🔒 Change Password
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:border-red-400 hover:bg-red-50 text-red-600 font-medium text-sm rounded-xl transition">
                    🚪 Sign Out
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
