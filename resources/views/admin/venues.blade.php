@extends('admin.layout')
@section('title', 'Venues')
@section('page-title', '🏛️ Venues Management')
@section('page-subtitle', 'All venues in the system with booking counts')

@section('content')

{{-- Filters --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Location</label>
            <select name="location" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none" onchange="this.form.submit()">
                <option value="">All Areas</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc }}" {{ request('location')===$loc ? 'selected':'' }}>{{ $loc }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
            <select name="type" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none" onchange="this.form.submit()">
                <option value="">All Types</option>
                @foreach(['community_hall'=>'Community Hall','sports_venue'=>'Sports Venue','conference_center'=>'Conference Center','outdoor_space'=>'Outdoor Space'] as $k=>$v)
                    <option value="{{ $k }}" {{ request('type')===$k ? 'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        @if(request()->hasAny(['location','type']))
            <a href="{{ route('admin.venues') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg transition">✕ Clear</a>
        @endif
    </form>
</div>

{{-- Venues table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">Venues <span class="text-gray-400 font-normal text-sm">({{ $venues->total() }})</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">Venue</th>
                    <th class="px-5 py-3">Location</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Capacity</th>
                    <th class="px-5 py-3">Base Fee</th>
                    <th class="px-5 py-3">Bookings</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($venues as $v)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            @if($v->hasImage())
                                <img src="{{ $v->image_url }}" class="w-10 h-10 rounded-lg object-cover shrink-0" alt="">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-xl shrink-0">
                                    @switch($v->type) @case('sports_venue') ⚽ @break @case('community_hall') 🏛️ @break @case('conference_center') 🏢 @break @default 🌳 @endswitch
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $v->name }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-40">{{ $v->address }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-700">{{ $v->location }}</td>
                    <td class="px-5 py-3 text-gray-700 capitalize">{{ str_replace('_',' ',$v->type) }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ number_format($v->capacity) }}</td>
                    <td class="px-5 py-3 font-semibold text-green-700">UGX {{ number_format($v->booking_fee) }}</td>
                    <td class="px-5 py-3">
                        <span class="font-bold text-gray-900">{{ $v->bookings_count }}</span>
                        <span class="text-gray-400 text-xs">bookings</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                            {{ $v->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' }}">
                            {{ $v->is_active ? '✅ Active' : '❌ Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('venues.show', $v->slug) }}" target="_blank"
                               class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition">👁️ View</a>
                            <form method="POST" action="{{ route('admin.venues.toggle', $v->id) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1 text-xs font-semibold rounded-lg transition
                                               {{ $v->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }}">
                                    {{ $v->is_active ? '🔴 Deactivate' : '🟢 Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">No venues found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($venues->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $venues->links() }}</div>
    @endif
</div>
@endsection
