<div class="min-h-screen bg-[#f8fafc] dark:bg-[#0b1329] text-[#0f172a] dark:text-[#f8fafc] pb-16 transition-colors duration-200">
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Subtle Silent Top Loading Indicator (No Text) --}}
    <div wire:loading class="fixed top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 via-sky-400 to-indigo-600 animate-pulse" style="z-index: 10000;"></div>

    {{-- Category Tabs Sub-Navigation Bar --}}
    <div class="sticky top-14 sm:top-16 z-30 bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-md border-b border-[#e2e8f0] dark:border-slate-800 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto no-scrollbar py-2 sm:py-2.5">
                <a
                    href="{{ route('ebooks.index') }}"
                    wire:navigate
                    class="py-1.5 px-3.5 text-xs sm:text-sm font-semibold rounded-lg whitespace-nowrap transition-all duration-150 cursor-pointer {{ $selectedCategory === 'Semua' ? 'bg-[#083575] text-white shadow-2xs' : 'text-[#64748b] dark:text-slate-400 hover:text-[#083575] dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                >
                    Semua Kategori
                </a>

                @foreach($navCategories as $cat)
                    @php
                        $catSlug = \Illuminate\Support\Str::slug($cat);
                        $isActive = ($selectedCategory === $cat);
                    @endphp
                    <a
                        href="{{ route('ebooks.category', $catSlug) }}"
                        wire:navigate
                        class="py-1.5 px-3.5 text-xs sm:text-sm font-semibold rounded-lg whitespace-nowrap transition-all duration-150 cursor-pointer {{ $isActive ? 'bg-[#083575] text-white shadow-2xs' : 'text-[#64748b] dark:text-slate-400 hover:text-[#083575] dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                    >
                        {{ $cat }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div>
            {{-- Toolbar: Status/Kategori & Sortir (Hanya tampilkan label kategori jika bukan 'Semua') --}}
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-[#e2e8f0] dark:border-slate-800">
                <div class="flex items-center gap-3">
                    @if($selectedCategory !== 'Semua')
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
                    @else
                        <div class="text-xs sm:text-sm font-semibold text-[#64748b] dark:text-slate-400">
                            Menampilkan seluruh koleksi e-book <span class="font-bold text-[#0f3b7a] dark:text-sky-300">({{ $ebooks->total() }})</span>
                        </div>
                    @endif
                </div>

                {{-- Sort Dropdown --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#64748b] dark:text-slate-400 hidden sm:inline">Urutkan:</span>
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
                                <a href="{{ $detailUrl }}" wire:navigate class="block w-full h-full relative cursor-pointer">
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

                            <div class="mt-2 flex flex-col flex-1 justify-between">
                                <a href="{{ $detailUrl }}" wire:navigate class="font-semibold text-xs sm:text-[13px] text-[#1e293b] dark:text-slate-200 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-tight line-clamp-2" title="{{ $book->judul }}">
                                    {{ $book->judul }}
                                </a>
                                <div class="flex items-center gap-1 mt-1.5 text-[11px] text-[#94a3b8] dark:text-slate-500 font-normal">
                                    <span>👁 {{ number_format($book->jumlah_dibaca ?? 0) }} dilihat</span>
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
                    <span class="text-4xl text-[#94a3b8] dark:text-slate-500 block mb-2">📚</span>
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
    </div>
</div>
