@extends('layouts.app')

@section('title', 'Book ' . $venue->name)

@section('content')

{{-- Breadcrumb --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-sm text-gray-500 flex items-center gap-2">
        <a href="{{ route('venues.index') }}" class="hover:text-green-700">Venues</a>
        <span>›</span>
        <a href="{{ route('venues.show', $venue->slug) }}" class="hover:text-green-700">{{ $venue->name }}</a>
        <span>›</span>
        <span class="text-gray-900 font-medium">Book</span>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── Form ──────────────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 sm:p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Submit a Booking Request</h1>
                <p class="text-sm text-gray-500 mb-8">Fill in the details below to request a booking for <strong>{{ $venue->name }}</strong>.</p>

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="alert-error mb-6">
                        <span>❌</span>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('venues.book.store', $venue->slug) }}" id="booking-form">
                    @csrf

                    {{-- Section 1: Event Details --}}
                    <div class="mb-8">
                        <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="w-6 h-6 bg-green-700 text-white text-xs rounded-full flex items-center justify-center font-bold">1</span>
                            Event Details
                        </h2>
                        <div class="space-y-4">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="event_type">Event Type <span class="text-red-500">*</span></label>
                                <select name="event_type" id="event_type" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" required>
                                    <option value="">Select event type…</option>
                                    @foreach($eventTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('event_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('event_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="event_date">Event Date <span class="text-red-500">*</span></label>
                                <input type="date" name="event_date" id="event_date"
                                       value="{{ old('event_date', $prefill['date'] ?? '') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" required>
                                @error('event_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="start_time">Start Time <span class="text-red-500">*</span></label>
                                    <input type="time" name="start_time" id="start_time"
                                           value="{{ old('start_time', $prefill['start_time'] ?? '') }}"
                                           min="{{ substr($venue->open_time, 0, 5) }}"
                                           max="{{ substr($venue->close_time, 0, 5) }}"
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" required>
                                    @error('start_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="end_time">End Time <span class="text-red-500">*</span></label>
                                    <input type="time" name="end_time" id="end_time"
                                           value="{{ old('end_time', $prefill['end_time'] ?? '') }}"
                                           max="{{ substr($venue->close_time, 0, 5) }}"
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" required>
                                    @error('end_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <p class="text-xs text-gray-400">Venue operating hours: {{ substr($venue->open_time,0,5) }} – {{ substr($venue->close_time,0,5) }}</p>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="expected_guests">
                                    Expected Number of Guests <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal">(max {{ number_format($venue->capacity) }})</span>
                                </label>
                                <input type="number" name="expected_guests" id="expected_guests"
                                       value="{{ old('expected_guests') }}"
                                       min="1" max="{{ $venue->capacity }}"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" placeholder="e.g. 150" required>
                                @error('expected_guests') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="special_requirements">Special Requirements <span class="text-gray-400 font-normal">(optional)</span></label>
                                <textarea name="special_requirements" id="special_requirements" rows="3"
                                          class="form-input resize-none"
                                          placeholder="Any special setup, accessibility needs, equipment requests…">{{ old('special_requirements') }}</textarea>
                                @error('special_requirements') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Contact Details --}}
                    <div class="mb-8">
                        <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="w-6 h-6 bg-green-700 text-white text-xs rounded-full flex items-center justify-center font-bold">2</span>
                            Contact Details
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="contact_name">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_name" id="contact_name"
                                       value="{{ old('contact_name', Auth::user()->name) }}"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" placeholder="Your full name" required>
                                @error('contact_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="contact_phone">Phone Number <span class="text-red-500">*</span></label>
                                    <input type="tel" name="contact_phone" id="contact_phone"
                                           value="{{ old('contact_phone', Auth::user()->phone) }}"
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" placeholder="+256 700 000 000" required>
                                    @error('contact_phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="contact_email">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" name="contact_email" id="contact_email"
                                           value="{{ old('contact_email', Auth::user()->email) }}"
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" placeholder="you@example.com" required>
                                    @error('contact_email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="organization">Organisation / Company <span class="text-gray-400 font-normal">(optional)</span></label>
                                <input type="text" name="organization" id="organization"
                                       value="{{ old('organization', Auth::user()->organization) }}"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" placeholder="Name of your organisation">
                                @error('organization') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Cost summary --}}
                    <div id="cost-summary" class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 hidden">
                        <h4 class="font-semibold text-green-800 text-sm mb-2">💰 Estimated Cost</h4>
                        <div id="cost-breakdown" class="text-sm text-green-700 space-y-1"></div>
                    </div>

                    {{-- Terms notice --}}
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <p class="text-xs text-yellow-800">
                            <strong>⚠️ Please note:</strong> Submitting this form sends a booking request.
                            {{ $venue->requires_approval ? 'Your booking requires approval and you will be notified once confirmed.' : 'Your booking will be confirmed immediately upon submission.' }}
                            Payment arrangements will be communicated via the contact details provided.
                        </p>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3 text-base">
                        📋 Submit Booking Request
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Summary sidebar ───────────────────────────────────── --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 lg:sticky lg:top-24 space-y-4">
                <h3 class="font-bold text-gray-900">Booking Summary</h3>

                <div class="bg-gradient-to-br from-green-700 to-green-500 rounded-xl p-4 text-white">
                    <div class="text-xs text-green-200 mb-1">{{ $venue->type_label }}</div>
                    <div class="font-bold text-base leading-tight">{{ $venue->name }}</div>
                    <div class="text-xs text-green-200 mt-1">📍 {{ $venue->address }}</div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Capacity</span>
                        <span class="font-medium">Up to {{ number_format($venue->capacity) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Base Fee</span>
                        <span class="font-medium text-green-700">{{ $venue->formatted_booking_fee }}</span>
                    </div>
                    @if($venue->hourly_rate)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Hourly Rate</span>
                        <span class="font-medium">{{ $venue->formatted_hourly_rate }}</span>
                    </div>
                    @endif
                    @if($venue->full_day_rate)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Full Day</span>
                        <span class="font-medium">{{ $venue->formatted_full_day_rate }}</span>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-3">
                    <a href="{{ route('venues.show', $venue->slug) }}" class="text-xs text-green-700 font-semibold hover:underline">← Back to venue details</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Live cost estimation
function estimateCost() {
    const start = document.getElementById('start_time').value;
    const end   = document.getElementById('end_time').value;
    if (!start || !end) return;

    const [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    const hours = ((eh * 60 + em) - (sh * 60 + sm)) / 60;
    if (hours <= 0) return;

    const hourlyRate = {{ $venue->hourly_rate ?? 'null' }};
    const fullDayRate = {{ $venue->full_day_rate ?? 'null' }};
    const baseRate = {{ $venue->booking_fee }};

    let cost, basis;
    if (hourlyRate) {
        cost  = hourlyRate * hours;
        basis = `${hours} hrs × UGX ${hourlyRate.toLocaleString()}/hr`;
    } else if (hours >= 8 && fullDayRate) {
        cost  = fullDayRate;
        basis = 'Full day rate applied';
    } else {
        cost  = baseRate;
        basis = 'Base booking fee';
    }

    const summary = document.getElementById('cost-summary');
    const breakdown = document.getElementById('cost-breakdown');
    breakdown.innerHTML = `
        <div class="flex justify-between"><span>Duration:</span><span><strong>${hours.toFixed(1)} hours</strong></span></div>
        <div class="flex justify-between"><span>Basis:</span><span>${basis}</span></div>
        <div class="flex justify-between border-t border-green-200 pt-1 mt-1"><span class="font-bold">Total:</span><span class="font-bold">UGX ${Math.round(cost).toLocaleString()}</span></div>
    `;
    summary.classList.remove('hidden');
}

document.getElementById('start_time').addEventListener('change', estimateCost);
document.getElementById('end_time').addEventListener('change', estimateCost);
</script>
@endpush
