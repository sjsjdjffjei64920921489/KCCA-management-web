@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-teal-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-lg">

        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('venues.index') }}" class="inline-flex items-center justify-center w-16 h-16 bg-green-700 rounded-2xl shadow-lg mb-4 hover:bg-green-800 transition">
                <span class="text-white font-black text-2xl">KC</span>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900">Create your account</h1>
            <p class="text-gray-500 text-sm mt-2">Join KCCA Venues and start booking community spaces across Kampala</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">

            {{-- Errors --}}
            @if($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm flex items-start gap-2 mb-6">
                    <span class="text-lg">❌</span>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5" id="register-form">
                @csrf

                {{-- Full name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="name">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                        <input type="text" name="name" id="name"
                               value="{{ old('name') }}"
                               class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                               placeholder="e.g. Buharl Baryanengwe"
                               required autofocus>
                    </div>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="email">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                               placeholder="you@example.com"
                               autocomplete="email" required>
                    </div>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Phone + Org --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="phone">
                            Phone Number
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">📞</span>
                            <input type="tel" name="phone" id="phone"
                                   value="{{ old('phone') }}"
                                   class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                   placeholder="+256 700 000 000">
                        </div>
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="organization">
                            Organisation <span class="text-gray-400 font-normal text-xs">(optional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🏢</span>
                            <input type="text" name="organization" id="organization"
                                   value="{{ old('organization') }}"
                                   class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                                   placeholder="Company / NGO / School">
                        </div>
                        @error('organization')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="password">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password" id="password"
                               class="w-full rounded-xl border border-gray-300 pl-10 pr-12 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                               placeholder="Minimum 8 characters"
                               autocomplete="new-password" required
                               oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePwd('password','eye1')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg">
                            <span id="eye1">👁️</span>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="mt-2">
                        <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div id="strength-bar" class="h-full rounded-full transition-all duration-300 w-0 bg-red-400"></div>
                        </div>
                        <p id="strength-label" class="text-xs text-gray-400 mt-1"></p>
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Confirm password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="password_confirmation">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full rounded-xl border border-gray-300 pl-10 pr-12 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-600 focus:ring-2 focus:ring-green-100 focus:outline-none transition"
                               placeholder="Repeat your password"
                               autocomplete="new-password" required
                               oninput="checkMatch()">
                        <button type="button" onclick="togglePwd('password_confirmation','eye2')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg">
                            <span id="eye2">👁️</span>
                        </button>
                    </div>
                    <p id="match-msg" class="text-xs mt-1 hidden"></p>
                </div>

                {{-- Terms --}}
                <div class="flex items-start gap-3 bg-gray-50 rounded-xl p-4">
                    <input type="checkbox" id="terms" required
                           class="mt-0.5 w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label for="terms" class="text-xs text-gray-600 cursor-pointer">
                        By creating an account I agree to KCCA's
                        <span class="text-green-700 font-semibold">Terms of Use</span> and
                        <span class="text-green-700 font-semibold">Privacy Policy</span>.
                        My personal details will only be used for booking management.
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl text-sm transition-all duration-200 shadow-lg hover:shadow-green-200 flex items-center justify-center gap-2">
                    🎉 Create My Account
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center"><span class="px-3 bg-white text-xs text-gray-400">Already have an account?</span></div>
            </div>

            <a href="{{ route('login') }}"
               class="w-full py-3 border-2 border-green-700 text-green-700 hover:bg-green-50 font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                🔐 Sign In Instead
            </a>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            🔒 Your information is secure and encrypted.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd(id, eyeId) {
    const inp = document.getElementById(id);
    const eye = document.getElementById(eyeId);
    if (inp.type === 'password') { inp.type = 'text'; eye.textContent = '🙈'; }
    else { inp.type = 'password'; eye.textContent = '👁️'; }
}

function checkStrength(val) {
    const bar   = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w: '0%',   color: 'bg-gray-300', text: '' },
        { w: '25%',  color: 'bg-red-400',    text: '⚠️ Weak' },
        { w: '50%',  color: 'bg-orange-400', text: '🔶 Fair' },
        { w: '75%',  color: 'bg-yellow-400', text: '🔷 Good' },
        { w: '100%', color: 'bg-green-500',  text: '✅ Strong' },
    ];
    const lvl = levels[score];
    bar.style.width = lvl.w;
    bar.className   = `h-full rounded-full transition-all duration-300 ${lvl.color}`;
    label.textContent = lvl.text;
    label.className   = score <= 1 ? 'text-xs text-red-500 mt-1' :
                        score === 2 ? 'text-xs text-orange-500 mt-1' :
                        score === 3 ? 'text-xs text-yellow-600 mt-1' :
                                     'text-xs text-green-600 mt-1';
}

function checkMatch() {
    const p1  = document.getElementById('password').value;
    const p2  = document.getElementById('password_confirmation').value;
    const msg = document.getElementById('match-msg');
    if (!p2) { msg.classList.add('hidden'); return; }
    msg.classList.remove('hidden');
    if (p1 === p2) {
        msg.textContent = '✅ Passwords match';
        msg.className   = 'text-xs mt-1 text-green-600';
    } else {
        msg.textContent = '❌ Passwords do not match';
        msg.className   = 'text-xs mt-1 text-red-500';
    }
}
</script>
@endpush
