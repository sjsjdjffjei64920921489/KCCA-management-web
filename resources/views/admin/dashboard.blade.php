@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', '📊 Dashboard Overview')
@section('page-subtitle', 'Live data from your KCCA Venues database')

@section('content')

{{-- ── Stats Cards ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-3xl">🏛️</span>
            <span class="text-xs font-semibold text-green-700 bg-green-100 px-2 py-1 rounded-full">Active</span>
        </div>
        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['active_venues'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Venues Online</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['total_venues'] }} total</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-3xl">📋</span>
            <span class="text-xs font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded-full">Total</span>
        </div>
        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['total_bookings'] }}</p>
        <p class="text-sm text-gray-500 mt-1">All Bookings</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['upcoming'] }} upcoming</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-3xl">⏳</span>
            <span class="text-xs font-semibold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full">Action needed</span>
        </div>
        <p class="text-3xl font-extrabold text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Pending Bookings</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['confirmed'] }} confirmed</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-3xl">👥</span>
            <span class="text-xs font-semibold text-purple-700 bg-purple-100 px-2 py-1 rounded-full">+{{ $stats['new_users_week'] }} this week</span>
        </div>
        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['total_users'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Registered Users</p>
    </div>
</div>

{{-- ── Revenue + Booking status ────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    {{-- Revenue card --}}
    <div class="bg-gradient-to-br from-green-700 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-green-200 text-sm font-medium mb-1">💰 Total Revenue</p>
        <p class="text-3xl font-extrabold">UGX {{ number_format($stats['revenue']) }}</p>
        <p class="text-green-200 text-xs mt-2">From confirmed bookings</p>
        <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-3 gap-2 text-center text-xs">
            <div>
                <p class="font-bold text-lg">{{ $stats['confirmed'] }}</p>
                <p class="text-green-200">Confirmed</p>
            </div>
            <div>
                <p class="font-bold text-lg">{{ $stats['pending'] }}</p>
                <p class="text-green-200">Pending</p>
            </div>
            <div>
                <p class="font-bold text-lg">{{ $stats['cancelled'] }}</p>
                <p class="text-green-200">Cancelled</p>
            </div>
        </div>
    </div>

    {{-- Bookings by location --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-900 text-sm mb-4">📍 Bookings by Location</h3>
        @php $maxLoc = $byLocation->max() ?: 1; @endphp
        <div class="space-y-3">
            @foreach($byLocation as $location => $count)
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-medium text-gray-700">{{ $location }}</span>
                    <span class="font-bold text-gray-900">{{ $count }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-600 rounded-full transition-all"
                         style="width: {{ ($count / $maxLoc) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
            @if($byLocation->isEmpty())
                <p class="text-gray-400 text-xs text-center py-4">No bookings yet</p>
            @endif
        </div>
    </div>

    {{-- Bookings by event type --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-900 text-sm mb-4">🎉 Top Event Types</h3>
        @php $maxEv = $byEventType->max() ?: 1; @endphp
        <div class="space-y-3">
            @foreach($byEventType as $type => $count)
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-medium text-gray-700 capitalize">{{ str_replace('_',' ',$type) }}</span>
                    <span class="font-bold text-gray-900">{{ $count }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full"
                         style="width: {{ ($count / $maxEv) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
            @if($byEventType->isEmpty())
                <p class="text-gray-400 text-xs text-center py-4">No bookings yet</p>
            @endif
        </div>
    </div>
</div>

{{-- ── Monthly bookings chart ───────────────────────────────────── --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
    <h3 class="font-bold text-gray-900 mb-5">📈 Monthly Bookings (Last 6 Months)</h3>
    @php $maxMonth = max(array_column($monthlyBookings, 'count')) ?: 1; @endphp
    <div class="flex items-end gap-3 h-32">
        @foreach($monthlyBookings as $m)
        <div class="flex-1 flex flex-col items-center gap-1">
            <span class="text-xs font-bold text-gray-700">{{ $m['count'] }}</span>
            <div class="w-full rounded-t-lg transition-all"
                 style="height: {{ max(4, ($m['count'] / $maxMonth) * 100) }}px;
                        background: {{ $m['count'] > 0 ? '#16a34a' : '#e5e7eb' }}">
            </div>
            <span class="text-xs text-gray-400 text-center leading-tight">{{ $m['month'] }}</span>
        </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ── Recent bookings ─────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">🕐 Recent Bookings</h3>
            <a href="{{ route('admin.bookings') }}" class="text-xs text-green-700 font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentBookings as $b)
            <div class="px-5 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $b->venue->name }}</p>
                    <p class="text-xs text-gray-500">{{ $b->contact_name }} · {{ $b->event_date->format('d M Y') }}</p>
                    <p class="text-xs font-mono text-gray-400">{{ $b->reference_number }}</p>
                </div>
                <div class="flex flex-col items-end gap-1 shrink-0">
                    <span class="
                        @if($b->status==='confirmed') inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800
                        @elseif($b->status==='pending') inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800
                        @elseif($b->status==='cancelled') inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800
                        @else inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 @endif">
                        {{ ucfirst($b->status) }}
                    </span>
                    <span class="text-xs font-bold text-green-700">UGX {{ number_format($b->total_amount) }}</span>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400 text-sm">No bookings yet</div>
            @endforelse
        </div>
    </div>

    {{-- ── Recent users ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">👥 Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="text-xs text-green-700 font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentUsers as $u)
            <div class="px-5 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
                    {{ strtoupper(substr($u->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $u->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $u->email }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs font-bold text-gray-700">{{ $u->bookings_count }} bookings</p>
                    <p class="text-xs text-gray-400">{{ $u->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400 text-sm">No users yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
