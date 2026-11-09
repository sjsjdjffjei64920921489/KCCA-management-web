@extends('layouts.admin')
@section('title', $user->name)
@section('breadcrumb', 'User Details')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-700 font-bold text-lg shrink-0">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-extrabold text-gray-900">{{ $user->name }}</h1>
                @if($user->role === 'admin')
                    <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Admin</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">User</span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
        </div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back to Users</a>
</div>

{{-- ── Stats ──────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs text-gray-400 mb-1">Total Bookings</p>
        <p class="text-2xl font-extrabold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs text-gray-400 mb-1">Pending</p>
        <p class="text-2xl font-extrabold text-amber-600">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs text-gray-400 mb-1">Confirmed</p>
        <p class="text-2xl font-extrabold text-green-600">{{ $stats['confirmed'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs text-gray-400 mb-1">Total Spent</p>
        <p class="text-lg font-extrabold text-gray-900">UGX {{ number_format($stats['total_spent']) }}</p>
    </div>
</div>

{{-- ── User info ──────────────────────────────────────────────────── }}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-sm font-bold text-gray-700 mb-4">Contact Information</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Email</p>
            <p class="font-medium text-gray-900">{{ $user->email }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Phone</p>
            <p class="font-medium text-gray-900">{{ $user->phone ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Organization</p>
            <p class="font-medium text-gray-900">{{ $user->organization ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Member Since</p>
            <p class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
        </div>
    </div>
</div>

{{-- ── Booking history ────────────────────────────────────────────── }}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-bold text-gray-700">📋 Booking History ({{ $user->bookings->count() }})</h3>
    </div>
    @if($user->bookings->isEmpty())
        <div class="p-10 text-center text-gray-400 text-sm">No bookings yet.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 border-b border-gray-100 bg-gray-50">
                        <th class="px-6 py-2 font-medium">Reference</th>
                        <th class="px-4 py-2 font-medium">Venue</th>
                        <th class="px-4 py-2 font-medium">Event</th>
                        <th class="px-4 py-2 font-medium">Date</th>
                        <th class="px-4 py-2 font-medium text-center">Guests</th>
                        <th class="px-4 py-2 font-medium text-right">Amount</th>
                        <th class="px-4 py-2 font-medium text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($user->bookings->sortByDesc('event_date') as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="font-mono text-xs font-semibold text-green-700 hover:underline">{{ $booking->reference_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ Str::limit($booking->venue->name ?? '—', 22) }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $booking->event_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-gray-600 text-center text-xs">{{ number_format($booking->expected_guests) }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 text-xs">{{ 'UGX ' . number_format($booking->total_amount) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ match($booking->status) {
                                        'pending'   => 'bg-amber-100 text-amber-700',
                                        'confirmed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'completed' => 'bg-gray-100 text-gray-600',
                                        default     => 'bg-gray-100 text-gray-600',
                                    }} ">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
