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
        'label' => 'Koleksi E-Book',
        'url' => $isHome ? '#ebook' : route('home') . '#ebook',
        'is_active' => ($active === 'E-Book' || $active === 'Koleksi E-Book' || request()->routeIs('ebooks.*')),
    ],
    [
        'id' => 'artikel',
        'label' => 'Artikel & Berita',
        'url' => $isHome ? '#artikel' : route('home') . '#artikel',
        'is_active' => ($active === 'Artikel' || $active === 'Informasi' || request()->routeIs('informasi.*')),
    ],
    [
        'id' => 'csirt',
        'label' => 'CSIRT Jatim',
        'url' => 'https://csirt.jatimprov.go.id',
        'target' => '_blank',
        'is_active' => false,
    ],
];
@endphp

{{-- Full-Width Solid Navbar (OAPEN-Style Template) --}}
<header
    x-data="{ mobileMenu: false, isScrolled: false }"
    x-init="isScrolled = window.pageYOffset > 10"
    @scroll.window.passive="isScrolled = window.pageYOffset > 10"
    class="fixed top-0 left-0 w-full z-50 bg-[#0b3d2e] text-white transition-all duration-200"
    :class="isScrolled ? 'shadow-md border-b border-[#082e23]' : 'border-b border-[#124938]'"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-18">
            
            {{-- Sisi Kiri: Logo Identitas (Crisp White Treatment) --}}
            <div class="flex items-center shrink-0">
                <a href="{{ $isHome ? '#beranda' : route('home') }}" {{ !$isHome ? 'wire:navigate' : '' }} class="flex items-center gap-2 group select-none transition-opacity duration-150 hover:opacity-90">
                    <img src="{{ asset('assets/images/logo2.png') }}" alt="Lentera Sandi" class="h-9 sm:h-10 md:h-11 w-auto object-contain brightness-0 invert">
                </a>
            </div>

            @if(!$showAdmin)
                {{-- Sisi Kanan: Menu Navigasi & Action Button (Desktop) --}}
                <div class="hidden lg:flex items-center gap-6 xl:gap-8 ml-auto">
                    {{-- Nav Links --}}
                    <nav class="flex items-center gap-6 xl:gap-8">
                        @foreach($navItems as $item)
                            <a
                                href="{{ $item['url'] }}"
                                data-nav-id="{{ $item['id'] }}"
                                @if(!empty($item['target'])) target="{{ $item['target'] }}" rel="noopener noreferrer" @endif
                                {{ (!empty($item['target']) || str_starts_with($item['url'], '#')) ? '' : 'wire:navigate.hover' }}
                                class="nav-link-item text-[13px] xl:text-sm font-semibold tracking-wide transition-colors duration-150 py-1 inline-flex items-center gap-1.5 {{ $item['is_active'] ? 'text-emerald-300 font-bold active-link' : 'text-white hover:text-emerald-200' }}"
                            >
                                <span>{{ $item['label'] }}</span>
                                @if(!empty($item['badge']))
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
                                        {{ $item['badge'] }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </nav>

                    {{-- Action Button: Outlined Box Button (seperti tombol 'Newsletter' pada template) --}}
                    <div class="flex items-center gap-3 pl-2 border-l border-white/20">
                        @auth
                            <a
                                href="{{ route('admin.dashboard') }}"
                                wire:navigate
                                class="border border-white text-white hover:bg-white hover:text-[#0b3d2e] px-4 py-1.5 text-xs font-semibold tracking-wider uppercase transition-all duration-150 rounded-xs select-none shadow-2xs"
                                title="Masuk ke Panel Administrasi"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                wire:navigate
                                class="border border-white text-white hover:bg-white hover:text-[#0b3d2e] px-4 py-1.5 text-xs font-semibold tracking-wider uppercase transition-all duration-150 rounded-xs select-none shadow-2xs"
                                title="Login ke Sistem Administrasi"
                            >
                                Akses Admin
                            </a>
                        @endauth

                        {{-- Dark/Light Mode Minimalist Toggle --}}
                        <button
                            type="button"
                            onclick="window.toggleTheme()"
                            class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded transition-colors cursor-pointer"
                            aria-label="Ganti mode terang/gelap"
                            title="Ganti Mode Terang / Gelap"
                        >
                            {{-- Ikon Matahari (tampil di dark mode) --}}
                            <svg class="w-4 h-4 hidden dark:block text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{-- Ikon Bulan (tampil di light mode) --}}
                            <svg class="w-4 h-4 block dark:hidden text-white/90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Sisi Kanan: Mobile Action & Hamburger --}}
                <div class="flex lg:hidden items-center gap-2">
                    <button
                        type="button"
                        onclick="window.toggleTheme()"
                        class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-md transition-colors"
                        aria-label="Ganti mode terang/gelap"
                    >
                        <svg class="w-4 h-4 hidden dark:block text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg class="w-4 h-4 block dark:hidden text-white/90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <button
                        @click="mobileMenu = !mobileMenu"
                        type="button"
                        class="p-2 text-white hover:bg-white/10 rounded-md transition-colors focus:outline-none"
                        aria-label="Toggle menu"
                    >
                        <svg class="w-6 h-6 transition-transform duration-200" :class="mobileMenu ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                        </svg>
                    </button>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono text-emerald-200">Mode Wireframe</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Mobile Drawer Dropdown --}}
    @if(!$showAdmin)
        <div
            x-show="mobileMenu"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden bg-[#082e23] border-t border-[#124938] px-4 py-4 flex flex-col gap-2"
            style="display: none;"
        >
            @foreach($navItems as $item)
                <a
                    href="{{ $item['url'] }}"
                    data-mobile-nav-id="{{ $item['id'] }}"
                    @if(!empty($item['target'])) target="{{ $item['target'] }}" rel="noopener noreferrer" @endif
                    {{ (!empty($item['target']) || str_starts_with($item['url'], '#')) ? '' : 'wire:navigate' }}
                    @click="mobileMenu = false"
                    class="mobile-nav-link-item px-3 py-2 rounded text-sm font-semibold tracking-wide transition-colors {{ $item['is_active'] ? 'bg-white/15 text-emerald-300 font-bold active-link' : 'text-white/90 hover:bg-white/10 hover:text-white' }}"
                >
                    <span>{{ $item['label'] }}</span>
                    @if(!empty($item['badge']))
                        <span class="ml-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach

            <div class="pt-3 mt-2 border-t border-white/15 flex flex-col">
                @auth
                    <a
                        href="{{ route('admin.dashboard') }}"
                        wire:navigate
                        @click="mobileMenu = false"
                        class="text-center border border-white text-white hover:bg-white hover:text-[#0b3d2e] px-4 py-2 text-xs font-semibold tracking-wider uppercase transition-colors rounded-xs"
                    >
                        Dashboard Admin
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        wire:navigate
                        @click="mobileMenu = false"
                        class="text-center border border-white text-white hover:bg-white hover:text-[#0b3d2e] px-4 py-2 text-xs font-semibold tracking-wider uppercase transition-colors rounded-xs"
                    >
                        Akses Admin
                    </a>
                @endauth
            </div>
        </div>
    @endif
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

    window.addEventListener('wheel', cancelGlide, { passive: true });
    window.addEventListener('touchmove', cancelGlide, { passive: true });

    function smoothGlideTo(targetEl, maxDuration = 480) {
        if (!targetEl) return;
        cancelGlide();

        const headerOffset = 70;
        const startY = window.pageYOffset || document.documentElement.scrollTop;
        const targetY = Math.max(0, targetEl.getBoundingClientRect().top + startY - headerOffset);
        const distance = targetY - startY;

        if (Math.abs(distance) < 2) return;

        const duration = Math.min(maxDuration, Math.max(260, Math.abs(distance) * 0.35));
        let startTime = null;

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

    const sectionIds = ['beranda', 'ebook', 'artikel'];

    function updateActiveUI(activeId) {
        if (!activeId) return;

        const desktopLinks = document.querySelectorAll('.nav-link-item');
        const mobileLinks = document.querySelectorAll('.mobile-nav-link-item');

        desktopLinks.forEach(link => {
            const id = link.getAttribute('data-nav-id');
            if (id === activeId) {
                link.classList.remove('text-white', 'hover:text-emerald-200');
                link.classList.add('text-emerald-300', 'font-bold', 'active-link');
            } else if (id && sectionIds.includes(id)) {
                link.classList.remove('text-emerald-300', 'font-bold', 'active-link');
                link.classList.add('text-white', 'hover:text-emerald-200');
            }
        });

        mobileLinks.forEach(link => {
            const id = link.getAttribute('data-mobile-nav-id');
            if (id === activeId) {
                link.classList.remove('text-white/90', 'hover:bg-white/10');
                link.classList.add('bg-white/15', 'text-emerald-300', 'font-bold', 'active-link');
            } else if (id && sectionIds.includes(id)) {
                link.classList.remove('bg-white/15', 'text-emerald-300', 'font-bold', 'active-link');
                link.classList.add('text-white/90', 'hover:bg-white/10');
            }
        });
    }

    function updateScrollSpy() {
        const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
        if (!isHomePage) return;

        const scrollPos = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollPos < 140) {
            updateActiveUI('beranda');
            return;
        }

        const triggerLine = 120;
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
