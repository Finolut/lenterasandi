@props([
    'active' => '',
    'showAdmin' => false,
])

@php
$isHome = request()->routeIs('home');

$navItems = [
    [
        'id' => 'beranda',
        'label' => 'Beranda',
        'url' => $isHome ? '#beranda' : route('home') . '#beranda',
        'is_active' => ($active === 'Beranda' || ($isHome && empty($active))),
    ],
    [
        'id' => 'ebook',
        'label' => 'E-Book',
        'url' => $isHome ? '#ebook' : route('home') . '#ebook',
        'badge' => 'Baru',
        'is_active' => ($active === 'E-Book' || $active === 'Koleksi E-Book' || request()->routeIs('ebooks.*')),
    ],
    [
        'id' => 'artikel',
        'label' => 'Artikel',
        'url' => $isHome ? '#artikel' : route('home') . '#artikel',
        'is_active' => ($active === 'Artikel' || $active === 'Informasi' || request()->routeIs('informasi.*')),
    ],
    [
        'id' => 'tentang',
        'label' => 'Tentang',
        'url' => $isHome ? '#tentang' : route('home') . '#tentang',
        'is_active' => ($active === 'Tentang'),
    ],
];
@endphp

<header
    x-data="{ mobileMenu: false, isScrolled: false }"
    x-init="isScrolled = window.pageYOffset > 15"
    @scroll.window.passive="isScrolled = window.pageYOffset > 15"
    class="fixed top-0 left-0 w-full z-50 px-3 sm:px-6 transition-all duration-300 ease-out"
    :class="isScrolled ? 'pt-2 md:pt-2.5' : 'pt-3 md:pt-4'"
