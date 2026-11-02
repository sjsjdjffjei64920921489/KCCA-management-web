<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $stats = [
            'total'     => $user->bookings()->count(),
            'upcoming'  => $user->bookings()->whereIn('status', ['pending','confirmed'])->where('event_date', '>=', now()->toDateString())->count(),
            'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
            'cancelled' => $user->bookings()->where('status', 'cancelled')->count(),
        ];
        $recentBookings = $user->bookings()->with('venue')->orderByDesc('created_at')->take(3)->get();

        return view('auth.profile', compact('user', 'stats', 'recentBookings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => 'required|string|max:150',
            'phone'        => 'nullable|string|max:30',
            'organization' => 'nullable|string|max:150',
            'email'        => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Your profile has been updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.'])->withFragment('password');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.')->withFragment('password');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate(['confirm_delete' => 'required|in:DELETE']);

        $user = Auth::user();

        // Cancel all upcoming bookings first
        $user->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('event_date', '>=', now()->toDateString())
            ->update(['status' => 'cancelled', 'cancelled_at' => now(), 'cancellation_reason' => 'Account deleted by user']);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('venues.index')->with('success', 'Your account has been deleted.');
    }
}
