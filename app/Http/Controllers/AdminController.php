<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ── Dashboard overview ────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_venues'    => Venue::count(),
            'active_venues'   => Venue::active()->count(),
            'total_bookings'  => Booking::count(),
            'pending'         => Booking::where('status', 'pending')->count(),
            'confirmed'       => Booking::where('status', 'confirmed')->count(),
            'cancelled'       => Booking::where('status', 'cancelled')->count(),
            'total_users'     => User::where('role', 'user')->count(),
            'new_users_week'  => User::where('role', 'user')->where('created_at', '>=', now()->subWeek())->count(),
            'revenue'         => Booking::whereIn('status', ['confirmed','completed'])->sum('total_amount'),
            'upcoming'        => Booking::whereIn('status', ['pending','confirmed'])->where('event_date', '>=', now()->toDateString())->count(),
        ];

        $recentBookings = Booking::with(['venue', 'user'])
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $recentUsers = User::where('role', 'user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Bookings per location for chart
        $byLocation = Booking::join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->selectRaw('venues.location, count(*) as total')
            ->groupBy('venues.location')
            ->pluck('total', 'location');

        // Bookings by event type
        $byEventType = Booking::selectRaw('event_type, count(*) as total')
            ->groupBy('event_type')
            ->orderByDesc('total')
            ->take(6)
            ->pluck('total', 'event_type');

        // Monthly bookings (last 6 months)
        $monthlyBookings = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyBookings[] = [
                'month' => $month->format('M Y'),
                'count' => Booking::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'recentUsers', 'byLocation', 'byEventType', 'monthlyBookings'
        ));
    }

    // ── Bookings management ───────────────────────────────────────
    public function bookings(Request $request)
    {
        $query = Booking::with(['venue', 'user'])->orderByDesc('created_at');

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('venue_id')) $query->where('venue_id', $request->venue_id);
        if ($request->filled('search'))   $query->where(function ($q) use ($request) {
            $q->where('reference_number', 'like', "%{$request->search}%")
              ->orWhere('contact_name', 'like', "%{$request->search}%")
              ->orWhere('contact_email', 'like', "%{$request->search}%")
              ->orWhere('contact_phone', 'like', "%{$request->search}%");
        });

        $bookings = $query->paginate(15)->withQueryString();
        $venues   = Venue::orderBy('name')->get(['id', 'name']);

        return view('admin.bookings', compact('bookings', 'venues'));
    }

    public function confirmBooking(string $reference)
    {
        $booking = Booking::where('reference_number', $reference)->firstOrFail();
        $booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        return back()->with('success', "Booking {$reference} confirmed.");
    }

    public function cancelBooking(Request $request, string $reference)
    {
        $booking = Booking::where('reference_number', $reference)->firstOrFail();
        $booking->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $request->reason ?? 'Cancelled by admin',
        ]);
        return back()->with('success', "Booking {$reference} cancelled.");
    }

    // ── Venues management ─────────────────────────────────────────
    public function venues(Request $request)
    {
        $query = Venue::withCount('bookings')->orderBy('name');
        if ($request->filled('location')) $query->where('location', $request->location);
        if ($request->filled('type'))     $query->where('type', $request->type);

        $venues    = $query->paginate(10)->withQueryString();
        $locations = Venue::distinct()->pluck('location');

        return view('admin.venues', compact('venues', 'locations'));
    }

    public function toggleVenue(int $id)
    {
        $venue = Venue::findOrFail($id);
        $venue->update(['is_active' => !$venue->is_active]);
        $status = $venue->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "{$venue->name} has been {$status}.");
    }

    // ── Users management ──────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::withCount('bookings')->orderByDesc('created_at');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('role')) $query->where('role', $request->role);

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users', compact('users'));
    }
}
