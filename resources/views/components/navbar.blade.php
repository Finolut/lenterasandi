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
        'url' => $isHome ? '#beranda' : route('home'),
        'is_active' => ($active === 'Beranda' || ($isHome && empty($active))),
    ],
    [
        'id' => 'ebook',
        'label' => 'Koleksi E-Book',
        'url' => $isHome ? '#ebook' : route('ebooks.index'),
        'is_active' => ($active === 'E-Book' || $active === 'Koleksi E-Book' || request()->routeIs('ebooks.*')),
    ],
    [
        'id' => 'artikel',
        'label' => 'Artikel & Berita',
        'url' => $isHome ? '#artikel' : route('informasi.index'),
        'is_active' => ($active === 'Artikel' || $active === 'Informasi' || request()->routeIs('informasi.*')),
    ],
];
@endphp

{{-- Full-Width Admin Navy Navbar dengan Smooth Shrink saat Scroll --}}
<header
    x-data="{ mobileMenu: false, isScrolled: false }"
    x-init="isScrolled = window.pageYOffset > 20"
    @scroll.window.passive="isScrolled = window.pageYOffset > 20"
    class="fixed top-0 left-0 w-full z-50 text-white transition-all duration-300 ease-in-out"
    :class="isScrolled
        ? 'bg-[#062859]/95 backdrop-blur-md shadow-lg shadow-black/15 border-b border-[#0b489a] py-2 md:py-2.5'
        : 'bg-[#083575] border-b border-[#0b489a]/70 py-3.5 md:py-4'"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between transition-all duration-300 ease-in-out">
            
            {{-- Sisi Kiri: Logo Identitas (Menyusut Halus saat Scroll) --}}
            <div class="flex items-center shrink-0">
                <a href="{{ $isHome ? '#beranda' : route('home') }}" {{ !$isHome ? 'wire:navigate' : '' }} class="flex items-center gap-2 group select-none transition-opacity duration-150 hover:opacity-90">
                    <img
                        src="{{ asset('assets/images/logo2.png') }}"
                        alt="Lentera Sandi"
                        class="w-auto object-contain brightness-0 invert transition-all duration-300 ease-in-out"
                        :class="isScrolled ? 'h-8 sm:h-8.5 md:h-9' : 'h-9 sm:h-10 md:h-11'"
                    >
                </a>
            </div>

            @if(!$showAdmin)
                {{-- Sisi Kanan: Menu Navigasi & Theme Toggle (Desktop) --}}
                <div class="hidden lg:flex items-center gap-7 xl:gap-9 ml-auto">
                    {{-- Nav Links --}}
                    <nav class="flex items-center gap-6 xl:gap-8">
                        @foreach($navItems as $item)
                            <a
                                href="{{ $item['url'] }}"
                                data-nav-id="{{ $item['id'] }}"
                                {{ str_starts_with($item['url'], '#') ? '' : 'wire:navigate.hover' }}
                                class="nav-link-item group relative text-[13px] xl:text-sm font-semibold tracking-wide transition-colors duration-200 py-1 inline-flex items-center gap-1.5 {{ $item['is_active'] ? 'text-cyan-300 font-bold active-link' : 'text-sky-100/90 hover:text-white' }}"
                            >
                                <span>{{ $item['label'] }}</span>
                                @if(!empty($item['badge']))
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
                                        {{ $item['badge'] }}
                                    </span>
                                @endif
                                {{-- Cyan indicator line --}}
                                <span class="nav-indicator absolute -bottom-1 left-0 right-0 h-[2px] bg-cyan-400 rounded-full transition-all duration-300 ease-out origin-center {{ $item['is_active'] ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-75 group-hover:opacity-40' }}"></span>
                            </a>
                        @endforeach
                    </nav>

                    {{-- Dark/Light Mode Minimalist Toggle --}}
                    <div class="flex items-center pl-2 border-l border-white/15">
                        <button
                            type="button"
                            onclick="window.toggleTheme()"
                            class="p-2 text-sky-100/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors cursor-pointer"
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
                <div class="flex lg:hidden items-center gap-1.5">
                    <button
                        type="button"
                        onclick="window.toggleTheme()"
                        class="p-2 text-sky-100/80 hover:text-white hover:bg-white/10 rounded-md transition-colors"
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
                        class="p-2 text-sky-100 hover:text-white hover:bg-white/10 rounded-md transition-colors focus:outline-none"
                        aria-label="Toggle menu"
                    >
                        <svg class="w-6 h-6 transition-transform duration-200" :class="mobileMenu ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                        </svg>
                    </button>
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
            class="lg:hidden bg-[#062859] border-t border-[#0b489a] px-4 py-3 flex flex-col gap-1 shadow-xl"
            style="display: none;"
        >
            @foreach($navItems as $item)
                <a
                    href="{{ $item['url'] }}"
                    data-mobile-nav-id="{{ $item['id'] }}"
                    {{ str_starts_with($item['url'], '#') ? '' : 'wire:navigate' }}
                    @click="mobileMenu = false"
                    class="mobile-nav-link-item px-3.5 py-2.5 rounded-lg text-sm font-semibold tracking-wide transition-colors {{ $item['is_active'] ? 'bg-[#0e438c] text-cyan-300 font-bold active-link' : 'text-sky-100 hover:bg-white/10 hover:text-white' }}"
                >
                    <span>{{ $item['label'] }}</span>
                    @if(!empty($item['badge']))
                        <span class="ml-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
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

        const headerOffset = 64;
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
            const indicator = link.querySelector('.nav-indicator');
            if (id === activeId) {
                link.classList.remove('text-sky-100/90');
                link.classList.add('text-cyan-300', 'font-bold', 'active-link');
                if (indicator) {
                    indicator.classList.remove('scale-x-0', 'opacity-0');
                    indicator.classList.add('scale-x-100', 'opacity-100');
                }
            } else if (id && sectionIds.includes(id)) {
                link.classList.remove('text-cyan-300', 'font-bold', 'active-link');
                link.classList.add('text-sky-100/90');
                if (indicator) {
                    indicator.classList.remove('scale-x-100', 'opacity-100');
                    indicator.classList.add('scale-x-0', 'opacity-0');
                }
            }
        });

        mobileLinks.forEach(link => {
            const id = link.getAttribute('data-mobile-nav-id');
            if (id === activeId) {
                link.classList.remove('text-sky-100', 'hover:bg-white/10');
                link.classList.add('bg-[#0e438c]', 'text-cyan-300', 'font-bold', 'active-link');
            } else if (id && sectionIds.includes(id)) {
                link.classList.remove('bg-[#0e438c]', 'text-cyan-300', 'font-bold', 'active-link');
                link.classList.add('text-sky-100', 'hover:bg-white/10');
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
