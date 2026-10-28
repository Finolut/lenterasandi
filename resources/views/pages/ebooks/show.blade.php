@php
    $id = request()->route('id');
    $ebook = \App\Models\Ebook::where('status', 'Publik')->findOrFail($id);

    $coverSrc = $ebook->cover_image;
    $pdfSrc   = $ebook->file_url ? Storage::disk('public')->url($ebook->file_url) : null;

    // E-Book Terkait (Maksimal 6 item)
    $relatedEbooks = \App\Models\Ebook::where('status', 'Publik')
        ->where('id', '!=', $ebook->id)
        ->where('kategori', $ebook->kategori)
        ->latest()
        ->take(6)
        ->get();

    if ($relatedEbooks->count() < 6) {
        $needed = 6 - $relatedEbooks->count();
        $excludeIds = $relatedEbooks->pluck('id')->push($ebook->id)->all();
        $moreEbooks = \App\Models\Ebook::where('status', 'Publik')
            ->whereNotIn('id', $excludeIds)
            ->latest()
            ->take($needed)
            ->get();
        $relatedEbooks = $relatedEbooks->concat($moreEbooks);
    }
@endphp

<x-layouts.app active="Koleksi E-Book" title="{{ $ebook->judul }} - Lentera Sandi">
    <div class="w-full bg-[#f8fafc] dark:bg-[#0b1329] text-[#0f172a] dark:text-[#f8fafc] pb-20 transition-colors duration-200">
        {{-- Main Detail Container --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- Main Book Detail Presentation Layout (Flex 2-column on desktop) --}}
            <div class="flex flex-col md:flex-row gap-8 lg:gap-12 items-start mb-14">
                {{-- Left Column: 3D Book Cover & Primary Actions (Fixed width container) --}}
                <div class="w-full md:w-[280px] lg:w-[310px] shrink-0 flex flex-col items-center md:items-stretch">
                    {{-- Realistic Book Cover Card --}}
                    <div class="w-full max-w-[280px] md:max-w-none bg-white dark:bg-slate-900 p-3 rounded-2xl border border-[#e2e8f0] dark:border-slate-800 shadow-sm mb-5 transition-colors duration-200">
                        <div
                            class="relative w-full rounded-xl overflow-hidden shadow-md bg-[#f8fafc] dark:bg-slate-950/60 border border-[#cbd5e1] dark:border-slate-700 group"
                            style="aspect-ratio: 2/3; height: 380px; max-height: 420px;"
                        >
                            @if($coverSrc)
                                <img
                                    src="{{ $coverSrc }}"
                                    alt="{{ $ebook->judul }}"
                                    class="w-full h-full object-cover object-top"
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                >
                            @elseif($pdfSrc)
                                <div
                                    class="pdf-cover-wrap relative w-full h-full bg-[#f8fafc] overflow-hidden flex items-center justify-center"
                                    data-pdf-url="{{ $pdfSrc }}"
                                    style="width: 100%; height: 100%; overflow: hidden;"
                                    x-data
                                    x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })"
                                >
                                    <div class="pdf-skeleton absolute inset-0 bg-[#e2e8f0] animate-pulse flex flex-col items-center justify-center text-[#94a3b8] gap-2 p-4">
                                        <svg class="w-8 h-8 animate-spin text-[#0284c7]" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-xs font-mono text-[#64748b]">Memuat sampul PDF...</span>
                                    </div>
                                    <img
                                        class="pdf-cover-img hidden"
                                        alt="{{ $ebook->judul }}"
                                        style="width: 100%; height: 100%; object-fit: cover; object-position: top;"
                                    >
                                </div>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-[#f1f5f9] text-[#94a3b8] p-6 text-center">
                                    <svg class="w-16 h-16 mb-2 stroke-current stroke-1 fill-none text-[#94a3b8]" viewBox="0 0 24 24">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-[#64748b]">E-Book Resmi</span>
                                </div>
                            @endif

                            {{-- Star Recommendation Badge --}}
                            <div class="absolute top-3 right-3 w-7 h-7 rounded-full bg-black/85 flex items-center justify-center text-[#facc15] shadow-md pointer-events-none" style="z-index: 10;" title="Rekomendasi">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>

                            {{-- E-Book Format Badge --}}
                            <div class="absolute bottom-3 left-3 px-2.5 py-1 rounded-md bg-[#083575]/90 backdrop-blur-xs text-white text-[10px] font-bold flex items-center gap-1.5 shadow-sm pointer-events-none" style="z-index: 10;">
                                <svg class="w-3 h-3 text-[#38bdf8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>PDF E-Book</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons Call-to-Action --}}
                    <div class="w-full max-w-[280px] md:max-w-none space-y-3">
                        @if($ebook->file_url)
                            {{-- Baca Online Primary Button --}}
                            <a
                                href="{{ route('ebooks.read', $ebook->id) }}"
                                target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-bold text-white bg-[#0284c7] hover:bg-[#0369a1] shadow-sm hover:shadow-md transition-all duration-200 text-sm group"
                            >
                                <svg class="w-4 h-4 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>Baca Online</span>
                                <span class="text-[11px] bg-white/20 px-1.5 py-0.5 rounded text-white font-medium ml-1">Viewer</span>
                            </a>

                            {{-- Unduh PDF Secondary Button --}}
                            <a
                                href="{{ route('ebooks.download', $ebook->id) }}"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-[#0f172a] dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-[#f8fafc] dark:hover:bg-slate-700 border border-[#cbd5e1] dark:border-slate-700 hover:border-[#94a3b8] shadow-2xs hover:shadow-xs transition-all duration-200 text-sm group"
                            >
                                <svg class="w-4 h-4 text-[#0284c7] dark:text-sky-400 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>Unduh PDF</span>
                                @if($ebook->ukuran_file)
                                    <span class="text-xs text-[#64748b] dark:text-slate-400 font-normal">({{ $ebook->ukuran_format }})</span>
                                @endif
                            </a>
                        @else
                            <div class="w-full text-center py-3 px-4 rounded-xl bg-[#f1f5f9] dark:bg-slate-800 text-[#94a3b8] dark:text-slate-400 text-xs font-semibold border border-[#e2e8f0] dark:border-slate-700">
                                Berkas digital sedang tidak tersedia
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Right Column: Information, Synopsis & Metadata (Fluid flex-1) --}}
                <div class="flex-1 min-w-0 space-y-6">
                    {{-- Header Section: Metadata Bar --}}
                    <div>
                        <h1 class="sr-only">{{ $ebook->judul }}</h1>

                        {{-- Author, Publisher & Date Metadata Bar --}}
                        <div class="flex flex-wrap items-center gap-y-2 gap-x-6 pb-3 border-b border-[#e2e8f0] dark:border-slate-800 text-xs text-[#475569] dark:text-slate-300">
                            <div class="flex items-center gap-2">
                                <span class="text-[#94a3b8] dark:text-slate-400">Penulis:</span>
                                <span class="font-bold text-[#0f172a] dark:text-slate-100">{{ $ebook->pengarang ?? 'Diskominfo Provinsi Jawa Timur' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[#94a3b8] dark:text-slate-400">Penerbit:</span>
                                <span class="font-bold text-[#0f172a] dark:text-slate-100">{{ $ebook->penerbit ?? 'Diskominfo Jatim' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[#64748b] dark:text-slate-400">
                                <svg class="w-3.5 h-3.5 text-[#94a3b8] dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Tahun Terbit {{ $ebook->tahun ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Synopsis Card --}}
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e2e8f0] dark:border-slate-800 p-6 sm:p-7 shadow-xs transition-colors duration-200">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-[#f1f5f9] dark:border-slate-800">
                            <svg class="w-4 h-4 text-[#0284c7] dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-[#0f3b7a] dark:text-sky-300">Sinopsis &amp; Deskripsi</h2>
                        </div>

                        <div class="text-[#334155] dark:text-slate-300 text-sm sm:text-base leading-relaxed space-y-3">
                            <style>
                                .ebook-synopsis-prose h2 { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-top: 1rem; margin-bottom: 0.4rem; }
                                .ebook-synopsis-prose h3 { font-size: 1.15rem; font-weight: 600; color: #1e293b; margin-top: 0.85rem; margin-bottom: 0.35rem; }
                                .ebook-synopsis-prose h4 { font-size: 1rem; font-weight: 600; color: #334155; margin-top: 0.75rem; margin-bottom: 0.3rem; }
                                .ebook-synopsis-prose p { margin-bottom: 0.75rem; line-height: 1.7; color: #334155; }
                                .ebook-synopsis-prose ul { list-style-type: disc; padding-left: 1.4rem; margin-bottom: 0.75rem; }
                                .ebook-synopsis-prose ol { list-style-type: decimal; padding-left: 1.4rem; margin-bottom: 0.75rem; }
                                .ebook-synopsis-prose li { margin-bottom: 0.25rem; }
                                .ebook-synopsis-prose blockquote { border-left: 3px solid #0284c7; padding-left: 0.75rem; font-style: italic; color: #475569; margin: 0.75rem 0; background: #f8fafc; padding-top: 0.25rem; padding-bottom: 0.25rem; }
                                .ebook-synopsis-prose b, .ebook-synopsis-prose strong { font-weight: 700; color: #0f172a; }
                                .dark .ebook-synopsis-prose h2 { color: #f1f5f9; }
                                .dark .ebook-synopsis-prose h3 { color: #e2e8f0; }
                                .dark .ebook-synopsis-prose h4 { color: #cbd5e1; }
                                .dark .ebook-synopsis-prose p { color: #cbd5e1; }
                                .dark .ebook-synopsis-prose blockquote { color: #94a3b8; background: #1e293b; border-color: #38bdf8; }
                                .dark .ebook-synopsis-prose b, .dark .ebook-synopsis-prose strong { color: #f8fafc; }
                                .ebook-synopsis-prose u { text-decoration: underline; }
                                .ebook-synopsis-prose i, .ebook-synopsis-prose em { font-style: italic; }
                            </style>
                            @if($ebook->deskripsi)
                                @if(strip_tags($ebook->deskripsi) !== $ebook->deskripsi)
                                    <div class="ebook-synopsis-prose">
                                        {!! $ebook->deskripsi !!}
                                    </div>
                                @else
                                    @php
                                        $paragraphs = preg_split('/(\r\n|\n|\r)/', $ebook->deskripsi);
                                    @endphp
                                    @foreach($paragraphs as $para)
                                        @php $trimmed = trim($para); @endphp
                                        @if(!empty($trimmed))
                                            <p class="leading-[1.7]">{{ $trimmed }}</p>
                                        @endif
                                    @endforeach
                                @endif
                            @else
                                <p class="text-[#94a3b8] dark:text-slate-400 italic">Belum ada ringkasan atau sinopsis resmi untuk materi e-book ini.</p>
                            @endif
                        </div>
                    </div>


                </div>
            </div>

            {{-- Related E-Books Shelf Section (Synchronized with /koleksi Shelf Style) --}}
            @if($relatedEbooks->count() > 0)
                <section class="mt-14 pt-8 border-t border-[#e2e8f0] dark:border-slate-800">
                    <div class="flex items-center justify-between mb-6 pb-2 border-b border-[#e2e8f0] dark:border-slate-800">
                        <h2 class="text-lg sm:text-xl font-bold text-[#0f3b7a] dark:text-sky-300 flex items-center gap-2">
                            <span>E-Book Terkait Lainnya</span>
                            <span class="text-xs font-normal text-[#64748b] dark:text-slate-400">({{ $relatedEbooks->count() }})</span>
                        </h2>

                        <a
                            href="{{ route('ebooks.index') }}"
                            class="text-xs sm:text-sm font-semibold text-[#0284c7] dark:text-sky-400 hover:text-[#0369a1] dark:hover:text-sky-300 hover:underline flex items-center gap-1 cursor-pointer transition-colors"
                        >
                            <span>Lihat Semua Koleksi</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Responsive Shelf: Exactly 6 items in 1 row, compact & tidy --}}
                    <div class="overflow-x-auto pb-3 -mx-4 px-4 sm:mx-0 sm:px-0">
                        <div style="display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 16px; min-width: 760px;">
                            @foreach($relatedEbooks as $relBook)
                                @php
                                    $relCover = $relBook->cover_image;
                                    $relPdf   = $relBook->file_url ? Storage::disk('public')->url($relBook->file_url) : null;
                                    $relDetailUrl = route('ebooks.show', $relBook->id);
                                @endphp
                                <div
                                    x-data
                                    x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })"
                                    class="group flex flex-col select-none w-full"
                                >
                                    <div
                                        class="relative w-full bg-[#f8fafc] dark:bg-slate-900 rounded-lg shadow-xs group-hover:shadow-md group-hover:-translate-y-1 transition-all duration-200 border border-[#e2e8f0] dark:border-slate-800 overflow-hidden"
                                        style="aspect-ratio: 2/3;"
                                    >
                                        <a href="{{ $relDetailUrl }}" wire:navigate.hover class="block w-full h-full relative cursor-pointer">
                                            @if($relCover)
                                                <img src="{{ $relCover }}" alt="{{ $relBook->judul }}" class="w-full h-full object-cover object-top" loading="lazy">
                                            @elseif($relPdf)
                                                <div class="pdf-cover-wrap relative w-full h-full bg-[#f8fafc] dark:bg-slate-900 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $relPdf }}">
                                                    <div class="pdf-skeleton absolute inset-0 bg-[#e2e8f0] dark:bg-slate-800 animate-pulse flex flex-col items-center justify-center text-[#94a3b8] dark:text-slate-400 gap-1 p-2">
                                                        <svg class="w-5 h-5 animate-spin text-[#64748b] dark:text-slate-400" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <span class="text-[9px] font-mono text-[#64748b] dark:text-slate-400">Memuat...</span>
                                                    </div>
                                                    <img class="pdf-cover-img hidden w-full h-full object-cover object-top" alt="{{ $relBook->judul }}">
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
                                        <a href="{{ $relDetailUrl }}" wire:navigate.hover class="font-semibold text-xs sm:text-[13px] text-[#1e293b] dark:text-slate-200 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-tight line-clamp-2 min-h-[2.25rem]" title="{{ $relBook->judul }}">
                                            {{ $relBook->judul }}
                                        </a>
                                        <div class="mt-1 flex items-center justify-between pt-0.5 text-[#94a3b8] dark:text-slate-500">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#94a3b8] dark:text-slate-400">BOOK</span>
                                            <a href="{{ $relDetailUrl }}" wire:navigate.hover class="w-4 h-4 rounded-full border border-[#cbd5e1] dark:border-slate-700 hover:border-[#38bdf8] hover:text-[#0284c7] text-[#94a3b8] dark:text-slate-400 flex items-center justify-center text-[10px] font-bold font-serif transition-colors">i</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-layouts.app>
