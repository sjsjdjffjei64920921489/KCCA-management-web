@extends('layouts.app')

@section('title', 'Booking Confirmed – ' . $booking->reference_number)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Success banner --}}
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full
            {{ $booking->status === 'confirmed' ? 'bg-green-100' : 'bg-yellow-100' }} mb-6">
            <span class="text-4xl">{{ $booking->status === 'confirmed' ? '✅' : '⏳' }}</span>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
            {{ $booking->status === 'confirmed' ? 'Booking Confirmed!' : 'Request Received!' }}
        </h1>
        <p class="text-gray-500 text-lg">
            {{ $booking->status === 'confirmed'
                ? 'Your venue has been successfully booked.'
                : 'Your booking request is pending approval. You will be notified soon.' }}
        </p>
    </div>

    {{-- Reference card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8 mb-6">

        {{-- Reference number --}}
        <div class="bg-gradient-to-r from-green-50 to-teal-50 border-2 border-green-200 rounded-2xl p-6 text-center mb-8">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-widest mb-2">Booking Reference Number</p>
            <p class="text-3xl font-black text-green-700 tracking-wider">{{ $booking->reference_number }}</p>
            <p class="text-xs text-gray-400 mt-2">Save this reference number for your records</p>
        </div>

        {{-- Status badge --}}
        <div class="flex justify-center mb-6">
            <span class="
                @if($booking->status === 'confirmed') inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800
                @elseif($booking->status === 'pending') inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800
                @else inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-800 @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </div>

        {{-- Booking details --}}
        <div class="space-y-4 text-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Venue</p>
                    <p class="font-bold text-gray-900">{{ $booking->venue->name }}</p>
                    <p class="text-gray-500 text-xs">{{ $booking->venue->address }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Event</p>
                    <p class="font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Date</p>
                    <p class="font-bold text-gray-900">{{ $booking->event_date->format('l, d F Y') }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Time</p>
                    <p class="font-bold text-gray-900">{{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }}</p>
                    <p class="text-gray-500 text-xs">{{ $booking->duration_hours }} hours</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Expected Guests</p>
                    <p class="font-bold text-gray-900">{{ number_format($booking->expected_guests) }} people</p>
                </div>

                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <p class="text-xs text-green-600 font-semibold uppercase tracking-wide mb-1">Total Amount</p>
                    <p class="font-black text-green-700 text-lg">{{ $booking->formatted_total }}</p>
                </div>
            </div>

            {{-- Contact --}}
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-2">Contact Details</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm">
                    <div><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $booking->contact_name }}</span></div>
                    <div><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $booking->contact_phone }}</span></div>
                    <div><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $booking->contact_email }}</span></div>
                </div>
            </div>

            @if($booking->special_requirements)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Special Requirements</p>
                <p class="text-gray-700">{{ $booking->special_requirements }}</p>
            </div>
            @endif

            @if($booking->confirmed_at)
            <div class="text-xs text-gray-400 text-center">
                Confirmed on {{ $booking->confirmed_at->format('d M Y \a\t H:i') }}
            </div>
            @endif
        </div>
    </div>

    {{-- What's next --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">📋 What Happens Next?</h3>
        <ol class="space-y-3 text-sm text-gray-600">
            @if($booking->status === 'pending')
                <li class="flex gap-3"><span class="font-bold text-green-700 shrink-0">1.</span> Our team will review your booking request within 24 hours.</li>
                <li class="flex gap-3"><span class="font-bold text-green-700 shrink-0">2.</span> You will receive a confirmation email at <strong>{{ $booking->contact_email }}</strong>.</li>
                <li class="flex gap-3"><span class="font-bold text-green-700 shrink-0">3.</span> Payment instructions will be included in the confirmation email.</li>
            @else
                <li class="flex gap-3"><span class="font-bold text-green-700 shrink-0">1.</span> A confirmation has been sent to <strong>{{ $booking->contact_email }}</strong>.</li>
                <li class="flex gap-3"><span class="font-bold text-green-700 shrink-0">2.</span> Please make payment of <strong>{{ $booking->formatted_total }}</strong> before your event date.</li>
                <li class="flex gap-3"><span class="font-bold text-green-700 shrink-0">3.</span> Contact the venue on <strong>{{ $booking->venue->contact_phone }}</strong> for any arrangements.</li>
            @endif
            <li class="flex gap-3"><span class="font-bold text-green-700 shrink-0">4.</span> Quote reference <strong>{{ $booking->reference_number }}</strong> in all communications.</li>
        </ol>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('bookings.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-green-700 hover:bg-green-800 text-white transition-all duration-200 flex-1 justify-center">
            📂 View My Bookings
        </a>
        <a href="{{ route('venues.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 transition-all duration-200 flex-1 justify-center">
            🏛️ Browse More Venues
        </a>
        <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 transition-all duration-200 flex-1 justify-center">
            🖨️ Print / Save
        </button>
    </div>
</div>
@endsection
