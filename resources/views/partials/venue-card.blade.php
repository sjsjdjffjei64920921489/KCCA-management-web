<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group flex flex-col hover:shadow-lg transition-shadow duration-200">
    {{-- Image --}}
    <div class="relative h-48 overflow-hidden bg-gray-100">
        @if($venue->hasImage())
            <img src="{{ $venue->image_url }}"
                 alt="{{ $venue->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-green-700 to-green-500">
                <span class="text-6xl opacity-30">
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

        {{-- Overlaid badges --}}
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/90 text-green-800 shadow">
                {{ $venue->type_label }}
            </span>
        </div>
        <div class="absolute top-3 right-3">
            <span class="bg-white/90 text-gray-800 text-xs font-bold px-2 py-1 rounded-lg shadow">
                📍 {{ $venue->location }}
            </span>
        </div>

        {{-- Gradient overlay for text legibility --}}
        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/40 to-transparent"></div>
    </div>

    {{-- Body --}}
    <div class="p-5 flex flex-col flex-1">
        <h3 class="font-bold text-gray-900 text-base mb-1 group-hover:text-green-700 transition">
            {{ $venue->name }}
        </h3>
        <p class="text-gray-500 text-xs mb-3">{{ $venue->address }}</p>
        <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 mb-4 flex-1">
            {{ $venue->description }}
        </p>

        {{-- Meta --}}
        <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
            <span class="flex items-center gap-1">👥 Up to {{ number_format($venue->capacity) }} guests</span>
            <span class="flex items-center gap-1">🕐 {{ substr($venue->open_time, 0, 5) }}–{{ substr($venue->close_time, 0, 5) }}</span>
        </div>

        {{-- Facilities preview --}}
        @if($venue->facilities->isNotEmpty())
            <div class="flex flex-wrap gap-1 mb-4">
                @foreach($venue->facilities->take(4) as $f)
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $f->icon }} {{ $f->name }}</span>
                @endforeach
                @if($venue->facilities->count() > 4)
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">+{{ $venue->facilities->count() - 4 }} more</span>
                @endif
            </div>
        @endif

        {{-- Pricing & CTA --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div>
                <div class="text-xs text-gray-400">From</div>
                <div class="font-bold text-green-700 text-sm">{{ $venue->formatted_booking_fee }}</div>
            </div>
            <a href="{{ route('venues.show', $venue->slug) }}"
               class="inline-flex items-center gap-1 px-4 py-2 rounded-lg font-semibold text-xs bg-green-700 hover:bg-green-800 text-white transition">
                View Details →
            </a>
        </div>
    </div>
</div>
