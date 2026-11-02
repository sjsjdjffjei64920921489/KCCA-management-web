@extends('layouts.app')

@section('title', $venue->name)
@section('meta_description', Str::limit($venue->description, 160))

@section('content')

{{-- Breadcrumb --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-sm text-gray-500 flex items-center gap-2">
        <a href="{{ route('venues.index') }}" class="hover:text-green-700 transition">Venues</a>
        <span>›</span>
        <a href="{{ route('venues.index', ['location' => $venue->location]) }}" class="hover:text-green-700 transition">{{ $venue->location }}</a>
        <span>›</span>
        <span class="text-gray-900 font-medium truncate">{{ $venue->name }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ── LEFT: Venue info ──────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Hero image with gallery --}}
            @php $gallery = $venue->gallery_urls; @endphp

            <div class="rounded-2xl overflow-hidden shadow-lg relative bg-gray-100" style="height:380px;">
                {{-- Main image --}}
                @if($venue->hasImage())
                    <img id="main-photo"
                         src="{{ $venue->image_url }}"
                         alt="{{ $venue->name }}"
                         class="w-full h-full object-cover transition-opacity duration-300">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-green-700 via-green-600 to-teal-500 flex items-center justify-center">
                        <span class="text-8xl opacity-25">
                            @switch($venue->type)
                                @case('sports_venue') ⚽ @break
                                @case('community_hall') 🏛️ @break
                                @case('conference_center') 🏢 @break
                                @case('outdoor_space') 🌳 @break
                                @default 🏟️
                            @endswitch
                        </span>
                    </div>
                @endif

                {{-- Bottom overlay with title --}}
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/75 via-black/30 to-transparent p-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-500 text-white mb-2">{{ $venue->type_label }}</span>
                    <h1 class="text-white text-2xl sm:text-3xl font-extrabold leading-tight drop-shadow-lg">{{ $venue->name }}</h1>
                    <p class="text-white/80 text-sm mt-1">📍 {{ $venue->address }}</p>
                </div>

                {{-- Photo counter badge --}}
                @if(count($gallery) > 1)
                    <div class="absolute top-4 right-4 bg-black/60 text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                        📷 <span id="photo-counter">1</span> / {{ count($gallery) }}
                    </div>
                @endif

                {{-- Prev / Next arrows --}}
                @if(count($gallery) > 1)
                    <button onclick="changePhoto(-1)"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition text-lg">‹</button>
                    <button onclick="changePhoto(1)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition text-lg">›</button>
                @endif
            </div>

            {{-- Thumbnail strip --}}
            @if(count($gallery) > 1)
            <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                @foreach($gallery as $i => $url)
                    <button onclick="setPhoto({{ $i }})"
                            id="thumb-{{ $i }}"
                            class="shrink-0 w-20 h-14 rounded-lg overflow-hidden border-2 transition
                                   {{ $i === 0 ? 'border-green-600' : 'border-transparent opacity-60 hover:opacity-100' }}">
                        <img src="{{ $url }}" alt="Photo {{ $i+1 }}" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
            @endif

            {{-- Quick meta --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4 text-center">
                    <div class="text-2xl mb-1">📍</div>
                    <div class="text-xs text-gray-500 mb-0.5">Location</div>
                    <div class="font-bold text-gray-900 text-sm">{{ $venue->location }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4 text-center">
                    <div class="text-2xl mb-1">👥</div>
                    <div class="text-xs text-gray-500 mb-0.5">Capacity</div>
                    <div class="font-bold text-gray-900 text-sm">{{ number_format($venue->capacity) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4 text-center">
                    <div class="text-2xl mb-1">🕐</div>
                    <div class="text-xs text-gray-500 mb-0.5">Hours</div>
                    <div class="font-bold text-gray-900 text-sm">{{ substr($venue->open_time,0,5) }}–{{ substr($venue->close_time,0,5) }}</div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4 text-center">
                    <div class="text-2xl mb-1">💰</div>
                    <div class="text-xs text-gray-500 mb-0.5">Base Fee</div>
                    <div class="font-bold text-green-700 text-sm">{{ $venue->formatted_booking_fee }}</div>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h2 class="section-title mb-4">About This Venue</h2>
                <p class="text-gray-600 leading-relaxed">{{ $venue->description }}</p>
            </div>

            {{-- Facilities --}}
            @if($venue->facilities->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h2 class="section-title mb-5">Facilities & Amenities</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($venue->facilities as $facility)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <span class="text-2xl">{{ $facility->icon }}</span>
                            <span class="text-sm font-medium text-gray-700">{{ $facility->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Location --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h2 class="section-title mb-4">Location & Address</h2>
                <div class="flex items-start gap-3 mb-4">
                    <span class="text-2xl mt-0.5">📍</span>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $venue->address }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $venue->location }}, Kampala, Uganda</p>
                    </div>
                </div>
                {{-- Map placeholder --}}
                <div class="bg-gradient-to-br from-green-50 to-teal-50 border-2 border-dashed border-green-200 rounded-xl h-48 flex flex-col items-center justify-center text-gray-500">
                    <span class="text-4xl mb-2">🗺️</span>
                    <p class="font-semibold text-sm">{{ $venue->name }}</p>
                    <p class="text-xs">{{ $venue->address }}</p>
                    @if($venue->latitude && $venue->longitude)
                        <a href="https://maps.google.com/?q={{ $venue->latitude }},{{ $venue->longitude }}" target="_blank"
                           class="mt-3 text-xs text-green-700 font-semibold hover:underline">
                            Open in Google Maps →
                        </a>
                    @endif
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h2 class="section-title mb-5">Booking Fees</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                        <div class="text-xs text-green-700 font-semibold mb-1">Base Booking Fee</div>
                        <div class="text-xl font-extrabold text-green-800">{{ $venue->formatted_booking_fee }}</div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                        <div class="text-xs text-blue-700 font-semibold mb-1">Hourly Rate</div>
                        <div class="text-xl font-extrabold text-blue-800">{{ $venue->formatted_hourly_rate }}</div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-center">
                        <div class="text-xs text-purple-700 font-semibold mb-1">Full Day Rate</div>
                        <div class="text-xl font-extrabold text-purple-800">{{ $venue->formatted_full_day_rate }}</div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">All prices in Ugandan Shillings (UGX). Payment details provided upon booking confirmation.</p>
            </div>

            {{-- Suitable events --}}
            @if($venue->event_types)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h2 class="section-title mb-4">Suitable For</h2>
                <div class="flex flex-wrap gap-2">
                    @php
                        $eventLabels = [
                            'wedding' => ['🎊','Wedding'],
                            'kwanjula' => ['💍','Introduction (Kwanjula)'],
                            'party' => ['🎉','Party / Celebration'],
                            'sports' => ['⚽','Sports Event'],
                            'workshop' => ['📋','Workshop / Training'],
                            'community_meeting' => ['🤝','Community Meeting'],
                            'conference' => ['🏛️','Conference'],
                            'graduation' => ['🎓','Graduation'],
                            'outdoor_concert' => ['🎵','Outdoor Concert'],
                            'corporate_dinner' => ['🍽️','Corporate Dinner'],
                            'trade_fair' => ['🏪','Trade Fair'],
                            'athletics' => ['🏃','Athletics'],
                            'tournament' => ['🏆','Tournament'],
                            'fitness_event' => ['🏋️','Fitness Event'],
                            'cultural_event' => ['🎭','Cultural Event'],
                            'training' => ['📚','Training'],
                            'seminar' => ['💼','Seminar'],
                            'press_conference' => ['📰','Press Conference'],
                        ];
                    @endphp
                    @foreach($venue->event_types as $et)
                        @php $info = $eventLabels[$et] ?? ['🎪', ucfirst(str_replace('_',' ',$et))]; @endphp
                        <span class="flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1.5 rounded-full">
                            {{ $info[0] }} {{ $info[1] }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Contact --}}
            @if($venue->contact_phone || $venue->contact_email)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h2 class="section-title mb-4">Contact Information</h2>
                <div class="flex flex-col sm:flex-row gap-4">
                    @if($venue->contact_phone)
                        <a href="tel:{{ $venue->contact_phone }}" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-green-50 transition group">
                            <span class="text-2xl">📞</span>
                            <div>
                                <div class="text-xs text-gray-500">Phone</div>
                                <div class="font-semibold text-gray-900 group-hover:text-green-700 transition">{{ $venue->contact_phone }}</div>
                            </div>
                        </a>
                    @endif
                    @if($venue->contact_email)
                        <a href="mailto:{{ $venue->contact_email }}" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-green-50 transition group">
                            <span class="text-2xl">✉️</span>
                            <div>
                                <div class="text-xs text-gray-500">Email</div>
                                <div class="font-semibold text-gray-900 group-hover:text-green-700 transition">{{ $venue->contact_email }}</div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- ── RIGHT: Availability + Booking CTA ───────────────── --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Sticky booking card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 lg:sticky lg:top-24 space-y-5">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">Check Availability</h3>
                    <p class="text-xs text-gray-500">Pick a date and time to see if this venue is free.</p>
                </div>

                <form id="availability-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">📅 Date</label>
                        <input type="date" id="avail-date" name="date"
                               min="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                               required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">⏰ Start</label>
                            <input type="time" id="avail-start" name="start_time" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">⏰ End</label>
                            <input type="time" id="avail-end" name="end_time" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" required>
                        </div>
                    </div>

                    <button type="submit" id="check-btn" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm border-2 border-green-700 text-green-700 hover:bg-green-700 hover:text-white transition-all duration-200 w-full">
                        🔍 Check Availability
                    </button>
                </form>

                {{-- Availability result --}}
                <div id="avail-result" class="hidden"></div>

                {{-- Book Now --}}
                <div id="book-cta" class="hidden">
                    @auth
                        <a id="book-link" href="{{ route('venues.book', $venue->slug) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-green-700 hover:bg-green-800 text-white transition-all duration-200 w-full justify-center">
                            📋 Book This Venue
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ route('venues.book', $venue->slug) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-green-700 hover:bg-green-800 text-white transition-all duration-200 w-full justify-center">
                            🔐 Login to Book
                        </a>
                    @endauth
                </div>

                {{-- Direct book without availability check --}}
                <div class="border-t border-gray-100 pt-4">
                    @auth
                        <a href="{{ route('venues.book', $venue->slug) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-green-700 hover:bg-green-800 text-white transition-all duration-200 w-full justify-center">
                            📋 Book Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-green-700 hover:bg-green-800 text-white transition-all duration-200 w-full justify-center">
                            🔐 Login to Book
                        </a>
                        <p class="text-center text-xs text-gray-400 mt-2">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-green-700 font-semibold hover:underline">Register free</a>
                        </p>
                    @endauth
                </div>
            </div>

            {{-- Upcoming bookings notice --}}
            @if($upcomingBookings->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5">
                <h4 class="font-semibold text-gray-900 text-sm mb-3">⚠️ Already Booked Slots</h4>
                <div class="space-y-2">
                    @foreach($upcomingBookings->take(5) as $b)
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-gray-700">{{ $b->event_date->format('D, d M Y') }}</span>
                            <span class="text-gray-500">{{ substr($b->start_time,0,5) }} – {{ substr($b->end_time,0,5) }}</span>
                        </div>
                    @endforeach
                    @if($upcomingBookings->count() > 5)
                        <p class="text-xs text-gray-400">+{{ $upcomingBookings->count() - 5 }} more slots booked</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Photo gallery ─────────────────────────────────────────────────
const photos  = @json($venue->gallery_urls);
let current   = 0;

function setPhoto(index) {
    current = (index + photos.length) % photos.length;
    const img = document.getElementById('main-photo');
    if (img) {
        img.style.opacity = '0';
        setTimeout(() => {
            img.src = photos[current];
            img.style.opacity = '1';
        }, 150);
    }
    const counter = document.getElementById('photo-counter');
    if (counter) counter.textContent = current + 1;

    // Update thumbnail borders
    photos.forEach((_, i) => {
        const thumb = document.getElementById('thumb-' + i);
        if (thumb) {
            if (i === current) {
                thumb.className = thumb.className.replace('border-transparent opacity-60 hover:opacity-100','border-green-600');
            } else {
                thumb.className = thumb.className.replace('border-green-600','border-transparent opacity-60 hover:opacity-100');
            }
        }
    });
}

function changePhoto(dir) { setPhoto(current + dir); }

// Auto slideshow every 5 seconds if multiple photos
if (photos.length > 1) {
    setInterval(() => changePhoto(1), 5000);
}
</script>

<script>
// ── Availability checker ──────────────────────────────────────────
document.getElementById('availability-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn    = document.getElementById('check-btn');
    const result = document.getElementById('avail-result');
    const bookCta = document.getElementById('book-cta');
    const date   = document.getElementById('avail-date').value;
    const start  = document.getElementById('avail-start').value;
    const end    = document.getElementById('avail-end').value;

    btn.textContent = '⏳ Checking…';
    btn.disabled = true;
    result.className = '';
    result.innerHTML = '';

    try {
        const res = await fetch('{{ route("venues.check-availability", $venue->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ date, start_time: start, end_time: end }),
        });

        const data = await res.json();

        if (data.available) {
            result.className = 'rounded-xl bg-green-50 border border-green-200 p-4 text-sm';
            result.innerHTML = `
                <div class="font-semibold text-green-800 mb-1">✅ Available!</div>
                <div class="text-green-700 mb-2">${data.message}</div>
                <div class="flex justify-between text-xs text-green-700">
                    <span>Duration: <strong>${data.hours} hrs</strong></span>
                    <span>Est. Cost: <strong>${data.cost_fmt}</strong></span>
                </div>`;
            const bookLink = document.getElementById('book-link');
            if (bookLink) {
                bookLink.href = `{{ route('venues.book', $venue->slug) }}?date=${date}&start_time=${start}&end_time=${end}`;
            }
            bookCta.classList.remove('hidden');
        } else {
            result.className = 'rounded-xl bg-red-50 border border-red-200 p-4 text-sm';
            result.innerHTML = `<div class="font-semibold text-red-800 mb-1">❌ Not Available</div><div class="text-red-700">${data.message}</div>`;
            bookCta.classList.add('hidden');
        }
    } catch (err) {
        result.className = 'rounded-xl bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-800';
        result.innerHTML = '⚠️ Unable to check availability. Please try again.';
    }

    btn.textContent = '🔍 Check Availability';
    btn.disabled = false;
    result.classList.remove('hidden');
});
</script>
@endpush
