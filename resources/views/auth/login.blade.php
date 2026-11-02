@extends('layouts.app')
@section('title', 'Sign In')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-teal-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('venues.index') }}"
               class="inline-flex items-center justify-center w-16 h-16 bg-green-700 rounded-2xl shadow-lg mb-4 hover:bg-green-800 transition">
                <span class="text-white font-black text-2xl">KC</span>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900">Welcome back</h1>
            <p class="text-gray-500 text-sm mt-2">Sign in to manage your venue bookings</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">

            {{-- Errors --}}
            @if($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm flex items-start gap-2 mb-6">
                    <span class="text-lg leading-tight">❌</span>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="email">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">✉️</span>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                               placeholder="you@example.com"
                               autocomplete="email" required autofocus>
                    </div>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-semibold text-gray-700" for="password">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">🔒</span>
                        <input type="password" name="password" id="password"
                               class="w-full rounded-xl border border-gray-300 pl-10 pr-12 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                               placeholder="Enter your password"
                               autocomplete="current-password" required>
                        <button type="button" onclick="togglePwd()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg">
                            <span id="eye-icon">👁️</span>
                        </button>
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition">Remember me for 30 days</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-green-700 hover:bg-green-800 active:bg-green-900 text-white font-bold rounded-xl text-sm transition-all duration-200 shadow-lg hover:shadow-green-200 flex items-center justify-center gap-2">
                    🔐 Sign In to My Account
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-3 bg-white text-xs text-gray-400">Don't have an account?</span>
                </div>
            </div>

            <a href="{{ route('register') }}"
               class="w-full py-3 border-2 border-green-700 text-green-700 hover:bg-green-50 font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                🎉 Create a Free Account
            </a>
        </div>

        {{-- What you get --}}
        <div class="mt-8 grid grid-cols-3 gap-4 text-center">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="text-2xl mb-1">🏛️</div>
                <p class="text-xs text-gray-600 font-medium">Browse all<br>venues</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="text-2xl mb-1">📋</div>
                <p class="text-xs text-gray-600 font-medium">Book & manage<br>events</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="text-2xl mb-1">✅</div>
                <p class="text-xs text-gray-600 font-medium">Get instant<br>confirmation</p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            🔒 Secured by KCCA · Kampala Capital City Authority
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd() {
    const inp = document.getElementById('password');
    const eye = document.getElementById('eye-icon');
    if (inp.type === 'password') { inp.type = 'text';     eye.textContent = '🙈'; }
    else                         { inp.type = 'password'; eye.textContent = '👁️'; }
}
</script>
@endpush
