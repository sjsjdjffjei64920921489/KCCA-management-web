@extends('layouts.admin')
@section('title', 'Add New Venue')
@section('breadcrumb', 'Add New Venue')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-gray-900">Add New Venue</h1>
        <p class="text-sm text-gray-500 mt-0.5">Create a new bookable venue.</p>
    </div>
    <a href="{{ route('admin.venues.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back to Venues</a>
</div>

<form method="POST" action="{{ route('admin.venues.store') }}">
    @csrf

    {{-- ── Basic info ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">📍 Basic Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Venue Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none bg-white">
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- ── Location ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">🗺️ Location</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Area / Location <span class="text-red-500">*</span></label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Lugogo, Kamwokya, Kabalagala" required
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">
                @error('location') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Address <span class="text-red-500">*</span></label>
                <input type="text" name="address" value="{{ old('address') }}" required
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">
                @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                <input type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                <input type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
            </div>
        </div>
    </div>

    {{-- ── Capacity & pricing ────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">💰 Capacity & Pricing (UGX)</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacity (max guests) <span class="text-red-500">*</span></label>
                <input type="number" name="capacity" value="{{ old('capacity') }}" min="1" required
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                @error('capacity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Base Booking Fee <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="booking_fee" value="{{ old('booking_fee') }}" min="0" required
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                @error('booking_fee') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate (optional)</label>
                <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate') }}" min="0"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Day Rate (optional)</label>
                <input type="number" step="0.01" name="full_day_rate" value="{{ old('full_day_rate') }}" min="0"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
            </div>
        </div>
    </div>

    {{-- ── Contact & scheduling ──────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">📞 Contact & Operating Hours</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                @error('contact_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Opening Time <span class="text-red-500">*</span></label>
                <input type="time" name="open_time" value="{{ old('open_time', '07:00') }}" required
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                @error('open_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Closing Time <span class="text-red-500">*</span></label>
                <input type="time" name="close_time" value="{{ old('close_time', '22:00') }}" required
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                @error('close_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Main Image Filename (in /public/images/venues/)</label>
                <input type="text" name="image" value="{{ old('image') }}" placeholder="e.g. lugogo-hall.jpg"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Upload images to <code>public/images/venues/</code> and enter the filename here.</p>
            </div>
        </div>
    </div>

    {{-- ── Event types ──────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">🎉 Suitable Event Types</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($eventTypes as $eventType)
                <label class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-green-50 transition text-sm {{ in_array($eventType, old('event_types', [])) ? 'bg-green-50 border-green-400' : '' }}">
                    <input type="checkbox" name="event_types[]" value="{{ $eventType }}" {{ in_array($eventType, old('event_types', [])) ? 'checked' : '' }}
                           class="rounded text-green-600 focus:ring-green-500">
                    <span class="capitalize">{{ str_replace('_', ' ', $eventType) }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- ── Facilities ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-700">Facilities / Amenities</h3>
            <button type="button" id="add-facility" class="text-xs font-medium text-green-600 hover:text-green-700">+ Add Facility</button>
        </div>
        <div id="facilities-container" class="space-y-2">
            @foreach(old('facilities', [['name' => '', 'icon' => '']]) as $i => $facility)
                <div class="flex gap-2 facility-row">
                    <input type="text" name="facilities[{{ $i }}][icon]" value="{{ $facility['icon'] ?? '' }}" maxlength="10" placeholder="🔧"
                           class="w-16 px-2 py-2 text-center text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                    <input type="text" name="facilities[{{ $i }}][name]" value="{{ $facility['name'] ?? '' }}" placeholder="Facility name (e.g. Parking, WiFi, Generator)"
                           class="flex-1 px-4 py-2 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
                    <button type="button" class="remove-facility p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── Settings ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">⚙️ Settings</h3>
        <div class="flex flex-col sm:flex-row gap-6">
            <label class="inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="rounded text-green-600 focus:ring-green-500 w-5 h-5">
                <div>
                    <p class="text-sm font-medium text-gray-700">Active</p>
                    <p class="text-xs text-gray-400">Show this venue on the public website</p>
                </div>
            </label>
            <label class="inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="requires_approval" value="1" {{ old('requires_approval') ? 'checked' : '' }}
                       class="rounded text-green-600 focus:ring-green-500 w-5 h-5">
                <div>
                    <p class="text-sm font-medium text-gray-700">Requires Approval</p>
                    <p class="text-xs text-gray-400">Bookings start as 'pending' instead of 'confirmed'</p>
                </div>
            </label>
        </div>
    </div>

    {{-- ── Submit ───────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.venues.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 rounded-lg transition">Cancel</a>
        <button type="submit" class="px-6 py-2.5 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-xl transition shadow-sm">Create Venue</button>
    </div>
</form>

@push('scripts')
<script>
let facilityIndex = document.querySelectorAll('.facility-row').length;
document.getElementById('add-facility').addEventListener('click', function() {
    const container = document.getElementById('facilities-container');
    const row = document.createElement('div');
    row.className = 'flex gap-2 facility-row';
    row.innerHTML = `
        <input type="text" name="facilities[${facilityIndex}][icon]" value="" maxlength="10" placeholder="🔧"
               class="w-16 px-2 py-2 text-center text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
        <input type="text" name="facilities[${facilityIndex}][name]" value="" placeholder="Facility name (e.g. Parking, WiFi, Generator)"
               class="flex-1 px-4 py-2 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none">
        <button type="button" class="remove-facility p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Remove">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    container.appendChild(row);
    facilityIndex++;
});
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-facility')) {
        e.target.closest('.facility-row').remove();
    }
});
</script>
@endpush

@endsection
