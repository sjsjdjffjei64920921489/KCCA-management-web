@extends('layouts.admin')
@section('title', 'Bookings')
@section('breadcrumb', 'Manage Bookings')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-gray-900">Bookings</h1>
        <p class="text-sm text-gray-500 mt-0.5">View and manage all event bookings.</p>
    </div>
</div>

{{-- ── Status filter tabs ──────────────────────────────────────────── --}}
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('admin.bookings.index') }}"
       class="px-4 py-2 text-sm font-medium rounded-lg transition {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        All ({{ array_sum($statusCounts) }})
    </a>
    @foreach($statuses as $status)
        <a href="{{ route('admin.bookings.index', ['status' => $status]) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request('status') === $status ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }} flex items-center gap-1.5">
            {{ ucfirst($status) }}
            <span class="text-xs {{ request('status') === $status ? 'text-gray-300' : 'text-gray-400' }}({{ $statusCounts[$status] }})</span>
        </a>
    @endforeach
</div>

{{-- ── Filters ──────────────────────────────────────────────────────── --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="lg:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search reference, name, email, event type, venue..."
                   class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">
        </div>
        <div>
            <select name="venue_id" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none bg-white">
                <option value="">All Venues</option>
                @foreach($venues as $venue)
                    <option value="{{ $venue->id }}" @selected(request('venue_id') == $venue->id)>{{ $venue->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none" title="Date from">
        </div>
        <div class="flex gap-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none" title="Date to">
            <button type="submit" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition shrink-0">Filter</button>
        </div>
    </div>
</form>

{{-- ── Bookings table ──────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 border-b border-gray-100 bg-gray-50">
                    <th class="px-4 py-3 font-semibold">Reference</th>
                    <th class="px-4 py-3 font-semibold">Venue</th>
                    <th class="px-4 py-3 font-semibold">Customer</th>
                    <th class="px-4 py-3 font-semibold">Event</th>
                    <th class="px-4 py-3 font-semibold text-center">Guests</th>
                    <th class="px-4 py-3 font-semibold text-right">Amount</th>
                    <th class="px-4 py-3 font-semibold text-center">Status</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="font-mono text-xs font-semibold text-green-700 hover:underline">{{ $booking->reference_number }}</a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $booking->created_at->format('M d') }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <p class="font-medium text-gray-900 text-xs">{{ Str::limit($booking->venue->name, 22) }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->venue->location }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <p class="font-medium text-gray-900 text-xs">{{ $booking->contact_name }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->contact_email }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <p class="text-xs capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->event_date->format('M d, Y') }} · {{ substr($booking->start_time, 0, 5) }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-center">{{ number_format($booking->expected_guests) }}</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-900">{{ 'UGX ' . number_format($booking->total_amount) }}</td>
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
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No bookings found matching your filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($bookings->hasPages())
    <div class="mt-4">{{ $bookings->withQueryString()->links() }}</div>
@endif

@endsection
