@extends('layouts.admin')
@section('title', 'Venues')
@section('breadcrumb', 'Manage Venues')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-gray-900">Venues</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manage all community halls, sports venues and public spaces.</p>
    </div>
    <a href="{{ route('admin.venues.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-xl transition shadow-sm shrink-0">
        + Add New Venue
    </a>
</div>

{{-- ── Filters ──────────────────────────────────────────────────────── --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name, location, address..."
                   class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">
        </div>
        <div>
            <select name="type" class="w-full sm:w-auto px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none bg-white">
                <option value="">All Types</option>
                @foreach($types as $value => $label)
                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="w-full sm:w-auto px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none bg-white">
                <option value="">All Statuses</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition shrink-0">Filter</button>
        <a href="{{ route('admin.venues.index') }}" class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 font-medium shrink-0">Clear</a>
    </div>
</form>

{{-- ── Venues table ─────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 border-b border-gray-100 bg-gray-50">
                    <th class="px-6 py-3 font-semibold">Venue</th>
                    <th class="px-4 py-3 font-semibold">Type</th>
                    <th class="px-4 py-3 font-semibold">Location</th>
                    <th class="px-4 py-3 font-semibold text-center">Capacity</th>
                    <th class="px-4 py-3 font-semibold text-right">Booking Fee</th>
                    <th class="px-4 py-3 font-semibold text-center">Bookings</th>
                    <th class="px-4 py-3 font-semibold text-center">Status</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($venues as $venue)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-lg shrink-0">
                                    @if($venue->hasImage())
                                        <img src="{{ $venue->image_url }}" alt="{{ $venue->name }}" class="w-full h-full rounded-lg object-cover">
                                    @else
                                        🏟️
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.venues.edit', $venue) }}" class="font-semibold text-gray-900 hover:text-green-700 truncate block">{{ $venue->name }}</a>
                                    <p class="text-xs text-gray-400 truncate">{{ $venue->address }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $venue->type_label }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $venue->location }}</td>
                        <td class="px-4 py-3 text-gray-600 text-center">{{ number_format($venue->capacity) }}</td>
                        <td class="px-4 py-3 text-gray-600 text-right font-medium">{{ 'UGX ' . number_format($venue->booking_fee) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">{{ $venue->bookings_count }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($venue->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">● Active</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">● Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('venues.show', $venue->slug) }}" target="_blank"
                                   class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View on site">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.venues.edit', $venue) }}"
                                   class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.venues.toggle', $venue) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="{{ $venue->is_active ? 'Deactivate' : 'Activate' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.venues.destroy', $venue) }}" onsubmit="return confirm('Are you sure you want to delete {{ $venue->name }}? This will also delete all related bookings and facilities.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No venues found. <a href="{{ route('admin.venues.create') }}" class="text-green-600 hover:underline">Create one →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($venues->hasPages())
    <div class="mt-4">{{ $venues->withQueryString()->links() }}</div>
@endif

@endsection
