<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Show the booking form for a venue */
    public function create(Request $request, string $slug)
    {
        $venue = Venue::active()->where('slug', $slug)->firstOrFail();

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

        // Pre-fill from availability check if passed via query string
        $prefill = [
            'date'       => $request->query('date'),
            'start_time' => $request->query('start_time'),
            'end_time'   => $request->query('end_time'),
        ];

        return view('bookings.create', compact('venue', 'eventTypes', 'prefill'));
    }

    /** Store a new booking */
    public function store(Request $request, string $slug)
    {
        $venue = Venue::active()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'event_type'          => 'required|string|max:50',
            'event_date'          => 'required|date|after_or_equal:today',
            'start_time'          => 'required|date_format:H:i',
            'end_time'            => 'required|date_format:H:i|after:start_time',
            'expected_guests'     => 'required|integer|min:1|max:' . $venue->capacity,
            'contact_name'        => 'required|string|max:150',
            'contact_phone'       => 'required|string|max:30',
            'contact_email'       => 'required|email|max:150',
            'organization'        => 'nullable|string|max:150',
            'special_requirements' => 'nullable|string|max:1000',
        ]);

        // Double-check availability before confirming
        $conflict = Booking::where('venue_id', $venue->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('event_date', $validated['event_date'])
            ->where(function ($q) use ($validated) {
                $q->where('start_time', '<', $validated['end_time'] . ':00')
                  ->where('end_time', '>', $validated['start_time'] . ':00');
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['event_date' => 'This venue is already booked for the selected date and time. Please choose a different slot.']);
        }

        // Calculate total
        $start  = \Carbon\Carbon::parse($validated['start_time']);
        $end    = \Carbon\Carbon::parse($validated['end_time']);
        $hours  = round($end->diffInMinutes($start) / 60, 1);

        if ($venue->hourly_rate) {
            $total = $venue->hourly_rate * $hours;
        } elseif ($hours >= 8 && $venue->full_day_rate) {
            $total = $venue->full_day_rate;
        } else {
            $total = $venue->booking_fee;
        }

        $booking = Booking::create([
            'reference_number'    => Booking::generateReference(),
            'venue_id'            => $venue->id,
            'user_id'             => Auth::id(),
            'event_type'          => $validated['event_type'],
            'event_date'          => $validated['event_date'],
            'start_time'          => $validated['start_time'] . ':00',
            'end_time'            => $validated['end_time'] . ':00',
            'expected_guests'     => $validated['expected_guests'],
            'contact_name'        => $validated['contact_name'],
            'contact_phone'       => $validated['contact_phone'],
            'contact_email'       => $validated['contact_email'],
            'organization'        => $validated['organization'] ?? null,
            'special_requirements' => $validated['special_requirements'] ?? null,
            'total_amount'        => $total,
            'status'              => $venue->requires_approval ? 'pending' : 'confirmed',
            'confirmed_at'        => $venue->requires_approval ? null : now(),
        ]);

        return redirect()->route('bookings.confirmation', $booking->reference_number)
            ->with('success', 'Your booking has been submitted successfully!');
    }

    /** Booking confirmation page */
    public function confirmation(string $reference)
    {
        $booking = Booking::with('venue')
            ->where('reference_number', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('bookings.confirmation', compact('booking'));
    }

    /** User dashboard – list all their bookings */
    public function dashboard()
    {
        $user = Auth::user();

        $upcoming = $user->bookings()
            ->with('venue')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->get();

        $past = $user->bookings()
            ->with('venue')
            ->where(function ($q) {
                $q->whereIn('status', ['completed', 'cancelled'])
                  ->orWhere('event_date', '<', now()->toDateString());
            })
            ->orderByDesc('event_date')
            ->take(20)
            ->get();

        return view('bookings.dashboard', compact('upcoming', 'past'));
    }

    /** Show a single booking detail */
    public function show(string $reference)
    {
        $booking = Booking::with('venue.facilities')
            ->where('reference_number', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('bookings.show', compact('booking'));
    }

    /** Cancel a booking */
    public function cancel(Request $request, string $reference)
    {
        $booking = Booking::where('reference_number', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$booking->canBeCancelled()) {
            return back()->withErrors(['cancel' => 'This booking cannot be cancelled.']);
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->route('bookings.dashboard')
            ->with('success', "Booking {$reference} has been cancelled successfully.");
    }
}
