@extends('layouts.admin')
@section('title', 'Booking ' . $booking->reference_number)
@section('breadcrumb', 'Booking Details')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-xl font-extrabold text-gray-900 font-mono">{{ $booking->reference_number }}</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                {{ match($booking->status) {
                    'pending'   => 'bg-amber-100 text-amber-700',
                    'confirmed' => 'bg-green-100 text-green-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    'completed' => 'bg-gray-100 text-gray-600',
                    default     => 'bg-gray-100 text-gray-600',
                }} ">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
        <p class="text-sm text-gray-500 mt-0.5">Booked on {{ $booking->created_at->format('M d, Y \a\t h:i A') }}</p>
    </div>
    <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back to Bookings</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── Left column: Booking details ──────────────────────────── --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Event details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">🎉 Event Details</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Event Type</p>
                    <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $booking->event_type) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Event Date</p>
                    <p class="font-medium text-gray-900">{{ $booking->event_date->format('l, M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Start Time</p>
                    <p class="font-medium text-gray-900">{{ $booking->start_time->format('h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">End Time</p>
                    <p class="font-medium text-gray-900">{{ $booking->end_time->format('h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Expected Guests</p>
                    <p class="font-medium text-gray-900">{{ number_format($booking->expected_guests) }} people</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Duration</p>
                    <p class="font-medium text-gray-900">{{ $booking->duration_hours }} hours</p>
                </div>
            </div>

            @if($booking->special_requirements)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Special Requirements</p>
                    <p class="text-sm text-gray-700">{{ $booking->special_requirements }}</p>
                </div>
            @endif

            @if($booking->organization)
                <div class="mt-3">
                    <p class="text-xs text-gray-400 mb-0.5">Organization</p>
                    <p class="text-sm text-gray-700">{{ $booking->organization }}</p>
                </div>
            @endif
        </div>

        {{-- Venue details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">🏟️ Venue</h3>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-xl shrink-0">
                    @if($booking->venue->hasImage())
                        <img src="{{ $booking->venue->image_url }}" alt="{{ $booking->venue->name }}" class="w-full h-full rounded-lg object-cover">
                    @else
                        🏟️
                    @endif
                </div>
                <div class="flex-1">
                    <a href="{{ route('venues.show', $booking->venue->slug) }}" target="_blank" class="font-semibold text-gray-900 hover:text-green-700">{{ $booking->venue->name }}</a>
                    <p class="text-sm text-gray-500">{{ $booking->venue->address }}, {{ $booking->venue->location }}</p>
                    <p class="text-xs text-gray-400 mt-1">Capacity: {{ number_format($booking->venue->capacity) }} · {{ $booking->venue->type_label }}</p>
                    @if($booking->venue->facilities->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 mt-3">
                            @foreach($booking->venue->facilities as $facility)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-50 text-gray-600 text-xs rounded-md">
                                    {{ $facility->icon ?? '✓' }} {{ $facility->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contact details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">👤 Customer / Contact</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Contact Name</p>
                    <p class="font-medium text-gray-900">{{ $booking->contact_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Contact Phone</p>
                    <p class="font-medium text-gray-900">{{ $booking->contact_phone }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Contact Email</p>
                    <a href="mailto:{{ $booking->contact_email }}" class="font-medium text-green-700 hover:underline">{{ $booking->contact_email }}</a>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Registered User</p>
                    @if($booking->user)
                        <a href="{{ route('admin.users.show', $booking->user) }}" class="font-medium text-green-700 hover:underline">{{ $booking->user->name }}</a>
                    @else
                        <p class="text-gray-400">—</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">💰 Payment Summary</h3>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm text-gray-500">Total Amount</span>
                <span class="text-lg font-extrabold text-gray-900">{{ 'UGX ' . number_format($booking->total_amount) }}</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-gray-500">Status</span>
                <span class="text-sm font-semibold
                    {{ match($booking->status) {
                        'pending'   => 'text-amber-600',
                        'confirmed' => 'text-green-600',
                        'cancelled' => 'text-red-600',
                        'completed' => 'text-gray-600',
                        default     => 'text-gray-600',
                    }}">{{ ucfirst($booking->status) }}</span>
            </div>
        </div>

    </div>

    {{-- ── Right column: Actions + Timeline + Notes ────────────────── --}}
    <div class="space-y-6">

        {{-- Admin actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">⚙️ Actions</h3>
            <div class="space-y-2">
                @if($booking->status === 'pending')
                    <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2">
                            ✅ Confirm Booking
                        </button>
                    </form>
                @endif

                @if($booking->status === 'confirmed')
                    <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2">
                            🏁 Mark as Completed
                        </button>
                    </form>
                @endif

                @if(in_array($booking->status, ['pending', 'confirmed']))
                    <details class="group">
                        <summary class="cursor-pointer w-full px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2 list-none">
                            ❌ Cancel Booking
                        </summary>
                        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" class="mt-2 space-y-2">
                            @csrf
                            <textarea name="cancellation_reason" rows="2" placeholder="Reason for cancellation (optional)"
                                      class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-red-500 outline-none"></textarea>
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition">Confirm Cancellation</button>
                        </form>
                    </details>
                @endif

                @if($booking->status === 'cancelled')
                    <p class="text-sm text-gray-400 text-center py-2">This booking has been cancelled.</p>
                @endif
            </div>
        </div>

        {{-- Timeline --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">📅 Timeline</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="text-xs font-medium text-gray-900">Booking Created</p>
                        <p class="text-xs text-gray-400">{{ $booking->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
                @if($booking->confirmed_at)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-500 mt-1.5 shrink-0"></div>
                        <div>
                            <p class="text-xs font-medium text-gray-900">Confirmed</p>
                            <p class="text-xs text-gray-400">{{ $booking->confirmed_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                @endif
                @if($booking->cancelled_at)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-red-500 mt-1.5 shrink-0"></div>
                        <div>
                            <p class="text-xs font-medium text-gray-900">Cancelled</p>
                            <p class="text-xs text-gray-400">{{ $booking->cancelled_at->format('M d, Y \a\t h:i A') }}</p>
                            @if($booking->cancellation_reason)
                                <p class="text-xs text-gray-500 mt-0.5 italic">"{{ $booking->cancellation_reason }}"</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Admin notes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">📝 Admin Notes</h3>
            <form method="POST" action="{{ route('admin.bookings.notes', $booking) }}">
                @csrf
                @method('PUT')
                <textarea name="admin_notes" rows="4" placeholder="Add internal notes about this booking..."
                          class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                <button type="submit" class="w-full mt-2 px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition">Save Notes</button>
            </form>
        </div>

    </div>
</div>

@endsection
