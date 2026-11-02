@extends('layouts.app')

@section('title', 'Browse Venues')
@section('meta_description', 'Find and book community halls, sports venues and public spaces in Lugogo, Kamwokya and Kabalagala, Kampala.')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-green-800 via-green-700 to-green-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4">
                🏙️ Kampala Capital City Authority
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight mb-4">
                Find the Perfect Venue<br>in Kampala
            </h1>
            <p class="text-green-100 text-lg mb-8">
                Browse and book community halls, sports venues and public spaces across
                <strong class="text-white">Lugogo</strong>,
                <strong class="text-white">Kamwokya</strong> and
                <strong class="text-white">Kabalagala</strong>.
            </p>

            {{-- Quick search --}}
            <form method="GET" action="{{ route('venues.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search venues by name or area…"
                       class="flex-1 px-5 py-3 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-300">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-6 py-3 rounded-xl text-sm transition">
                    🔍 Search
                </button>
            </form>
        </div>
    </div>
</section>

{{-- Stats bar --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-wrap gap-6 text-sm text-gray-600">
            <span class="flex items-center gap-1.5"><span class="font-bold text-green-700">{{ \App\Models\Venue::active()->count() }}</span> venues available</span>
            <span class="text-gray-300">|</span>
            <span class="flex items-center gap-1.5">📍 <a href="{{ route('venues.index', ['location' => 'Lugogo']) }}" class="hover:text-green-700 transition">Lugogo</a></span>
            <span class="flex items-center gap-1.5">📍 <a href="{{ route('venues.index', ['location' => 'Kamwokya']) }}" class="hover:text-green-700 transition">Kamwokya</a></span>
            <span class="flex items-center gap-1.5">📍 <a href="{{ route('venues.index', ['location' => 'Kabalagala']) }}" class="hover:text-green-700 transition">Kabalagala</a></span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ── Sidebar Filters ─────────────────────────────────────── --}}
        <aside class="lg:w-64 shrink-0">
            <form method="GET" action="{{ route('venues.index') }}" id="filter-form">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-gray-900">Filters</h3>
                        @if(request()->hasAny(['location','type','capacity','event_type','search']))
                            <a href="{{ route('venues.index') }}" class="text-xs text-red-500 hover:text-red-700">Clear all</a>
                        @endif
                    </div>

                    {{-- Search (hidden on sidebar – preserved) --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    {{-- Location --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">📍 Location</label>
                        <select name="location" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" onchange="this.form.submit()">
                            <option value="">All Areas</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">🏛️ Venue Type</label>
                        <select name="type" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Event type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">🎉 Event Type</label>
                        <select name="event_type" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" onchange="this.form.submit()">
                            <option value="">Any Event</option>
                            @foreach($eventTypes as $key => $label)
                                <option value="{{ $key }}" {{ request('event_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Capacity --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">👥 Min Capacity</label>
                        <select name="capacity" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition" onchange="this.form.submit()">
                            <option value="">Any size</option>
                            <option value="50"   {{ request('capacity') == 50   ? 'selected' : '' }}>50+</option>
                            <option value="100"  {{ request('capacity') == 100  ? 'selected' : '' }}>100+</option>
                            <option value="200"  {{ request('capacity') == 200  ? 'selected' : '' }}>200+</option>
                            <option value="500"  {{ request('capacity') == 500  ? 'selected' : '' }}>500+</option>
                            <option value="1000" {{ request('capacity') == 1000 ? 'selected' : '' }}>1,000+</option>
                            <option value="3000" {{ request('capacity') == 3000 ? 'selected' : '' }}>3,000+</option>
                        </select>
                    </div>
                </div>
            </form>
        </aside>

        {{-- ── Venue Grid ──────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0">

            {{-- Results header --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-600">
                    Showing <strong>{{ $venues->firstItem() }}–{{ $venues->lastItem() }}</strong> of
                    <strong>{{ $venues->total() }}</strong> venue{{ $venues->total() != 1 ? 's' : '' }}
                    @if(request('location')) in <strong>{{ request('location') }}</strong> @endif
                    @if(request('search')) matching "<strong>{{ request('search') }}</strong>" @endif
                </p>
            </div>

            @if($venues->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-16 text-center">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No venues found</h3>
                    <p class="text-gray-500 text-sm mb-6">Try adjusting your filters or search term.</p>
                    <a href="{{ route('venues.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-green-700 hover:bg-green-800 text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">View All Venues</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($venues as $venue)
                        @include('partials.venue-card', ['venue' => $venue])
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($venues->hasPages())
                    <div class="mt-10 flex justify-center">
                        {{ $venues->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
