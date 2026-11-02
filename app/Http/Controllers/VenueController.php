<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::active()->with('facilities');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('location')) {
            $query->inLocation($request->location);
        }

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', (int) $request->capacity);
        }

        if ($request->filled('event_type')) {
            $query->whereJsonContains('event_types', $request->event_type);
        }

        $venues = $query->orderBy('name')->paginate(9)->withQueryString();

        $locations  = Venue::active()->distinct()->pluck('location')->sort()->values();
        $types      = [
            'community_hall'    => 'Community Hall',
            'sports_venue'      => 'Sports Venue',
            'public_venue'      => 'Public Venue',
            'conference_center' => 'Conference Center',
            'outdoor_space'     => 'Outdoor Space',
        ];
        $eventTypes = [
            'wedding'           => 'Wedding',
            'kwanjula'          => 'Introduction (Kwanjula)',
            'party'             => 'Party / Celebration',
            'sports'            => 'Sports Event',
            'workshop'          => 'Workshop / Training',
            'community_meeting' => 'Community Meeting',
            'conference'        => 'Conference / Seminar',
            'graduation'        => 'Graduation',
            'outdoor_concert'   => 'Outdoor Concert',
            'corporate_dinner'  => 'Corporate Dinner',
            'trade_fair'        => 'Trade Fair / Exhibition',
        ];

        return view('venues.index', compact('venues', 'locations', 'types', 'eventTypes'));
    }

    public function show(string $slug)
    {
        $venue = Venue::active()
            ->with('facilities')
            ->where('slug', $slug)
            ->firstOrFail();

        // Upcoming confirmed/pending bookings (for availability display)
        $upcomingBookings = $venue->bookings()
            ->active()
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->get(['event_date', 'start_time', 'end_time']);

        return view('venues.show', compact('venue', 'upcomingBookings'));
    }

    public function checkAvailability(Request $request, string $slug)
    {
        $request->validate([
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $venue     = Venue::active()->where('slug', $slug)->firstOrFail();
        $available = $venue->isAvailableOn(
            $request->date,
            $request->start_time . ':00',
            $request->end_time . ':00'
        );

        // Calculate estimated cost
        $start  = \Carbon\Carbon::parse($request->start_time);
        $end    = \Carbon\Carbon::parse($request->end_time);
        $hours  = round($end->diffInMinutes($start) / 60, 1);
        $cost   = 0;

        if ($venue->hourly_rate) {
            $cost = $venue->hourly_rate * $hours;
        } elseif ($hours >= 8 && $venue->full_day_rate) {
            $cost = $venue->full_day_rate;
        } else {
            $cost = $venue->booking_fee;
        }

        return response()->json([
            'available' => $available,
            'hours'     => $hours,
            'cost'      => $cost,
            'cost_fmt'  => 'UGX ' . number_format($cost),
            'message'   => $available
                ? 'Great! This venue is available for your selected date and time.'
                : 'Sorry, this venue is already booked for the selected date and time. Please choose a different slot.',
        ]);
    }
}
