<div class="min-h-screen bg-[#f8fafc] dark:bg-[#0b1329] text-[#0f172a] dark:text-[#f8fafc] pb-16 transition-colors duration-200">
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Subtle Silent Top Loading Indicator (No Text) --}}
    <div wire:loading class="fixed top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 via-sky-400 to-indigo-600 animate-pulse" style="z-index: 10000;"></div>

    {{-- Top Header (Solid White / Dark, High z-index, Synchronized with Koleksi) --}}
    <header
        class="border-b border-[#e2e8f0] dark:border-slate-800 shadow-xs bg-white dark:bg-[#0f172a] transition-colors duration-200"
        style="position: sticky; top: 0; z-index: 9999; isolation: isolate;"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Top Row: Brand Logo & Navigation --}}
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 select-none group">
                    <img src="{{ asset('assets/images/logo2.png') }}" alt="Lentera Sandi" class="h-10 sm:h-11 w-auto object-contain group-hover:opacity-90 transition-opacity">
                </a>
                <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold text-[#0f3b7a] dark:text-sky-300">
                    <a href="{{ route('home') }}" wire:navigate class="hover:text-[#0284c7] transition-colors">Beranda</a>
                    <span class="text-[#cbd5e1] dark:text-slate-700">•</span>
                    <a href="{{ route('ebooks.index') }}" wire:navigate class="hover:text-[#0284c7] transition-colors">Koleksi E-Book</a>
                    <button
                        type="button"
                        onclick="window.toggleTheme()"
                        class="p-2 rounded-xl border border-[#e2e8f0] dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-amber-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors cursor-pointer ml-1"
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
        </div>
    </header>

    {{-- Main Content Area --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Search & Sort Filter Bar --}}
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between mb-6 pb-3 border-b border-[#e2e8f0] dark:border-slate-800">
            {{-- Left: Search Input --}}
            <div class="flex items-center gap-3 flex-1 max-w-md">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#94a3b8] dark:text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="search"
                        placeholder="Cari judul, topik berita, kata kunci..."
                        class="w-full pl-9 pr-8 py-1.5 text-xs sm:text-sm bg-white dark:bg-slate-900 border border-[#cbd5e1] dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#38bdf8] focus:border-transparent text-[#0f172a] dark:text-slate-100 placeholder-[#94a3b8] dark:placeholder-slate-500 transition-colors"
                    />
                    @if(!empty($search))
                        <button
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#94a3b8] hover:text-[#ef4444] dark:hover:text-rose-400 cursor-pointer"
                            title="Hapus pencarian"
                        >
                            ✕
                        </button>
                    @endif
                </div>
            </div>

            {{-- Right: Status Count & Sort Dropdown --}}
            <div class="flex items-center justify-between sm:justify-end gap-3">
                <span class="text-xs text-[#64748b] dark:text-slate-400">
                    {{ $newsList->total() }} artikel
                </span>

                <select
                    wire:model.live="sortBy"
                    class="text-xs border border-[#cbd5e1] dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 px-2.5 py-1.5 text-[#334155] dark:text-slate-200 focus:outline-none focus:border-[#38bdf8] cursor-pointer shadow-xs transition-colors"
                >
                    <option value="Terbaru">Terbaru</option>
                    <option value="Terlama">Terlama</option>
                </select>
            </div>
        </div>

        {{-- ARTICLES GRID (Direct HTML Cards) --}}
        @if($newsList->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($newsList as $news)
                    @php
                        $thumbSrc = $news->gambar;
                        $summaryText = $news->ringkasan ?? strip_tags($news->konten);
                    @endphp
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e2e8f0] dark:border-slate-800 hover:border-[#cbd5e1] dark:hover:border-slate-700 hover:shadow-md hover:-translate-y-1 transition-all duration-200 group overflow-hidden flex flex-col h-full">
                        <a href="{{ route('informasi.show', $news->id) }}" wire:navigate.hover class="flex flex-col h-full cursor-pointer">
                            <div class="relative w-full aspect-video bg-[#0f172a] overflow-hidden border-b border-[#f1f5f9] dark:border-slate-800 flex items-center justify-center">
                                @if($thumbSrc)
                                    <img src="{{ $thumbSrc }}" alt="{{ $news->judul }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-[#f1f5f9] dark:bg-slate-800 text-[#94a3b8] dark:text-slate-400 p-4 text-center select-none">
                                        <svg class="w-10 h-10 stroke-current stroke-1 fill-none mb-1 text-[#94a3b8] dark:text-slate-400" viewBox="0 0 24 24">
                                            <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-[#64748b] dark:text-slate-400">Artikel</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-2.5 flex-wrap">
                                        @foreach($news->kategori_list as $kat)
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold border border-[#38bdf8] dark:border-sky-800 text-[#0284c7] dark:text-sky-300 bg-[#f0f9ff] dark:bg-sky-950/60">
                                                {{ $kat }}
                                            </span>
                                        @endforeach
                                        @if($news->tanggal_publish)
                                            <span class="text-xs text-[#cbd5e1] dark:text-slate-600">•</span>
                                            <span class="text-xs text-[#64748b] dark:text-slate-400 font-medium">{{ $news->tanggal_publish }}</span>
                                        @endif
                                    </div>
                                    <h3 class="font-bold text-base sm:text-lg text-[#0f3b7a] dark:text-slate-100 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-snug line-clamp-2 mb-2">
                                        {{ $news->judul }}
                                    </h3>
                                    @if($summaryText)
                                        <p class="text-xs sm:text-sm text-[#64748b] dark:text-slate-400 leading-relaxed line-clamp-2">
                                            {{ $summaryText }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-auto pt-3 border-t border-[#f1f5f9] dark:border-slate-800 flex items-center justify-between text-xs text-[#0284c7] dark:text-sky-400">
                                    <span class="font-semibold inline-flex items-center gap-1 group-hover:gap-1.5 transition-all">
                                        <span>Baca Selengkapnya</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                    @if($news->jumlah_dilihat)
                                        <span class="text-[#94a3b8] dark:text-slate-500 font-normal">👁 {{ $news->jumlah_dilihat }} dilihat</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-center py-4">
                {{ $newsList->links('vendor.pagination.simple-custom') }}
            </div>
        @else
            <div class="rounded-xl border border-[#e2e8f0] dark:border-slate-800 p-12 text-center my-6 bg-white dark:bg-slate-900 transition-colors">
                <span class="text-4xl text-[#94a3b8] dark:text-slate-500 block mb-2">📰</span>
                <h3 class="text-base font-semibold text-[#1e293b] dark:text-slate-200 mb-1">Tidak Ada Artikel Ditemukan</h3>
                <p class="text-xs text-[#64748b] dark:text-slate-400 mb-4">
                    @if(!empty($search))
                        Tidak ada materi atau berita yang cocok dengan kata kunci "<strong>{{ $search }}</strong>".
                    @else
                        Belum ada artikel yang dipublikasikan.
                    @endif
                </p>
                @if(!empty($search))
                    <button
                        wire:click="resetFilters"
                        class="inline-block px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                    >
                        Reset Filter
                    </button>
                @endif
            </div>
        @endif
    </main>

</div>
