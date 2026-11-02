<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:150',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'nullable|string|max:30',
            'organization' => 'nullable|string|max:150',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'password'     => Hash::make($validated['password']),
            'role'         => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('bookings.dashboard')
            ->with('success', 'Welcome! Your account has been created.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('bookings.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('venues.index')->with('success', 'You have been logged out.');
    }
}
