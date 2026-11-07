@php
    use App\Models\Ebook;
    use App\Models\Informasi;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    
    $totalEbooks = Cache::remember('home_total_ebooks', 60, function () {
        return Ebook::where('status', 'Publik')->count();
    });

    $heroEbook = Cache::remember('home_hero_ebook', now()->addMinutes(10), function () {
        return Ebook::where('status', 'Publik')
            ->latest()
            ->first();
    });

    $featuredEbooks = Cache::remember('home_featured_ebooks', now()->addMinutes(10), fn () => Ebook::where('status', 'Publik')
        ->select(['id', 'judul', 'kategori', 'file_url', 'cover_url', 'created_at'])
        ->latest()->take(6)->get());
    $recentNews = Cache::remember('home_recent_news', now()->addMinutes(10), fn () => Informasi::where('status', 'Publik')
        ->select(['id', 'judul', 'ringkasan', 'gambar_url', 'kategori', 'published_at'])
        ->latest('published_at')->take(3)->get());
@endphp
<x-layouts.app active="Beranda">
    {{-- HERO SECTION: Featured E-Book 2-Kolom --}}
    <section id="beranda" class="relative w-full bg-gradient-to-b from-[#EFF6FF] via-[#F8FAFC] to-white dark:from-[#09152e] dark:via-[#070e1e] dark:to-[#070e1e] border-b border-[#E6ECF5] dark:border-slate-800 scroll-mt-24 md:scroll-mt-28 overflow-hidden transition-colors duration-200">
        
        {{-- Soft Decorative Background Glow --}}
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#BFDBFE]/30 dark:bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/4 -right-32 w-96 h-96 bg-[#93C5FD]/20 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-[#BFDBFE] dark:via-sky-900 to-transparent"></div>

        <div class="max-w-6xl mx-auto px-6 pt-28 sm:pt-36 md:pt-40 pb-16 md:pb-20 relative z-10">
            @if($heroEbook)
                @php
                    $coverSrc = $heroEbook->cover_image;
                    $pdfSrc   = $heroEbook->file_url
                        ? Storage::disk('public')->url($heroEbook->file_url)
                        : null;
                    $cleanDeskripsi = strip_tags($heroEbook->deskripsi ?? '');
                    $sinopsis = !empty(trim($cleanDeskripsi))
                        ? Str::limit(trim($cleanDeskripsi), 240)
                        : 'Buku panduan resmi dan referensi terpercaya dalam meningkatkan literasi persandian serta ketahanan keamanan informasi digital bagi ASN dan masyarakat Jawa Timur.';
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 xl:gap-16 items-center">
                    
                    {{-- KOLOM KIRI: Cover E-Book Rekomendasi Vertikal --}}
                    <div class="lg:col-span-5 flex flex-col items-center justify-center reveal-on-scroll">
                        
                        {{-- Badge Pill di atas cover --}}
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-amber-500/15 via-orange-500/10 to-amber-500/15 border border-amber-300 dark:border-amber-600/60 text-amber-700 dark:text-amber-300 text-xs sm:text-sm font-bold shadow-xs mb-5">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            <span>⭐ Rekomendasi Minggu Ini</span>
                        </div>

                        {{-- Card Cover Vertikal dengan Soft 3D Drop-Shadow & Rounded-xl --}}
                        <div
                            x-data
                            x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })"
                            class="relative group"
                        >
                            {{-- Soft 3D Glow / Shadow Layer --}}
                            <div class="absolute -inset-2 bg-gradient-to-tr from-[#083575]/20 via-[#1D68BD]/15 to-sky-400/25 rounded-2xl blur-xl opacity-70 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                            <a href="{{ route('ebooks.show', $heroEbook->id) }}" wire:navigate.hover class="block relative">
                                <div class="w-[220px] sm:w-[260px] md:w-[290px] lg:w-[310px] aspect-[2/3] bg-white dark:bg-slate-900 rounded-xl overflow-hidden shadow-[0_20px_50px_rgba(8,53,117,0.22)] dark:shadow-[0_25px_60px_rgba(0,0,0,0.65)] ring-1 ring-black/5 dark:ring-white/10 group-hover:scale-[1.02] group-hover:-translate-y-1.5 transition-all duration-300 flex items-center justify-center">
                                    
                                    @if($coverSrc)
                                        <img src="{{ $coverSrc }}" alt="{{ $heroEbook->judul }}" class="w-full h-full object-cover object-top select-none" loading="eager" draggable="false">
                                    @elseif($pdfSrc)
                                        <div class="pdf-cover-wrap relative w-full h-full bg-[#f8fafc] dark:bg-slate-950/60 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                                            <div class="pdf-skeleton absolute inset-0 bg-[#e2e8f0] dark:bg-slate-800 animate-pulse flex flex-col items-center justify-center text-[#94a3b8] dark:text-slate-400 gap-2 p-4">
                                                <svg class="w-8 h-8 animate-spin text-[#64748b] dark:text-slate-400" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-xs font-mono text-[#64748b] dark:text-slate-400">Memuat cover...</span>
                                            </div>
                                            <img class="pdf-cover-img w-full h-full object-cover object-top hidden select-none" alt="{{ $heroEbook->judul }}" draggable="false">
                                        </div>
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#EFF6FF] to-[#DBEAFE] dark:from-slate-800 dark:to-slate-900 text-[#94a3b8] dark:text-slate-400 p-6 text-center select-none">
                                            <svg class="w-16 h-16 mb-3 text-[#BFDBFE] dark:text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6H6zm7 1.5L18.5 9H13V3.5zM8 13h8v1.5H8V13zm0 3h5v1.5H8V16z"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-[#083575] dark:text-sky-300">{{ $heroEbook->judul }}</span>
                                        </div>
                                    @endif

                                    {{-- Realistic subtle spine shadow on left edge of book --}}
                                    <div class="absolute left-0 inset-y-0 w-3 bg-gradient-to-r from-black/25 via-black/10 to-transparent pointer-events-none"></div>
                                </div>
                            </a>

                            {{-- Label klik untuk detail --}}
                            <p class="text-center text-xs text-[#64748B] dark:text-slate-400 mt-3 font-medium">Klik cover untuk melihat ringkasan lengkap</p>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Tag Kategori, Judul H1, Sinopsis, Tombol Aksi, & Search Bar Modern --}}
                    <div class="lg:col-span-7 flex flex-col text-left reveal-on-scroll reveal-delay-1">
                        
                        {{-- Tag Kategori & Metadata --}}
                        <div class="flex items-center gap-2.5 flex-wrap mb-3.5">
                            @if(!empty($heroEbook->kategori))
                                @foreach($heroEbook->kategori_list as $kat)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#EFF6FF] dark:bg-sky-950/70 text-[#1D68BD] dark:text-sky-300 border border-[#BFDBFE] dark:border-sky-800">
                                        {{ $kat }}
                                    </span>
                                @endforeach
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#EFF6FF] dark:bg-sky-950/70 text-[#1D68BD] dark:text-sky-300 border border-[#BFDBFE] dark:border-sky-800">
                                    Keamanan Informasi
                                </span>
                            @endif

                            @if($heroEbook->tahun)
                                <span class="text-xs text-[#94A3B8] dark:text-slate-500">•</span>
                                <span class="text-xs sm:text-sm font-medium text-[#64748B] dark:text-slate-400">Tahun {{ $heroEbook->tahun }}</span>
                            @endif
                        </div>

                        {{-- Judul Buku Tebal (H1) --}}
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#083575] dark:text-white tracking-tight leading-[1.18] mb-4">
                            <a href="{{ route('ebooks.show', $heroEbook->id) }}" wire:navigate.hover class="hover:text-[#1D68BD] dark:hover:text-sky-300 transition-colors">
                                {{ $heroEbook->judul }}
                            </a>
                        </h1>

                        {{-- Penulis / Penerbit jika ada --}}
                        @if($heroEbook->pengarang || $heroEbook->penerbit)
                            <p class="text-xs sm:text-sm text-[#64748B] dark:text-slate-400 mb-3.5 font-medium">
                                @if($heroEbook->pengarang)
                                    <span>Penulis: <strong class="text-[#083575] dark:text-slate-200">{{ $heroEbook->pengarang }}</strong></span>
                                @endif
                                @if($heroEbook->pengarang && $heroEbook->penerbit) • @endif
                                @if($heroEbook->penerbit)
                                    <span>Penerbit: <strong class="text-[#083575] dark:text-slate-200">{{ $heroEbook->penerbit }}</strong></span>
                                @endif
                            </p>
                        @endif

                        {{-- Sinopsis E-Book --}}
                        <p class="text-base sm:text-lg text-[#5A6A85] dark:text-slate-300 leading-relaxed mb-6 sm:mb-8 line-clamp-4">
                            {{ $sinopsis }}
                        </p>

                        {{-- 2 Tombol Aksi: 'Baca Sekarang' & 'Unduh' --}}
                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 mb-8 sm:mb-10">
                            {{-- Tombol Baca Sekarang --}}
                            <a
                                href="{{ route('ebooks.read', $heroEbook->id) }}"
                                target="_blank"
                                class="inline-flex items-center justify-center gap-2.5 px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl font-semibold text-white bg-[#083575] hover:bg-[#0b489a] active:scale-[0.98] shadow-sm hover:shadow-md transition-all duration-200 text-sm sm:text-base group"
                            >
                                <svg class="w-5 h-5 text-sky-200 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>Baca Sekarang</span>
                            </a>

                            {{-- Tombol Unduh --}}
                            <a
                                href="{{ route('ebooks.download', $heroEbook->id) }}"
                                class="inline-flex items-center justify-center gap-2.5 px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl font-semibold text-[#083575] dark:text-sky-300 bg-white dark:bg-slate-800 hover:bg-[#F8FAFC] dark:hover:bg-slate-700/80 border border-[#D0D5DD] dark:border-slate-700 active:scale-[0.98] shadow-xs hover:shadow-sm transition-all duration-200 text-sm sm:text-base group"
                            >
                                <svg class="w-5 h-5 text-[#1D68BD] dark:text-sky-400 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Unduh</span>
                                @if($heroEbook->ukuran_format && $heroEbook->ukuran_format !== '—')
                                    <span class="text-xs font-normal text-[#64748B] dark:text-slate-400">({{ $heroEbook->ukuran_format }})</span>
                                @endif
                            </a>
                        </div>

                        {{-- Search Bar Modern di Bawahnya --}}
                        <div class="pt-6 border-t border-[#E6ECF5] dark:border-slate-800">
                            <form action="{{ route('ebooks.index') }}" method="GET" class="relative max-w-xl group">
                                <div class="relative flex items-center">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#94A3B8] dark:text-slate-400 group-focus-within:text-[#1D68BD] dark:group-focus-within:text-sky-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="search"
                                        placeholder="Cari judul e-book atau topik lainnya..."
                                        class="w-full pl-11 pr-28 py-3.5 bg-white dark:bg-slate-800/90 text-sm sm:text-base text-[#0F172A] dark:text-white rounded-xl border border-[#D0D5DD] dark:border-slate-700 shadow-xs focus:ring-2 focus:ring-[#1D68BD] focus:border-[#1D68BD] dark:focus:ring-sky-500 placeholder-[#94A3B8] dark:placeholder-slate-400 transition-all outline-none"
                                    >
                                    <button
                                        type="submit"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 px-4 sm:px-5 py-2 bg-[#1D68BD] hover:bg-[#083575] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition-colors duration-200"
                                    >
                                        Cari
                                    </button>
                                </div>
                            </form>
                            
                            {{-- Topik Populer / Quick Links --}}
                            <div class="flex items-center gap-2 mt-3 text-xs text-[#64748B] dark:text-slate-400 flex-wrap">
                                <span class="font-medium">Topik Populer:</span>
                                <a href="{{ route('ebooks.index', ['search' => 'Keamanan Informasi']) }}" class="hover:text-[#1D68BD] dark:hover:text-sky-300 underline underline-offset-2">Keamanan Informasi</a>
                                <span>•</span>
                                <a href="{{ route('ebooks.index', ['search' => 'Siber']) }}" class="hover:text-[#1D68BD] dark:hover:text-sky-300 underline underline-offset-2">Siber</a>
                                <span>•</span>
                                <a href="{{ route('ebooks.index', ['search' => 'Modul']) }}" class="hover:text-[#1D68BD] dark:hover:text-sky-300 underline underline-offset-2">Modul</a>
                                <span>•</span>
                                <a href="{{ route('ebooks.index') }}" wire:navigate.hover class="hover:text-[#1D68BD] dark:hover:text-sky-300 underline underline-offset-2">Lihat Semua →</a>
                            </div>
                        </div>

                    </div>

                </div>
            @else
                {{-- Fallback jika belum ada e-book di database --}}
                <div class="text-center py-16">
                    <h1 class="text-3xl sm:text-4xl font-bold text-[#083575] dark:text-sky-300 mb-3">Portal E-Book Lentera Sandi</h1>
                    <p class="text-sm text-[#5A6A85] dark:text-slate-400 max-w-lg mx-auto mb-6">Belum ada e-book yang dipublikasikan saat ini. Kunjungi kembali dalam beberapa saat.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- SECTION: KOLEKSI E-BOOK (Horizontal Scrollable Carousel) --}}
    <section id="ebook" class="bg-[#F8FAFC] dark:bg-[#070e1e] border-b border-[#E6ECF5] dark:border-slate-800 py-10 md:py-16 scroll-mt-24 md:scroll-mt-28 relative transition-colors duration-200">
        <div id="koleksi" class="absolute -top-24"></div>
        <div class="max-w-6xl mx-auto px-6">

            {{-- Header Row --}}
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 reveal-on-scroll">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#083575] dark:text-sky-300">Koleksi E-Book Terbaru</h2>
                    <p class="text-xs sm:text-sm text-[#5A6A85] dark:text-slate-400 mt-1">Buku panduan resmi, standar keamanan, dan materi literasi persandian Diskominfo Jatim.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    {{-- Nav Arrows --}}
                    @if($featuredEbooks->count() > 0)
                        <div class="flex items-center gap-1.5" id="ebook-carousel-controls">
                            <button id="ebook-prev"
                                aria-label="Sebelumnya"
                                class="w-9 h-9 rounded-full bg-white dark:bg-slate-800 border border-[#e2e8f0] dark:border-slate-700 shadow-xs flex items-center justify-center text-[#083575] dark:text-sky-400 hover:bg-[#EFF6FF] dark:hover:bg-slate-700 hover:border-[#BFDBFE] dark:hover:border-sky-700 transition-all duration-150 disabled:opacity-30 disabled:cursor-not-allowed"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button id="ebook-next"
                                aria-label="Selanjutnya"
                                class="w-9 h-9 rounded-full bg-white dark:bg-slate-800 border border-[#e2e8f0] dark:border-slate-700 shadow-xs flex items-center justify-center text-[#083575] dark:text-sky-400 hover:bg-[#EFF6FF] dark:hover:bg-slate-700 hover:border-[#BFDBFE] dark:hover:border-sky-700 transition-all duration-150 disabled:opacity-30 disabled:cursor-not-allowed"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                    <a href="{{ route('ebooks.index') }}" wire:navigate.hover class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1D68BD] dark:text-sky-400 hover:text-[#083575] dark:hover:text-sky-300 group">
                        <span>Semua E-Book</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>
            </div>

            @if($featuredEbooks->count() > 0)
                {{-- Carousel Wrapper --}}
                <div class="relative reveal-on-scroll reveal-delay-1">

                    {{-- Fade edges --}}
                    <div class="absolute left-0 top-0 bottom-4 w-10 bg-gradient-to-r from-[#F8FAFC] dark:from-[#070e1e] to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute right-0 top-0 bottom-4 w-10 bg-gradient-to-l from-[#F8FAFC] dark:from-[#070e1e] to-transparent z-10 pointer-events-none"></div>

                    {{-- Outer: overflow-hidden (track pakai transform, bukan scroll) --}}
                    <div id="ebook-outer" class="overflow-hidden pb-4 select-none" style="cursor: grab;">
                        {{-- Track: transform-based infinite loop --}}
                        <div id="ebook-track" class="flex gap-4 sm:gap-5" style="width: max-content; will-change: transform;">
                            {{-- Cards dirender dua kali: original + clone --}}
                            @foreach([...$featuredEbooks, ...$featuredEbooks] as $book)
                                @php
                                    $coverSrc = $book->cover_image;
                                    $pdfSrc   = $book->file_url
                                        ? Storage::disk('public')->url($book->file_url)
                                        : null;
                                @endphp
                                <div
                                    x-data
                                    x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })"
                                    class="ebook-card flex-none w-[160px] sm:w-[185px] md:w-[195px] bg-white dark:bg-slate-900 rounded-xl border border-[#e5e7eb] dark:border-slate-800 hover:border-[#BFDBFE] dark:hover:border-sky-700 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-200 group overflow-hidden flex flex-col"
                                >
                                    <a href="{{ route('ebooks.show', $book->id) }}" wire:navigate.hover draggable="false" ondragstart="return false;" class="ebook-card-link flex flex-col h-full select-none">
                                        {{-- Cover --}}
                                        <div class="relative w-full bg-[#f8fafc] dark:bg-slate-950/60 overflow-hidden border-b border-[#f1f5f9] dark:border-slate-800 flex items-center justify-center rounded-t-xl" style="aspect-ratio: 2/3;">
                                            @if($coverSrc)
                                                <img src="{{ $coverSrc }}" alt="{{ $book->judul }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300 pointer-events-none select-none" loading="lazy" draggable="false">
                                            @elseif($pdfSrc)
                                                <div class="pdf-cover-wrap relative w-full h-full bg-[#f8fafc] dark:bg-slate-950/60 overflow-hidden flex items-center justify-center pointer-events-none" data-pdf-url="{{ $pdfSrc }}">
                                                    <div class="pdf-skeleton absolute inset-0 bg-[#e2e8f0] dark:bg-slate-800 animate-pulse flex flex-col items-center justify-center text-[#94a3b8] dark:text-slate-400 gap-2 p-3">
                                                        <svg class="w-6 h-6 animate-spin text-[#64748b] dark:text-slate-400" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <span class="text-[10px] font-mono text-[#64748b] dark:text-slate-400">Memuat...</span>
                                                    </div>
                                                    <img class="pdf-cover-img w-full h-full object-cover object-top hidden group-hover:scale-105 transition-transform duration-300 pointer-events-none" alt="{{ $book->judul }}" draggable="false">
                                                </div>
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#EFF6FF] to-[#DBEAFE] dark:from-slate-800 dark:to-slate-900 text-[#94a3b8] dark:text-slate-400 p-4 pointer-events-none">
                                                    <svg class="w-12 h-12 text-[#BFDBFE] dark:text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6H6zm7 1.5L18.5 9H13V3.5zM8 13h8v1.5H8V13zm0 3h5v1.5H8V16z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Judul --}}
                                        <div class="p-3.5 flex flex-col flex-1 pointer-events-none">
                                            <h3 class="font-bold text-[13px] text-[#0f3b7a] dark:text-slate-100 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-snug line-clamp-3">
                                                {{ $book->judul }}
                                            </h3>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <style>
                    #ebook-outer, #ebook-track, .ebook-card, .ebook-card-link, .ebook-card-link * {
                        -webkit-user-drag: none;
                        -khtml-user-drag: none;
                        -moz-user-drag: none;
                        -o-user-drag: none;
                        user-drag: none;
                    }
                </style>

                {{-- Carousel JS: smooth rAF infinite loop + drag tanpa salah klik --}}
                <script>
                (function () {
                    function setupCarousel() {
                        const outer   = document.getElementById('ebook-outer');
                        const track   = document.getElementById('ebook-track');
                        const prevBtn = document.getElementById('ebook-prev');
                        const nextBtn = document.getElementById('ebook-next');

                        if (!outer || !track) return;
                        if (outer.dataset.carouselActive === 'true') return;
                        outer.dataset.carouselActive = 'true';

                        const SPEED = 0.55;          // px per frame (~33px/s pada 60fps)
                        const GAP   = window.innerWidth >= 640 ? 20 : 16;
                        const DRAG_THRESHOLD = 5;    // px pergeseran untuk membedakan klik vs drag

                        let offset                = 0;  // translateX saat ini (px, selalu ≤ 0)
                        let halfW                 = 0;  // lebar satu set card
                        let isPaused              = false;
                        let isDragging            = false;
                        let hasDragged            = false;
                        let dragStartX            = 0;
                        let dragStartY            = 0;
                        let dragStartOff          = 0;
                        let dragPreventClickUntil = 0;

                        function getHalfW() {
                            return track.scrollWidth / 2;
                        }

                        function wrapOffset(val) {
                            const hw = getHalfW();
                            if (val <= -hw) val += hw;
                            if (val > 0)    val -= hw;
                            return val;
                        }

                        function applyTransform() {
                            track.style.transform = 'translateX(' + offset + 'px)';
                        }

                        // Cegah browser native drag pada link & gambar
                        outer.addEventListener('dragstart', e => {
                            e.preventDefault();
                            return false;
                        });

                        // Loop animasi rAF
                        function loop() {
                            if (!isPaused && !isDragging) {
                                offset -= SPEED;
                                offset  = wrapOffset(offset);
                                applyTransform();
                            }
                            requestAnimationFrame(loop);
                        }

                        requestAnimationFrame(() => {
                            halfW  = getHalfW();
                            offset = 0;
                            loop();
                        });

                        // Pause saat hover mouse di atas carousel
                        outer.addEventListener('mouseenter', () => {
                            if (!isDragging) isPaused = true;
                        });
                        outer.addEventListener('mouseleave', () => {
                            if (!isDragging) isPaused = false;
                        });

                        // Tombol prev / next
                        const cardStep = () => (track.querySelector('.ebook-card')?.offsetWidth || 185) + GAP;

                        if (prevBtn) prevBtn.addEventListener('click', () => {
                            isPaused = true;
                            offset = wrapOffset(offset + cardStep());
                            applyTransform();
                            setTimeout(() => { if (!isDragging) isPaused = false; }, 600);
                        });
                        if (nextBtn) nextBtn.addEventListener('click', () => {
                            isPaused = true;
                            offset = wrapOffset(offset - cardStep());
                            applyTransform();
                            setTimeout(() => { if (!isDragging) isPaused = false; }, 600);
                        });

                        // ---- Mouse Drag Handling ----
                        outer.addEventListener('mousedown', e => {
                            if (e.button !== 0) return; // hanya klik kiri
                            isDragging   = true;
                            hasDragged   = false;
                            isPaused     = true;
                            dragStartX   = e.clientX;
                            dragStartY   = e.clientY;
                            dragStartOff = offset;
                            outer.style.cursor = 'grabbing';
                        });

                        document.addEventListener('mousemove', e => {
                            if (!isDragging) return;
                            const deltaX = e.clientX - dragStartX;
                            const deltaY = e.clientY - dragStartY;
                            const dist   = Math.hypot(deltaX, deltaY);

                            if (dist > DRAG_THRESHOLD) {
                                hasDragged = true;
                                dragPreventClickUntil = Date.now() + 350;
                                track.style.pointerEvents = 'none'; // Matikan pointer event link saat sedang digeser
                                e.preventDefault();
                            }

                            if (hasDragged) {
                                offset = wrapOffset(dragStartOff + deltaX);
                                applyTransform();
                            }
                        });

                        document.addEventListener('mouseup', () => {
                            if (!isDragging) return;
                            isDragging = false;
                            outer.style.cursor = 'grab';

                            if (hasDragged) {
                                dragPreventClickUntil = Date.now() + 350;
                                // Biarkan pointerEvents = none sejenak agar event click tidak tembus ke ebook
                                setTimeout(() => {
                                    track.style.pointerEvents = '';
                                    hasDragged = false;
                                }, 100);
                            } else {
                                track.style.pointerEvents = '';
                            }

                            setTimeout(() => {
                                if (!isDragging) isPaused = false;
                            }, 700);
                        });

                        // ---- Touch Swipe Handling ----
                        let touchStartX = 0;
                        let touchStartY = 0;
                        let touchStartOff = 0;
                        let touchMoved = false;

                        outer.addEventListener('touchstart', e => {
                            if (!e.touches.length) return;
                            touchStartX   = e.touches[0].clientX;
                            touchStartY   = e.touches[0].clientY;
                            touchStartOff = offset;
                            touchMoved    = false;
                            isPaused      = true;
                        }, { passive: true });

                        outer.addEventListener('touchmove', e => {
                            if (!e.touches.length) return;
                            const deltaX = e.touches[0].clientX - touchStartX;
                            const deltaY = e.touches[0].clientY - touchStartY;
                            const dist   = Math.hypot(deltaX, deltaY);

                            if (dist > DRAG_THRESHOLD) {
                                touchMoved = true;
                                hasDragged = true;
                                dragPreventClickUntil = Date.now() + 350;
                                track.style.pointerEvents = 'none';
                            }

                            if (touchMoved) {
                                offset = wrapOffset(touchStartOff + deltaX);
                                applyTransform();
                            }
                        }, { passive: true });

                        outer.addEventListener('touchend', () => {
                            if (touchMoved) {
                                dragPreventClickUntil = Date.now() + 350;
                                setTimeout(() => {
                                    track.style.pointerEvents = '';
                                    hasDragged = false;
                                    touchMoved = false;
                                }, 100);
                            } else {
                                track.style.pointerEvents = '';
                            }
                            setTimeout(() => {
                                if (!isDragging) isPaused = false;
                            }, 900);
                        });

                        // ---- Intercept & Block Clicks When Dragged ----
                        function blockClickIfDragged(e) {
                            if (hasDragged || Date.now() < dragPreventClickUntil) {
                                if (outer.contains(e.target) || e.target === outer) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    e.stopImmediatePropagation();
                                    return false;
                                }
                            }
                        }

                        // Tangkap di capture phase paling awal
                        window.addEventListener('click', blockClickIfDragged, true);
                        outer.addEventListener('click', blockClickIfDragged, true);
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', setupCarousel);
                    } else {
                        setupCarousel();
                    }
                    document.addEventListener('livewire:navigated', setupCarousel);
                })();
                </script>

            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#E6ECF5] dark:border-slate-800 p-8 text-center text-[#5A6A85] dark:text-slate-400 text-sm reveal-on-scroll">
                    Belum ada e-book yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    {{-- SECTION: ARTIKEL & BERITA --}}
    <section id="artikel" class="bg-white dark:bg-[#0a1224] border-b border-[#E6ECF5] dark:border-slate-800 py-14 md:py-20 scroll-mt-24 md:scroll-mt-28 relative transition-colors duration-200">
        <div id="informasi" class="absolute -top-24"></div>
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 reveal-on-scroll">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#083575] dark:text-sky-300">Artikel &amp; Berita Terkini</h2>
                    <p class="text-xs sm:text-sm text-[#5A6A85] dark:text-slate-400 mt-1">Buletin berkala, pengumuman persandian, dan kabar keamanan digital teranyar.</p>
                </div>
                <a href="{{ route('informasi.index') }}" wire:navigate.hover class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1D68BD] dark:text-sky-400 hover:text-[#083575] dark:hover:text-sky-300 group">
                    <span>Semua Artikel</span>
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
            </div>

            @if($recentNews->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 reveal-on-scroll reveal-delay-1">
                    @foreach($recentNews as $news)
                        @php
                            $thumbSrc = $news->gambar;
                        @endphp
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e2e8f0] dark:border-slate-800 hover:border-[#cbd5e1] dark:hover:border-slate-700 hover:shadow-md hover:-translate-y-1 transition-all duration-200 group overflow-hidden flex flex-col h-full">
                            <a href="{{ route('informasi.show', $news->id) }}" wire:navigate.hover class="flex flex-col h-full cursor-pointer">
                                {{-- Thumbnail Berita --}}
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

                                {{-- Body Berita --}}
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
                                        @if($news->ringkasan)
                                            <p class="text-xs sm:text-sm text-[#64748b] dark:text-slate-400 leading-relaxed line-clamp-2">
                                                {{ $news->ringkasan }}
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
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#E6ECF5] dark:border-slate-800 p-8 text-center text-[#5A6A85] dark:text-slate-400 text-sm">
                    Belum ada artikel atau berita yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    {{-- TENTANG SECTION (INAgov Style Ringkas & 4 Poin Visi Misi - Sebelum Footer) --}}
    <section id="tentang" class="bg-[#F8FAFC] dark:bg-[#070e1e] border-b border-[#E6ECF5] dark:border-slate-800 scroll-mt-24 md:scroll-mt-28 relative overflow-hidden transition-colors duration-200"
             style="padding-top: clamp(70px, 8vw, 110px); padding-bottom: clamp(70px, 8vw, 110px);">
        <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-12 relative">
            {{-- Sparkle Kanan --}}
            <div class="hidden lg:block absolute right-4 xl:right-8 top-1/3 text-[#F02D2D] select-none pointer-events-none" style="width: 24px; height: 24px;">
                <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0L14.3 9.7L24 12L14.3 14.3L12 24L9.7 14.3L0 12L9.7 9.7L12 0Z"/>
                </svg>
            </div>

            {{-- Header & Penjelasan Ringkas --}}
            <div class="max-w-4xl relative reveal-on-scroll">
                {{-- Sparkle Kiri & Judul --}}
                <div class="flex items-center gap-3 mb-2">
                    <div class="text-[#F02D2D] select-none shrink-0" style="width: 24px; height: 24px;">
                        <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0L14.3 9.7L24 12L14.3 14.3L12 24L9.7 14.3L0 12L9.7 9.7L12 0Z"/>
                        </svg>
                    </div>
                    <h2 class="font-bold text-[#083575] dark:text-sky-300 tracking-tight" style="font-size: clamp(1.6rem, 2.3vw, 2.15rem); line-height: 1.25;">
                        Tentang Lentera Sandi
                    </h2>
                </div>

                <p class="font-semibold text-[#0F172A] dark:text-slate-200" style="margin-top: 14px; font-size: clamp(0.95rem, 1.1vw, 1.05rem); line-height: 1.5;">
                    Portal Digital Terintegrasi untuk Literasi &amp; Edukasi Keamanan Informasi (LENTERA SANDI).
                </p>

                <p class="text-[#5A6A85] dark:text-slate-400 max-w-3xl" style="margin-top: 14px; font-size: clamp(0.875rem, 1vw, 0.95rem); line-height: 1.65;">
                    Lentera Sandi adalah inisiatif strategis dari Bidang Persandian dan Keamanan Informasi, Dinas Komunikasi dan Informatika Provinsi Jawa Timur untuk memfasilitasi peningkatan budaya sadar keamanan informasi di kalangan ASN serta masyarakat umum dalam satu sistem terintegrasi.
                </p>
            </div>

            {{-- 4 Poin Diambil dari Visi & Misi --}}
            <div class="grid grid-cols-1 md:grid-cols-2" 
                 style="margin-top: clamp(40px, 5vw, 64px); row-gap: clamp(28px, 3.5vw, 40px); column-gap: clamp(36px, 4.5vw, 64px);">
                {{-- Poin 1 (Visi: Tata Kelola & Ketahanan Digital) --}}
                <div class="flex items-start gap-3.5 sm:gap-4 reveal-on-scroll reveal-delay-1">
                    <div class="text-[#1D68BD] dark:text-sky-400 shrink-0 mt-0.5" style="width: 30px; height: 30px; min-width: 30px;">
                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#083575] dark:text-sky-300" style="font-size: clamp(0.95rem, 1.1vw, 1.05rem); line-height: 1.35; margin-bottom: 5px;">
                            Ekosistem Digital Berketahanan
                        </h3>
                        <p class="text-[#5A6A85] dark:text-slate-400" style="font-size: clamp(0.8125rem, 0.9vw, 0.875rem); line-height: 1.6;">
                            Mewujudkan tata kelola persandian dan ketahanan digital pemerintahan Jawa Timur yang aman, andal, dan siap menghadapi ancaman siber terkini.
                        </p>
                    </div>
                </div>

                {{-- Poin 2 (Misi: Kesadaran & Budaya Keamanan ASN) --}}
                <div class="flex items-start gap-3.5 sm:gap-4 reveal-on-scroll reveal-delay-2">
                    <div class="text-[#1D68BD] dark:text-sky-400 shrink-0 mt-0.5" style="width: 30px; height: 30px; min-width: 30px;">
                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#083575] dark:text-sky-300" style="font-size: clamp(0.95rem, 1.1vw, 1.05rem); line-height: 1.35; margin-bottom: 5px;">
                            Budaya Sadar Keamanan Informasi
                        </h3>
                        <p class="text-[#5A6A85] dark:text-slate-400" style="font-size: clamp(0.8125rem, 0.9vw, 0.875rem); line-height: 1.6;">
                            Meningkatkan kapasitas dan kesadaran ASN di lingkungan pemerintah daerah dalam memproteksi kerahasiaan dan integritas data publik.
                        </p>
                    </div>
                </div>

                {{-- Poin 3 (Misi: Materi Edukasi Praktis & Terstruktur) --}}
                <div class="flex items-start gap-3.5 sm:gap-4 reveal-on-scroll reveal-delay-3">
                    <div class="text-[#1D68BD] dark:text-sky-400 shrink-0 mt-0.5" style="width: 30px; height: 30px; min-width: 30px;">
                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11 2v20c-5.07-.5-9-4.79-9-10s3.93-9.5 9-10zm2 0v8.99H22c-.47-4.74-4.24-8.52-8.99-8.99zm0 11.01V22c4.75-.47 8.52-4.25 9-8.99H13z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#083575] dark:text-sky-300" style="font-size: clamp(0.95rem, 1.1vw, 1.05rem); line-height: 1.35; margin-bottom: 5px;">
                            Materi Edukasi Praktis &amp; Terstruktur
                        </h3>
                        <p class="text-[#5A6A85] dark:text-slate-400" style="font-size: clamp(0.8125rem, 0.9vw, 0.875rem); line-height: 1.6;">
                            Menyediakan pedoman teknis, kebijakan persandian, dan regulasi keamanan informasi yang praktis, aplikatif, dan mudah dipahami.
                        </p>
                    </div>
                </div>

                {{-- Poin 4 (Misi: Akses Pembelajaran Terbuka & Bebas) --}}
                <div class="flex items-start gap-3.5 sm:gap-4 reveal-on-scroll reveal-delay-4">
                    <div class="text-[#1D68BD] dark:text-sky-400 shrink-0 mt-0.5" style="width: 30px; height: 30px; min-width: 30px;">
                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14zm-4.5 1a1 1 0 11-2 0 1 1 0 012 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#083575] dark:text-sky-300" style="font-size: clamp(0.95rem, 1.1vw, 1.05rem); line-height: 1.35; margin-bottom: 5px;">
                            Akses Layanan Mudah &amp; Terbuka
                        </h3>
                        <p class="text-[#5A6A85] dark:text-slate-400" style="font-size: clamp(0.8125rem, 0.9vw, 0.875rem); line-height: 1.6;">
                            Memberikan kemudahan bagi ASN dan masyarakat umum untuk membaca secara online serta mengunduh e-book kapan saja secara gratis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