>
    <div class="max-w-7xl mx-auto">
        {{-- Floating Navbar Container dengan Smooth Floating & Glassmorphism --}}
        <div
            class="relative z-20 rounded-2xl border px-4 sm:px-6 flex items-center justify-between transition-all duration-300 ease-out"
            :class="isScrolled
                ? 'bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-md shadow-md border-slate-200/90 dark:border-slate-800 py-2'
                : 'bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-sm shadow-xs border-[#E6ECF5] dark:border-slate-800 py-2.5'"
        >
            {{-- Kiri: Logo --}}
            <div class="flex items-center shrink-0 w-36 lg:w-48">
                <a href="{{ $isHome ? '#beranda' : route('home') }}" {{ !$isHome ? 'wire:navigate' : '' }} class="flex items-center group select-none transition-transform duration-200 hover:scale-[1.02]">
                    <img src="{{ asset('assets/images/logo2.png') }}" alt="Lentera Sandi" class="h-10 sm:h-11 md:h-12 w-auto object-contain group-hover:opacity-90 transition-opacity">
                </a>
            </div>

            @if(!$showAdmin)
                {{-- Tengah: Menu Navigasi Presisi di Tengah dengan Smooth Indicator --}}
                <nav class="hidden lg:flex items-center justify-center gap-6 xl:gap-8 flex-1">
                    @foreach($navItems as $item)
                        <a
                            href="{{ $item['url'] }}"
                            data-nav-id="{{ $item['id'] }}"
                            {{ !str_starts_with($item['url'], '#') ? 'wire:navigate.hover' : '' }}
                            class="nav-link-item group relative text-sm font-medium transition-colors duration-200 inline-flex items-center gap-1.5 py-1 px-0.5 {{ $item['is_active'] ? 'text-[#083575] dark:text-sky-400 font-semibold active-link' : 'text-[#475467] dark:text-slate-300 hover:text-[#083575] dark:hover:text-sky-300' }}"
                        >
                            <span>{{ $item['label'] }}</span>
                            @if(!empty($item['badge']))
                                <span class="bg-[#F02D2D] text-white text-[10px] font-bold px-2 py-0.5 rounded-full leading-none shadow-2xs">
                                    {{ $item['badge'] }}
                                </span>
                            @endif

                            {{-- Smooth Animated Active / Hover Pill Indicator --}}
                            <span class="nav-indicator absolute -bottom-1 left-0 right-0 h-[2.5px] bg-[#083575] dark:bg-sky-400 rounded-full transition-all duration-300 ease-out origin-center {{ $item['is_active'] ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-75 group-hover:opacity-40' }}"></span>
                        </a>
                    @endforeach
                </nav>

                {{-- Kanan: Tombol Theme Toggle di Desktop --}}
                <div class="hidden lg:flex items-center justify-end shrink-0 w-36 lg:w-48">
                    <button
                        type="button"
                        onclick="window.toggleTheme()"
                        class="p-2 rounded-xl border border-[#E6ECF5] dark:border-slate-800 bg-slate-50 dark:bg-slate-800/90 text-slate-600 dark:text-amber-300 hover:bg-slate-100 dark:hover:bg-slate-700/80 hover:text-[#083575] dark:hover:text-amber-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-sky-500/30 cursor-pointer shadow-2xs hover:scale-105 active:scale-95"
                        aria-label="Ganti mode terang/gelap"
                        title="Ganti Mode Terang / Gelap"
                    >
                        {{-- Ikon Matahari --}}
                        <svg class="w-5 h-5 hidden dark:block text-amber-300 transition-transform duration-300 hover:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{-- Ikon Bulan --}}
                        <svg class="w-5 h-5 block dark:hidden text-[#475467] transition-transform duration-300 hover:-rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

                {{-- Kanan: Tombol Theme Toggle & Hamburger Mobile --}}
                <div class="flex lg:hidden items-center gap-2 ml-auto">
                    {{-- Mobile Theme Toggle --}}
                    <button
                        type="button"
                        onclick="window.toggleTheme()"
                        class="p-2 rounded-xl border border-[#E6ECF5] dark:border-slate-800 bg-white dark:bg-slate-800 text-slate-600 dark:text-amber-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none transition-colors"
                        aria-label="Ganti mode terang/gelap"
                        title="Ganti Mode Terang / Gelap"
                    >
                        <svg class="w-5 h-5 hidden dark:block text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg class="w-5 h-5 block dark:hidden text-[#475467]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <button
                        @click="mobileMenu = !mobileMenu"
                        type="button"
                        class="p-2 rounded-xl border border-[#E6ECF5] dark:border-slate-800 bg-white dark:bg-slate-800 text-[#083575] dark:text-sky-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none transition-colors"
                        aria-label="Toggle menu"
                    >
                        <svg class="w-5 h-5 transition-transform duration-200" :class="mobileMenu ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                        </svg>
                    </button>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono text-[#5A6A85] dark:text-slate-400">Mode Wireframe</span>
                </div>
            @endif
        </div>

        {{-- Mobile Drawer Menu dengan Animasi Smooth Dropdown --}}
        @if(!$showAdmin)
            <div
                x-show="mobileMenu"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-3 scale-95"
                class="lg:hidden mt-2 bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-md rounded-2xl border border-[#E6ECF5] dark:border-slate-800 shadow-xl p-3.5 flex flex-col gap-1.5 relative z-30 transition-all duration-200"
                style="display: none;"
            >
                @foreach($navItems as $item)
                    <a
                        href="{{ $item['url'] }}"
                        data-mobile-nav-id="{{ $item['id'] }}"
                        {{ !str_starts_with($item['url'], '#') ? 'wire:navigate' : '' }}
                        @click="mobileMenu = false"
                        class="mobile-nav-link-item px-3.5 py-2.5 rounded-xl text-sm font-medium flex items-center justify-between transition-all duration-200 {{ $item['is_active'] ? 'bg-[#E9F2FC] dark:bg-sky-950/60 text-[#083575] dark:text-sky-300 font-semibold active-link' : 'text-[#475467] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/80' }}"
                    >
                        <span>{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="bg-[#F02D2D] text-white text-[10px] font-bold px-2 py-0.5 rounded-full leading-none">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach

                {{-- Baris Theme Switch di Mobile Drawer --}}
                <div class="pt-2 mt-1 border-t border-[#f1f5f9] dark:border-slate-800 flex items-center justify-between px-3.5 py-1.5">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Mode Tema</span>
                    <button
                        type="button"
                        onclick="window.toggleTheme()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E6ECF5] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 transition-colors"
                    >
                        <span class="dark:hidden flex items-center gap-1">🌙 Gelap</span>
                        <span class="hidden dark:flex items-center gap-1">☀️ Terang</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</header>

{{-- Smooth Glide & Dynamic ScrollSpy Script untuk Navigasi Beranda --}}
<script data-navigate-once>
(function() {
    let currentGlideAnim = null;

    function cancelGlide() {
        if (currentGlideAnim) {
            window.cancelAnimationFrame(currentGlideAnim);
            currentGlideAnim = null;
        }
    }

    // Interupsi manual user membatalkan animasi glide agar tidak memicu jitter
    window.addEventListener('wheel', cancelGlide, { passive: true });
    window.addEventListener('touchmove', cancelGlide, { passive: true });

    // Smooth Scroll yang responsif dan luwes
    function smoothGlideTo(targetEl, maxDuration = 480) {
        if (!targetEl) return;
        cancelGlide();

        const headerOffset = 88;
        const startY = window.pageYOffset || document.documentElement.scrollTop;
        const targetY = Math.max(0, targetEl.getBoundingClientRect().top + startY - headerOffset);
        const distance = targetY - startY;

        if (Math.abs(distance) < 2) return;

        // Skala durasi secara adaptif: jarak pendek lebih cepat, jarak jauh tetap luwes
        const duration = Math.min(maxDuration, Math.max(260, Math.abs(distance) * 0.35));
        let startTime = null;

        // Kurva Easing Cubic Out yang natural & halus (cepat meluncur di awal, lalu melambat lembut)
        function easeOutCubic(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        function animationLoop(currentTime) {
            if (!startTime) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1);
            const ease = easeOutCubic(progress);

            window.scrollTo(0, Math.round(startY + (distance * ease)));

            if (timeElapsed < duration) {
                currentGlideAnim = window.requestAnimationFrame(animationLoop);
            } else {
                window.scrollTo(0, targetY);
                currentGlideAnim = null;
                updateScrollSpy();
            }
        }

        currentGlideAnim = window.requestAnimationFrame(animationLoop);
    }

    const sectionIds = ['beranda', 'ebook', 'artikel', 'tentang'];

    function updateActiveUI(activeId) {
        if (!activeId) return;

        const desktopLinks = document.querySelectorAll('.nav-link-item');
        const mobileLinks = document.querySelectorAll('.mobile-nav-link-item');

        desktopLinks.forEach(link => {
            const id = link.getAttribute('data-nav-id');
            const indicator = link.querySelector('.nav-indicator');
            if (id === activeId) {
                link.classList.remove('text-[#475467]', 'dark:text-slate-300');
                link.classList.add('text-[#083575]', 'dark:text-sky-400', 'font-semibold', 'active-link');
                if (indicator) {
                    indicator.classList.remove('scale-x-0', 'opacity-0');
                    indicator.classList.add('scale-x-100', 'opacity-100');
                }
            } else {
                link.classList.remove('text-[#083575]', 'dark:text-sky-400', 'font-semibold', 'active-link');
                link.classList.add('text-[#475467]', 'dark:text-slate-300');
                if (indicator) {
                    indicator.classList.remove('scale-x-100', 'opacity-100');
                    indicator.classList.add('scale-x-0', 'opacity-0');
                }
            }
        });

        mobileLinks.forEach(link => {
            const id = link.getAttribute('data-mobile-nav-id');
            if (id === activeId) {
                link.classList.remove('text-[#475467]', 'dark:text-slate-300', 'hover:bg-gray-50', 'dark:hover:bg-slate-800/80');
                link.classList.add('bg-[#E9F2FC]', 'dark:bg-sky-950/60', 'text-[#083575]', 'dark:text-sky-300', 'font-semibold', 'active-link');
            } else {
                link.classList.remove('bg-[#E9F2FC]', 'dark:bg-sky-950/60', 'text-[#083575]', 'dark:text-sky-300', 'font-semibold', 'active-link');
                link.classList.add('text-[#475467]', 'dark:text-slate-300', 'hover:bg-gray-50', 'dark:hover:bg-slate-800/80');
            }
        });
    }

    function updateScrollSpy() {
        const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
        if (!isHomePage) return;

        const scrollPos = window.pageYOffset || document.documentElement.scrollTop;
        const winHeight = window.innerHeight;
        const docHeight = document.documentElement.scrollHeight;

        if (scrollPos < 160) {
            updateActiveUI('beranda');
            return;
        }

        if (scrollPos + winHeight >= docHeight - 70) {
            updateActiveUI('tentang');
            return;
        }

        const triggerLine = 150;
        let currentSection = 'beranda';

        for (let i = 0; i < sectionIds.length; i++) {
            const el = document.getElementById(sectionIds[i]);
            if (el) {
                const rect = el.getBoundingClientRect();
                if (rect.top <= triggerLine) {
                    currentSection = sectionIds[i];
                }
            }
        }

        updateActiveUI(currentSection);
    }

    // Scroll listener dengan throttling requestAnimationFrame
    let isTicking = false;
    window.addEventListener('scroll', function () {
        if (!isTicking) {
            window.requestAnimationFrame(function () {
                updateScrollSpy();
                isTicking = false;
            });
            isTicking = true;
        }
    }, { passive: true });

    // Delegasi klik tautan anchor '#' sekali saja secara global
    if (!window.__navbarClickRegistered) {
        window.__navbarClickRegistered = true;

        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            if (href && href.startsWith('#') && href.length > 1) {
                const targetId = href.substring(1);
                const targetEl = document.getElementById(targetId);
                if (targetEl) {
                    e.preventDefault();
                    updateActiveUI(targetId);
                    smoothGlideTo(targetEl);
                    if (history.pushState) {
                        history.pushState(null, null, '#' + targetId);
                    }
                }
            }
        });
    }

    function setupNavbar() {
        const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
        if (isHomePage) {
            updateScrollSpy();

            if (window.location.hash) {
                const targetId = window.location.hash.substring(1);
                const targetEl = document.getElementById(targetId);
                if (targetEl) {
                    updateActiveUI(targetId);
                    setTimeout(function () {
                        smoothGlideTo(targetEl);
                    }, 180);
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', setupNavbar);
    document.addEventListener('livewire:navigated', setupNavbar);
})();
</script>
