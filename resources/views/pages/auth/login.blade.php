<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk - Lentera Sandi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-50 font-sans antialiased text-slate-800 flex items-center justify-center p-4 sm:p-6">

    <div class="w-full max-w-sm">

        {{-- Header / Brand --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block hover:opacity-90 transition-opacity">
                <img src="{{ asset('assets/images/logo2.png') }}" alt="Lentera Sandi" class="h-14 sm:h-16 w-auto mx-auto mb-2 object-contain">
            </a>
            <p class="text-sm text-slate-500">Masuk ke panel admin</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 px-3.5 py-2.5 rounded-xl text-xs flex items-start gap-2.5">
                    <svg class="w-4 h-4 shrink-0 mt-0.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Status Notification --}}
            @if (session('status'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3.5 py-2.5 rounded-xl text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-700 mb-1.5">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@jatimprov.go.id"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-blue-600 focus:ring-blue-600/20' }} text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-slate-700 mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            class="w-full px-3.5 py-2.5 pr-10 text-sm rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-blue-600 focus:ring-blue-600/20' }} text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 transition-colors"
                            aria-label="Toggle password visibility"
                        >
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg id="eyeOffIcon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="text-xs text-slate-600">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button
                        id="btn-login"
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-sm font-medium py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition-all duration-150 flex items-center justify-center"
                    >
                        Masuk
                    </button>
                </div>
            </form>
        </div>

        {{-- Back to Home --}}
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition-colors font-medium">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        // Toggle Show / Hide Password
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        toggleBtn?.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('hidden', isPassword);
            eyeOffIcon.classList.toggle('hidden', !isPassword);
        });

        // Loading state saat submit
        const form = document.querySelector('form');
        const loginBtn = document.getElementById('btn-login');
        form?.addEventListener('submit', () => {
            loginBtn.disabled = true;
            loginBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
            loginBtn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
