@extends('admin.layout')
@section('title', 'Bookings')
@section('page-title', '📋 Bookings Management')
@section('page-subtitle', 'View, confirm and manage all venue bookings')

@section('content')

{{-- Stats strip --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    @foreach([['Pending','pending','yellow'],['Confirmed','confirmed','green'],['Cancelled','cancelled','red'],['Total','','blue']] as [$label,$status,$color])
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-extrabold text-{{ $color }}-600">
            {{ $status ? \App\Models\Booking::where('status',$status)->count() : \App\Models\Booking::count() }}
        </p>
        <p class="text-xs text-gray-500 mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Reference, name, email, phone…"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                <option value="">All</option>
                @foreach(['pending'=>'Pending','confirmed'=>'Confirmed','cancelled'=>'Cancelled','completed'=>'Completed'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('status')===$v ? 'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Venue</label>
            <select name="venue_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                <option value="">All Venues</option>
                @foreach($venues as $v)
                    <option value="{{ $v->id }}" {{ request('venue_id')==$v->id ? 'selected':'' }}>{{ $v->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-lg transition">🔍 Filter</button>
        @if(request()->hasAny(['search','status','venue_id']))
            <a href="{{ route('admin.bookings') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg transition">✕ Clear</a>
        @endif
    </form>
</div>

{{-- Bookings table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-900">All Bookings <span class="text-gray-400 font-normal text-sm">({{ $bookings->total() }})</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">Reference</th>
                    <th class="px-5 py-3">Venue</th>
                    <th class="px-5 py-3">User</th>
                    <th class="px-5 py-3">Event</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3">Amount</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $b)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $b->reference_number }}</td>
                    <td class="px-5 py-3">
                        <p class="font-semibold text-gray-900 truncate max-w-32">{{ $b->venue->name }}</p>
                        <p class="text-xs text-gray-400">{{ $b->venue->location }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900">{{ $b->contact_name }}</p>
                        <p class="text-xs text-gray-400">{{ $b->contact_phone }}</p>
                    </td>
                    <td class="px-5 py-3 capitalize text-gray-700">{{ str_replace('_',' ',$b->event_type) }}</td>
                    <td class="px-5 py-3 text-gray-700 whitespace-nowrap">
                        {{ $b->event_date->format('d M Y') }}<br>
                        <span class="text-xs text-gray-400">{{ substr($b->start_time,0,5) }}–{{ substr($b->end_time,0,5) }}</span>
                    </td>
                    <td class="px-5 py-3 font-bold text-green-700 whitespace-nowrap">UGX {{ number_format($b->total_amount) }}</td>
                    <td class="px-5 py-3">
                        <span class="
                            @if($b->status==='confirmed') inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800
                            @elseif($b->status==='pending') inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800
                            @elseif($b->status==='cancelled') inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800
                            @else inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            @if($b->status === 'pending')
                            <form method="POST" action="{{ route('admin.bookings.confirm', $b->reference_number) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition">✅ Confirm</button>
                            </form>
                            @endif
                            @if(in_array($b->status, ['pending','confirmed']))
                            <form method="POST" action="{{ route('admin.bookings.cancel', $b->reference_number) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition"
                                        onclick="return confirm('Cancel this booking?')">❌ Cancel</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2">📭</div>
                        No bookings found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection
