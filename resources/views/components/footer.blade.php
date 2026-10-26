@php
    $isHome = request()->routeIs('home');
@endphp

<footer class="mt-auto select-none font-sans text-white" style="background-color: #17437B !important;">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-12 pb-8">
        {{-- Baris Atas: Identitas / Brand di Kiri, Navigasi di Kanan --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-10 lg:gap-16">
            
            {{-- Sisi Kiri: Judul Utama & Subjudul --}}
            <div class="max-w-xl">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white leading-tight">
                    Lentera Sandi
                </h2>
                <p class="mt-2 text-sm sm:text-base text-white/85 font-light leading-relaxed">
                    Portal Literasi &amp; Edukasi Keamanan Informasi
                </p>
            </div>

            {{-- Sisi Kanan: Menu & Informasi (Teks Header Kuning Emas) --}}
            <div class="flex items-start gap-12 sm:gap-16 lg:gap-20">
                {{-- Kolom Menu --}}
                <div>
                    <h3 class="text-sm font-bold mb-3 sm:mb-4 tracking-wide" style="color: #E5B239;">
                        Menu
                    </h3>
                    <ul class="space-y-2 sm:space-y-2.5 text-xs sm:text-sm text-white/85 font-normal">
                        <li>
                            <a href="{{ $isHome ? '#beranda' : route('home') }}" {{ !$isHome ? 'wire:navigate' : '' }} class="hover:text-white hover:underline transition-colors">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ $isHome ? '#ebook' : route('home') . '#ebook' }}" {{ !$isHome ? 'wire:navigate' : '' }} class="hover:text-white hover:underline transition-colors">
                                E-Book
                            </a>
                        </li>
                        <li>
                            <a href="{{ $isHome ? '#artikel' : route('home') . '#artikel' }}" {{ !$isHome ? 'wire:navigate' : '' }} class="hover:text-white hover:underline transition-colors">
                                Artikel
                            </a>
                        </li>
                        <li>
                            <a href="{{ $isHome ? '#tentang' : route('home') . '#tentang' }}" {{ !$isHome ? 'wire:navigate' : '' }} class="hover:text-white hover:underline transition-colors">
                                Tentang
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" wire:navigate class="hover:text-white hover:underline transition-colors">
                                Akses Administrator
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Kolom Informasi --}}
                <div>
                    <h3 class="text-sm font-bold mb-3 sm:mb-4 tracking-wide" style="color: #E5B239;">
                        Informasi
                    </h3>
                    <ul class="space-y-2 sm:space-y-2.5 text-xs sm:text-sm text-white/85 font-normal">
                        <li>
                            <a href="https://jatimprov.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline transition-colors">
                                Jatimprov
                            </a>
                        </li>
                        <li>
                            <a href="https://klinikhoaks.jatimprov.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline transition-colors">
                                Klinik Hoaks
                            </a>
                        </li>
                        <li>
                            <a href="https://csirt.jatimprov.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline transition-colors">
                                CSIRT Jatim
                            </a>
                        </li>
                        <li>
                            <a href="https://opendata.jatimprov.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline transition-colors">
                                Satu Data Jatim
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        {{-- Garis Pemisah Kuning Emas Horisontal --}}
        <div class="mt-10 sm:mt-12 mb-6" style="border-top: 1px solid #E5B239;"></div>

        {{-- Baris Bawah: Copyright di Kiri, Ikon Sosmed di Kanan --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs sm:text-sm text-white/90">
            {{-- Copyright Text --}}
            <div class="text-center sm:text-left font-normal">
                Lentera Sandi &copy; {{ date('Y') }} All rights reserved
            </div>

            {{-- Sosial Media Diskominfo Jatim --}}
            <div class="flex items-center gap-4 sm:gap-5 flex-wrap justify-center sm:justify-end">
                {{-- YouTube --}}
                <a href="https://www.youtube.com/@kominfojatim" target="_blank" rel="noopener noreferrer" class="text-white hover:text-white/75 transition-colors" title="YouTube Diskominfo Jatim" aria-label="YouTube">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </a>

                {{-- Facebook --}}
                <a href="https://www.facebook.com/kominfojatim" target="_blank" rel="noopener noreferrer" class="text-white hover:text-white/75 transition-colors" title="Facebook Diskominfo Jatim" aria-label="Facebook">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>

                {{-- Instagram --}}
                <a href="https://www.instagram.com/kominfojatim" target="_blank" rel="noopener noreferrer" class="text-white hover:text-white/75 transition-colors" title="Instagram Diskominfo Jatim" aria-label="Instagram">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>

                {{-- TikTok --}}
                <a href="https://www.tiktok.com/@kominfojatim" target="_blank" rel="noopener noreferrer" class="text-white hover:text-white/75 transition-colors" title="TikTok Diskominfo Jatim" aria-label="TikTok">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-1.01v8.46c0 1.07-.2 2.14-.6 3.14-.54 1.34-1.48 2.5-2.69 3.28-1.22.79-2.67 1.18-4.13 1.14-1.63-.03-3.23-.59-4.52-1.57-1.39-1.05-2.34-2.58-2.67-4.3-.32-1.7-.05-3.48.78-4.99.82-1.5 2.16-2.66 3.76-3.24.4-.14.82-.25 1.24-.31v4.08c-.41.13-.8.36-1.12.67-.44.43-.72.99-.81 1.6-.1.68.04 1.38.38 1.96.35.58.91 1 1.56 1.19.66.19 1.38.12 1.99-.2.62-.32 1.08-.88 1.28-1.55.15-.5.2-1.03.2-1.56V.02z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>
