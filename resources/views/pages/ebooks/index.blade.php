<div class="min-h-screen bg-[#f8fafc] dark:bg-[#0b1329] text-[#0f172a] dark:text-[#f8fafc] pb-16 transition-colors duration-200">
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Subtle Silent Top Loading Indicator (No Text) --}}
    <div wire:loading class="fixed top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 via-sky-400 to-indigo-600 animate-pulse" style="z-index: 10000;"></div>

    {{-- Top Sticky Header (Solid White / Dark, High z-index) --}}
    <header
        class="border-b border-[#e2e8f0] dark:border-slate-800 shadow-xs bg-white dark:bg-[#0f172a] transition-colors duration-200"
        style="position: sticky; top: 0; z-index: 9999; isolation: isolate;"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Top Row: Brand Logo & Navigation Actions --}}
            <div class="flex items-center justify-between h-14">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 select-none group">
                    <img src="{{ asset('assets/images/logo2.png') }}" alt="Lentera Sandi" class="h-10 sm:h-11 w-auto object-contain group-hover:opacity-90 transition-opacity">
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" wire:navigate class="text-xs sm:text-sm font-semibold text-[#0f3b7a] dark:text-sky-300 hover:text-[#0284c7] transition-colors">
                        Beranda
                    </a>
                    <button
                        type="button"
                        onclick="window.toggleTheme()"
                        class="p-2 rounded-xl border border-[#e2e8f0] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-amber-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                        aria-label="Ganti mode terang/gelap"
                        title="Ganti Mode Terang / Gelap"
                    >
                        <svg class="w-4 h-4 hidden dark:block text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg class="w-4 h-4 block dark:hidden text-[#475467]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Navigation Tabs Row (Route View All per Kategori) --}}
            <nav class="flex items-center gap-1 sm:gap-2 overflow-x-auto no-scrollbar border-t border-[#f1f5f9] dark:border-slate-800 -mb-px">
                <a
                    href="{{ route('ebooks.index') }}"
                    wire:navigate
                    class="py-2.5 px-3 text-xs sm:text-sm font-medium whitespace-nowrap transition-colors border-b-2 cursor-pointer {{ $selectedCategory === 'Semua' ? 'border-[#0284c7] text-[#0284c7] dark:text-sky-400 font-bold' : 'border-transparent text-[#64748b] dark:text-slate-400 hover:text-[#0f172a] dark:hover:text-slate-200 hover:border-[#cbd5e1] dark:hover:border-slate-700' }}"
                >
                    All
                </a>

                @foreach($navCategories as $cat)
                    @php
                        $catSlug = \Illuminate\Support\Str::slug($cat);
                        $isActive = ($selectedCategory === $cat);
                    @endphp
                    <a
                        href="{{ route('ebooks.category', $catSlug) }}"
                        wire:navigate
                        class="py-2.5 px-3 text-xs sm:text-sm font-medium whitespace-nowrap transition-colors border-b-2 cursor-pointer {{ $isActive ? 'border-[#0284c7] text-[#0284c7] dark:text-sky-400 font-bold' : 'border-transparent text-[#64748b] dark:text-slate-400 hover:text-[#0f172a] dark:hover:text-slate-200 hover:border-[#cbd5e1] dark:hover:border-slate-700' }}"
                    >
                        {{ $cat }}
                    </a>
                @endforeach
            </nav>
        </div>
    </header>

    {{-- Main Content Area --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- VIEW MODE 1: Category Shelves (When "Semua" is active) --}}
        @if($isShelfMode)
            @if(count($shelves) > 0)
                <div class="space-y-10">
                    @foreach($shelves as $shelf)
                        @php
                            $shelfSlug = \Illuminate\Support\Str::slug($shelf['name']);
                        @endphp
                        <section class="mb-10 sm:mb-12">
                            {{-- Shelf Header: Title (Count) + View All Button dengan Route --}}
                            <div class="flex items-center justify-between mb-4 border-b border-[#e2e8f0] dark:border-slate-800 pb-2">
                                <h2 class="text-sm sm:text-base md:text-lg font-bold text-[#1e293b] dark:text-slate-100 flex items-center gap-2">
                                    <span>{{ $shelf['name'] }}</span>
                                    <span class="text-xs font-normal text-[#64748b] dark:text-slate-400">({{ $shelf['count'] }})</span>
                                </h2>

                                <a
                                    href="{{ route('ebooks.category', $shelfSlug) }}"
                                    wire:navigate
                                    class="text-xs sm:text-sm font-semibold text-[#0284c7] dark:text-sky-400 hover:text-[#0369a1] dark:hover:text-sky-300 hover:underline flex items-center gap-1 cursor-pointer transition-colors"
                                >
                                    <span>View All</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                            {{-- Horizontal Scrollable Book Shelf with Arrow Navigation --}}
                            <div
                                x-data="{
                                    canScrollLeft: false,
                                    canScrollRight: true,
                                    checkScroll() {
                                        const el = $refs.track;
                                        if (!el) return;
                                        this.canScrollLeft = el.scrollLeft > 10;
                                        this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 10);
                                    },
                                    scroll(amount) {
                                        const el = $refs.track;
                                        el.scrollBy({ left: amount, behavior: 'smooth' });
                                        setTimeout(() => this.checkScroll(), 300);
                                    }
                                }"
                                x-init="$nextTick(() => checkScroll())"
                                class="relative group/shelf"
                            >
                                {{-- Left Scroll Arrow Button --}}
                                <button
                                    x-show="canScrollLeft"
                                    x-cloak
                                    @click="scroll(-320)"
                                    type="button"
                                    class="absolute -left-3 sm:-left-4 top-1/3 -translate-y-1/2 z-20 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/95 dark:bg-slate-800/95 border border-[#cbd5e1] dark:border-slate-700 shadow-md flex items-center justify-center text-[#1e293b] dark:text-slate-100 hover:bg-white dark:hover:bg-slate-700 hover:scale-105 transition-all cursor-pointer"
                                    title="Geser ke kiri"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>

                                {{-- Right Scroll Arrow Button --}}
                                <button
                                    x-show="canScrollRight"
                                    x-cloak
                                    @click="scroll(320)"
                                    type="button"
                                    class="absolute -right-3 sm:-right-4 top-1/3 -translate-y-1/2 z-20 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/95 dark:bg-slate-800/95 border border-[#cbd5e1] dark:border-slate-700 shadow-md flex items-center justify-center text-[#1e293b] dark:text-slate-100 hover:bg-white dark:hover:bg-slate-700 hover:scale-105 transition-all cursor-pointer"
                                    title="Geser ke kanan"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                {{-- Scrollable Track of Books --}}
                                <div
                                    x-ref="track"
                                    @scroll.passive="checkScroll()"
                                    class="flex gap-4 sm:gap-5 overflow-x-auto py-2 px-1 scroll-smooth no-scrollbar"
                                >
                                    @foreach($shelf['books'] as $book)
                                        @php
                                            $coverSrc = $book->cover_image;
                                            $pdfSrc   = $book->file_url ? Storage::disk('public')->url($book->file_url) : null;
                                            $detailUrl = route('ebooks.show', $book->id);
                                        @endphp
                                        <div
                                            x-data
                                            x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })"
                                            class="group flex flex-col shrink-0 select-none"
                                            style="width: 165px; min-width: 165px; max-width: 165px;"
                                        >
                                            <div
                                                class="relative bg-[#f8fafc] dark:bg-slate-900 rounded-lg shadow-xs group-hover:shadow-md group-hover:-translate-y-1 transition-all duration-200 border border-[#e2e8f0] dark:border-slate-800 overflow-hidden"
                                                style="width: 165px; height: 240px; overflow: hidden;"
                                            >
                                                <a href="{{ $detailUrl }}" wire:navigate.hover class="block w-full h-full relative cursor-pointer">
                                                    @if($coverSrc)
                                                        <img src="{{ $coverSrc }}" alt="{{ $book->judul }}" class="w-full h-full object-cover object-top" loading="lazy">
                                                    @elseif($pdfSrc)
                                                        <div class="pdf-cover-wrap relative w-full h-full bg-[#f8fafc] dark:bg-slate-900 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                                                            <div class="pdf-skeleton absolute inset-0 bg-[#e2e8f0] dark:bg-slate-800 animate-pulse flex flex-col items-center justify-center text-[#94a3b8] dark:text-slate-400 gap-1 p-2">
                                                                <svg class="w-5 h-5 animate-spin text-[#64748b] dark:text-slate-400" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                <span class="text-[9px] font-mono text-[#64748b] dark:text-slate-400">Memuat...</span>
                                                            </div>
                                                            <img class="pdf-cover-img hidden w-full h-full object-cover object-top" alt="{{ $book->judul }}">
                                                        </div>
                                                    @else
                                                        <div class="w-full h-full flex flex-col items-center justify-center bg-[#f1f5f9] dark:bg-slate-800 text-[#94a3b8] dark:text-slate-400 p-3 text-center">
                                                            <svg class="w-8 h-8 mb-1.5 stroke-current stroke-1 fill-none text-[#94a3b8] dark:text-slate-400" viewBox="0 0 24 24">
                                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                            </svg>
                                                            <span class="text-[10px] font-medium text-[#64748b] dark:text-slate-400">E-Book</span>
                                                        </div>
                                                    @endif
                                                </a>

                                            </div>

                                            <div class="mt-2 flex flex-col flex-1">
                                                <a href="{{ $detailUrl }}" wire:navigate.hover class="font-semibold text-xs sm:text-[13px] text-[#1e293b] dark:text-slate-200 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-tight line-clamp-2 min-h-[2rem]">
                                                    {{ $book->judul }}
                                                </a>
                                                <div class="mt-1 flex items-center justify-between pt-0.5 text-[#94a3b8] dark:text-slate-500">
                                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#94a3b8] dark:text-slate-400">BOOK</span>
                                                    <a href="{{ $detailUrl }}" wire:navigate.hover class="w-4 h-4 rounded-full border border-[#cbd5e1] dark:border-slate-700 hover:border-[#38bdf8] hover:text-[#0284c7] text-[#94a3b8] dark:text-slate-400 flex items-center justify-center text-[10px] font-bold font-serif transition-colors">i</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-[#e2e8f0] dark:border-slate-800 p-12 text-center my-6 bg-white dark:bg-slate-900 transition-colors">
                    <span class="text-4xl text-[#94a3b8] dark:text-slate-500 block mb-2">📚</span>
                    <h3 class="text-base font-semibold text-[#1e293b] dark:text-slate-200 mb-1">Belum Ada E-Book Tersedia</h3>
                    <p class="text-xs text-[#64748b] dark:text-slate-400">Silakan periksa kembali nanti untuk materi dan buku terbaru.</p>
                </div>
            @endif

        {{-- VIEW MODE 2: Filtered by Category (View All) --}}
        @else
            <div>
                {{-- Header Kategori dengan Tombol Kembali ke Semua Koleksi --}}
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-[#e2e8f0] dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('ebooks.index') }}"
                            wire:navigate
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0284c7] dark:text-sky-300 hover:text-[#0369a1] dark:hover:text-sky-200 bg-[#f0f9ff] dark:bg-sky-950/60 hover:bg-[#e0f2fe] dark:hover:bg-sky-900/60 px-3 py-1.5 rounded-lg transition-colors cursor-pointer border border-[#bae6fd] dark:border-sky-800"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Semua Koleksi</span>
                        </a>

                        <div class="h-4 w-px bg-[#cbd5e1] dark:bg-slate-700"></div>

                        <h2 class="text-sm sm:text-base font-bold text-[#1e293b] dark:text-slate-100">
                            <span>Kategori: {{ $selectedCategory }}</span>
                            <span class="text-xs font-normal text-[#64748b] dark:text-slate-400 ml-1">({{ $ebooks->total() }})</span>
                        </h2>
                    </div>

                    {{-- Sort Dropdown --}}
                    <div>
                        <select
                            wire:model.live="sortBy"
                            class="text-xs border border-[#cbd5e1] dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 px-2.5 py-1.5 text-[#334155] dark:text-slate-200 focus:outline-none focus:border-[#38bdf8] cursor-pointer shadow-xs transition-colors"
                        >
                            @foreach($sortOptions as $sort)
                                <option value="{{ $sort }}">{{ $sort }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Books Grid --}}
                @if($ebooks->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-5 mb-8">
                        @foreach($ebooks as $book)
                            @php
                                $coverSrc = $book->cover_image;
                                $pdfSrc   = $book->file_url ? Storage::disk('public')->url($book->file_url) : null;
                                $detailUrl = route('ebooks.show', $book->id);
                            @endphp
                            <div
                                x-data
                                x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })"
                                class="group flex flex-col w-full max-w-[170px] mx-auto select-none"
                            >
                                <div
                                    class="relative w-full aspect-[2/3] bg-[#f8fafc] dark:bg-slate-900 rounded-lg shadow-xs group-hover:shadow-md group-hover:-translate-y-1 transition-all duration-200 border border-[#e2e8f0] dark:border-slate-800 overflow-hidden"
                                >
                                    <a href="{{ $detailUrl }}" wire:navigate.hover class="block w-full h-full relative cursor-pointer">
                                        @if($coverSrc)
                                            <img src="{{ $coverSrc }}" alt="{{ $book->judul }}" class="w-full h-full object-cover object-top" loading="lazy">
                                        @elseif($pdfSrc)
                                            <div class="pdf-cover-wrap relative w-full h-full bg-[#f8fafc] dark:bg-slate-900 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                                                <div class="pdf-skeleton absolute inset-0 bg-[#e2e8f0] dark:bg-slate-800 animate-pulse flex flex-col items-center justify-center text-[#94a3b8] dark:text-slate-400 gap-1 p-2">
                                                    <svg class="w-5 h-5 animate-spin text-[#64748b] dark:text-slate-400" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <span class="text-[9px] font-mono text-[#64748b] dark:text-slate-400">Memuat...</span>
                                                </div>
                                                <img class="pdf-cover-img hidden w-full h-full object-cover object-top" alt="{{ $book->judul }}">
                                            </div>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-[#f1f5f9] dark:bg-slate-800 text-[#94a3b8] dark:text-slate-400 p-3 text-center">
                                                <svg class="w-8 h-8 mb-1.5 stroke-current stroke-1 fill-none text-[#94a3b8] dark:text-slate-400" viewBox="0 0 24 24">
                                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                </svg>
                                                <span class="text-[10px] font-medium text-[#64748b] dark:text-slate-400">E-Book</span>
                                            </div>
                                        @endif
                                    </a>

                                    </div>

                                <div class="mt-2 flex flex-col flex-1">
                                    <a href="{{ $detailUrl }}" wire:navigate.hover class="font-semibold text-xs sm:text-[13px] text-[#1e293b] dark:text-slate-200 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-tight line-clamp-2 min-h-[2rem]">
                                        {{ $book->judul }}
                                    </a>
                                    <div class="mt-1 flex items-center justify-between pt-0.5 text-[#94a3b8] dark:text-slate-500">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#94a3b8] dark:text-slate-400">BOOK</span>
                                        <a href="{{ $detailUrl }}" wire:navigate.hover class="w-4 h-4 rounded-full border border-[#cbd5e1] dark:border-slate-700 hover:border-[#38bdf8] hover:text-[#0284c7] text-[#94a3b8] dark:text-slate-400 flex items-center justify-center text-[10px] font-bold font-serif transition-colors">i</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="flex items-center justify-center py-4">
                        {{ $ebooks->links('vendor.pagination.simple-custom') }}
                    </div>
                @else
                    <div class="rounded-xl border border-[#e2e8f0] dark:border-slate-800 p-12 text-center my-6 bg-white dark:bg-slate-900 transition-colors">
                        <span class="text-4xl text-[#94a3b8] dark:text-slate-500 block mb-2">🔍</span>
                        <h3 class="text-base font-semibold text-[#1e293b] dark:text-slate-200 mb-1">Tidak Ada E-Book Ditemukan</h3>
                        <p class="text-xs text-[#64748b] dark:text-slate-400 mb-4">
                            Belum ada materi yang tersedia dalam kategori ini.
                        </p>
                        <a
                            href="{{ route('ebooks.index') }}"
                            class="inline-block px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                        >
                            Kembali ke Semua Koleksi
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </main>
</div>
