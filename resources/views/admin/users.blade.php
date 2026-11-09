@extends('admin.layout')
@section('title', 'Users')
@section('page-title', '👥 Users Management')
@section('page-subtitle', 'All registered users and their booking activity')

@section('content')

{{-- Filters --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Name, email or phone…"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
            <select name="role" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none">
                <option value="">All</option>
                <option value="user"  {{ request('role')==='user'  ? 'selected':'' }}>Users</option>
                <option value="admin" {{ request('role')==='admin' ? 'selected':'' }}>Admins</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-lg transition">🔍 Search</button>
        @if(request()->hasAny(['search','role']))
            <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg transition">✕ Clear</a>
        @endif
    </form>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-extrabold text-gray-900">{{ \App\Models\User::count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Accounts</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-extrabold text-green-700">{{ \App\Models\User::where('role','user')->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Regular Users</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-extrabold text-blue-700">{{ \App\Models\User::where('created_at','>=',now()->subDays(30))->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">New (30 days)</p>
    </div>
</div>

{{-- Users table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">All Users <span class="text-gray-400 font-normal text-sm">({{ $users->total() }})</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">User</th>
                    <th class="px-5 py-3">Contact</th>
                    <th class="px-5 py-3">Organisation</th>
                    <th class="px-5 py-3">Bookings</th>
                    <th class="px-5 py-3">Role</th>
                    <th class="px-5 py-3">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $u)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400">{{ $u->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ $u->phone ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $u->organization ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="font-bold text-gray-900">{{ $u->bookings_count }}</span>
                        <span class="text-xs text-gray-400">bookings</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                            {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $u->role === 'admin' ? '👑 Admin' : '👤 User' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">
                        {{ $u->created_at->format('d M Y') }}<br>
                        <span class="text-gray-400">{{ $u->created_at->diffForHumans() }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection
