@extends('layouts.admin')
@section('title', 'Users')
@section('breadcrumb', 'Manage Users')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-extrabold text-gray-900">Users</h1>
        <p class="text-sm text-gray-500 mt-0.5">View registered users and their booking history.</p>
    </div>
</div>

{{-- ── Filters ──────────────────────────────────────────────────────── --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name, email, organization..."
                   class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">
        </div>
        <div>
            <select name="role" class="w-full sm:w-auto px-4 py-2.5 text-sm rounded-lg border border-gray-300 focus:border-green-500 outline-none bg-white">
                <option value="">All Roles</option>
                <option value="user" @selected(request('role') === 'user')>Users</option>
                <option value="admin" @selected(request('role') === 'admin')>Admins</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition shrink-0">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 font-medium shrink-0">Clear</a>
    </div>
</form>

{{-- ── Users table ─────────────────────────────────────────────────── }}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 border-b border-gray-100 bg-gray-50">
                    <th class="px-6 py-3 font-semibold">User</th>
                    <th class="px-4 py-3 font-semibold">Contact</th>
                    <th class="px-4 py-3 font-semibold">Organization</th>
                    <th class="px-4 py-3 font-semibold text-center">Role</th>
                    <th class="px-4 py-3 font-semibold text-center">Bookings</th>
                    <th class="px-4 py-3 font-semibold">Joined</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $user) }}" class="font-semibold text-gray-900 hover:text-green-700">{{ $user->name }}</a>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $user->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $user->organization ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Admin</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">User</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">{{ $user->bookings_count }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($users->hasPages())
    <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
@endif

@endsection
