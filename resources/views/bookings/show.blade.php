@extends('layouts.app')

@section('title', 'Booking ' . $booking->reference_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-500 flex items-center gap-2 mb-6">
        <a href="{{ route('bookings.dashboard') }}" class="hover:text-green-700">My Bookings</a>
        <span>›</span>
        <span class="text-gray-900 font-medium font-mono">{{ $booking->reference_number }}</span>
    </div>

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Booking Details</h1>
                <p class="text-sm font-mono text-gray-500">{{ $booking->reference_number }}</p>
            </div>
            <span class="
                @if($booking->status === 'confirmed') inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800
                @elseif($booking->status === 'pending') inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800
                @elseif($booking->status === 'cancelled') inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-800
                @else inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-700 @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-400">Venue:</span> <span class="font-semibold">{{ $booking->venue->name }}</span></div>
            <div><span class="text-gray-400">Location:</span> <span class="font-semibold">{{ $booking->venue->location }}</span></div>
            <div><span class="text-gray-400">Event:</span> <span class="font-semibold capitalize">{{ str_replace('_',' ', $booking->event_type) }}</span></div>
            <div><span class="text-gray-400">Date:</span> <span class="font-semibold">{{ $booking->event_date->format('l, d F Y') }}</span></div>
            <div><span class="text-gray-400">Time:</span> <span class="font-semibold">{{ substr($booking->start_time,0,5) }} – {{ substr($booking->end_time,0,5) }} ({{ $booking->duration_hours }} hrs)</span></div>
            <div><span class="text-gray-400">Guests:</span> <span class="font-semibold">{{ number_format($booking->expected_guests) }}</span></div>
            <div><span class="text-gray-400">Contact:</span> <span class="font-semibold">{{ $booking->contact_name }}</span></div>
            <div><span class="text-gray-400">Phone:</span> <span class="font-semibold">{{ $booking->contact_phone }}</span></div>
            <div><span class="text-gray-400">Email:</span> <span class="font-semibold">{{ $booking->contact_email }}</span></div>
            @if($booking->organization)
            <div><span class="text-gray-400">Organisation:</span> <span class="font-semibold">{{ $booking->organization }}</span></div>
            @endif
            <div class="sm:col-span-2">
                <span class="text-gray-400">Total Amount:</span>
                <span class="font-bold text-green-700 text-lg ml-1">{{ $booking->formatted_total }}</span>
            </div>
        </div>

        @if($booking->special_requirements)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 font-semibold mb-1">Special Requirements</p>
            <p class="text-sm text-gray-700">{{ $booking->special_requirements }}</p>
        </div>
        @endif

        @if($booking->status === 'cancelled' && $booking->cancellation_reason)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 font-semibold mb-1">Cancellation Reason</p>
            <p class="text-sm text-red-700">{{ $booking->cancellation_reason }}</p>
            <p class="text-xs text-gray-400 mt-1">Cancelled on {{ $booking->cancelled_at?->format('d M Y H:i') }}</p>
        </div>
        @endif
    </div>

    {{-- Venue mini card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center text-2xl">
                @switch($booking->venue->type)
                    @case('sports_venue') ⚽ @break
                    @case('community_hall') 🏛️ @break
                    @case('conference_center') 🏢 @break
                    @case('outdoor_space') 🌳 @break
                    @default 🏟️
                @endswitch
            </div>
            <div class="flex-1">
                <div class="font-bold text-gray-900">{{ $booking->venue->name }}</div>
                <div class="text-sm text-gray-500">{{ $booking->venue->address }}</div>
                @if($booking->venue->contact_phone)
                    <div class="text-sm text-gray-500">📞 {{ $booking->venue->contact_phone }}</div>
                @endif
            </div>
            <a href="{{ route('venues.show', $booking->venue->slug) }}" class="btn-secondary text-xs px-3 py-1.5 shrink-0">
                View Venue
            </a>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('bookings.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 transition-all duration-200 flex-1 justify-center">← My Bookings</a>
        @if($booking->canBeCancelled())
            <button onclick="document.getElementById('cancel-modal').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-red-600 hover:bg-red-700 text-white transition-all duration-200 flex-1 justify-center">Cancel Booking</button>
        @endif
        <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 transition-all duration-200 flex-1 justify-center">🖨️ Print</button>
    </div>

    {{-- Cancel modal --}}
    @if($booking->canBeCancelled())
    <div id="cancel-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
            <h3 class="font-bold text-gray-900 text-lg mb-4">Cancel This Booking?</h3>
            <form method="POST" action="{{ route('bookings.cancel', $booking->reference_number) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Reason <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="cancellation_reason" rows="3" class="form-input resize-none"
                              placeholder="Tell us why you're cancelling…"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-red-600 hover:bg-red-700 text-white transition-all duration-200 flex-1">Yes, Cancel</button>
                    <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')"
                            class="btn-secondary flex-1">Keep It</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
