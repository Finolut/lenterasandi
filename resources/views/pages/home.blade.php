@php
    use App\Models\Ebook;
    use App\Models\Informasi;
    use Illuminate\Support\Facades\Cache;
    
    $totalEbooks = Cache::remember('home_total_ebooks', 60, function () {
        return Ebook::where('status', 'Publik')->count();
    });



    $featuredEbooks = Cache::remember('home_featured_ebooks', now()->addMinutes(10), fn () => Ebook::where('status', 'Publik')
        ->select(['id', 'judul', 'kategori', 'file_url', 'cover_url', 'created_at'])
        ->latest()->take(6)->get());
    $recentNews = Cache::remember('home_recent_news', now()->addMinutes(10), fn () => Informasi::where('status', 'Publik')
        ->select(['id', 'judul', 'ringkasan', 'gambar_url', 'kategori', 'published_at'])
        ->latest('published_at')->take(3)->get());
@endphp
<x-layouts.app active="Beranda">
    {{-- HERO SECTION (INAgov Style) --}}
    <section id="beranda" class="relative min-h-[92vh] md:min-h-screen w-full bg-cover bg-no-repeat flex flex-col justify-between items-center text-center px-4 overflow-hidden border-b border-[#E6ECF5] dark:border-slate-800 scroll-mt-24 md:scroll-mt-28"
        style="background-image: url('{{ asset('assets/images/background.png') }}'); background-position: center bottom;">
        
        {{-- Subtle Dark Mode Backdrop Overlay --}}
        <div class="absolute inset-0 bg-transparent dark:bg-[#070e1e]/75 transition-colors duration-200 pointer-events-none"></div>

        {{-- Hero Center Content --}}
        <div class="max-w-4xl mx-auto pt-32 sm:pt-40 md:pt-44 pb-6 flex flex-col items-center z-10 reveal-on-scroll">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[46px] font-semibold text-[#083575] dark:text-sky-200 leading-tight tracking-tight">
                Akses Koleksi E-Book &amp; Artikel Digital dengan Lebih Efisien
            </h1>
            <p class="mt-4 sm:mt-5 text-base sm:text-lg md:text-xl text-[#5A6A85] dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Platform terpadu untuk mengakses, membaca e-book edukasi terstruktur, dan memperoleh panduan serta informasi keamanan digital terkini secara praktis.
            </p>
            <div class="mt-6 sm:mt-8 flex justify-center">
                <a
                    href="#tentang"
                    class="nav-smooth-link inline-flex items-center justify-center whitespace-nowrap rounded-lg font-medium border border-[#D0D5DD] dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-[#083575] dark:text-sky-300 h-11 sm:h-12 px-6 sm:px-7 text-sm sm:text-base shadow-xs hover:shadow-sm transition-all duration-200"
                >
                    Pelajari Lentera Sandi
                </a>
            </div>
        </div>

        {{-- Reserved spacing for character illustration at bottom of background.png --}}
        <div class="h-60 sm:h-72 md:h-84 w-full pointer-events-none"></div>
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
                    <div class="absolute left-0 top-0 bottom-0 w-6 bg-gradient-to-r from-[#F8FAFC] dark:from-[#070e1e] to-transparent z-10 pointer-events-none hidden sm:block"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-6 bg-gradient-to-l from-[#F8FAFC] dark:from-[#070e1e] to-transparent z-10 pointer-events-none hidden sm:block"></div>

                    {{-- Scrollable Track --}}
                    <div
                        id="ebook-carousel"
                        class="flex gap-4 sm:gap-5 overflow-x-auto pb-4 scroll-smooth cursor-grab active:cursor-grabbing select-none"
                        style="scrollbar-width: none; -ms-overflow-style: none;"
                    >
                        @foreach($featuredEbooks as $book)
                            @php
                                $coverSrc = $book->cover_image;
                                $pdfSrc   = $book->file_url
                                    ? Storage::disk('public')->url($book->file_url)
                                    : null;
                            @endphp

                            {{-- Kartu Buku --}}
                            <div
                                x-data
                                x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })"
                                class="flex-none w-[160px] sm:w-[185px] md:w-[195px] bg-white dark:bg-slate-900 rounded-xl border border-[#e5e7eb] dark:border-slate-800 hover:border-[#BFDBFE] dark:hover:border-sky-700 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-200 group overflow-hidden flex flex-col"
                            >
                                <a href="{{ route('ebooks.show', $book->id) }}" wire:navigate.hover class="flex flex-col h-full cursor-pointer">

                                    {{-- Cover --}}
                                    <div class="relative w-full bg-[#f8fafc] dark:bg-slate-950/60 overflow-hidden border-b border-[#f1f5f9] dark:border-slate-800 flex items-center justify-center rounded-t-xl" style="aspect-ratio: 2/3;">
                                        @if($coverSrc)
                                            <img src="{{ $coverSrc }}" alt="{{ $book->judul }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300" loading="lazy" draggable="false">
                                        @elseif($pdfSrc)
                                            <div class="pdf-cover-wrap relative w-full h-full bg-[#f8fafc] dark:bg-slate-950/60 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                                                <div class="pdf-skeleton absolute inset-0 bg-[#e2e8f0] dark:bg-slate-800 animate-pulse flex flex-col items-center justify-center text-[#94a3b8] dark:text-slate-400 gap-2 p-3">
                                                    <svg class="w-6 h-6 animate-spin text-[#64748b] dark:text-slate-400" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <span class="text-[10px] font-mono text-[#64748b] dark:text-slate-400">Memuat...</span>
                                                </div>
                                                <img class="pdf-cover-img w-full h-full object-cover object-top hidden group-hover:scale-105 transition-transform duration-300" alt="{{ $book->judul }}" draggable="false">
                                            </div>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#EFF6FF] to-[#DBEAFE] dark:from-slate-800 dark:to-slate-900 text-[#94a3b8] dark:text-slate-400 p-4 select-none">
                                                <svg class="w-12 h-12 mb-2 text-[#BFDBFE] dark:text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6H6zm7 1.5L18.5 9H13V3.5zM8 13h8v1.5H8V13zm0 3h5v1.5H8V16z"/>
                                                </svg>
                                                <span class="text-xs font-semibold text-[#93C5FD] dark:text-slate-500">E-Book</span>
                                            </div>
                                        @endif

                                        {{-- Hover overlay badge --}}
                                        <div class="absolute inset-0 bg-[#083575]/0 group-hover:bg-[#083575]/10 dark:group-hover:bg-sky-900/20 transition-all duration-200 flex items-center justify-center rounded-t-xl">
                                            <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-white dark:bg-slate-800 text-[#083575] dark:text-sky-300 text-[11px] font-bold px-3 py-1.5 rounded-full shadow-md border border-[#BFDBFE] dark:border-sky-700">
                                                Baca →
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Info --}}
                                    <div class="p-3.5 flex flex-col flex-1 gap-1.5">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold border border-[#38bdf8] dark:border-sky-800 text-[#0284c7] dark:text-sky-300 bg-[#f0f9ff] dark:bg-sky-950/60 truncate max-w-full">
                                            {{ $book->kategori }}
                                        </span>
                                        <h3 class="font-bold text-[13px] text-[#0f3b7a] dark:text-slate-100 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-snug line-clamp-3 mt-0.5">
                                            {{ $book->judul }}
                                        </h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Dot indicators --}}
                    <div id="ebook-dots" class="flex items-center justify-center gap-2 mt-4">
                        {{-- Dots generated by JS --}}
                    </div>
                </div>

                {{-- Hide scrollbar CSS --}}
                <style>
                    #ebook-carousel::-webkit-scrollbar { display: none; }
                </style>

                {{-- Carousel JS --}}
                <script>
                    (function () {
                        const carousel  = document.getElementById('ebook-carousel');
                        const prevBtn   = document.getElementById('ebook-prev');
                        const nextBtn   = document.getElementById('ebook-next');
                        const dotsWrap  = document.getElementById('ebook-dots');

                        if (!carousel) return;

                        const CARD_GAP   = 20;   // px gap between cards
                        const SCROLL_BY  = () => carousel.querySelector('[class*="flex-none"]')?.offsetWidth + CARD_GAP || 215;

                        // ---- Dot indicators ----
                        const cards      = carousel.querySelectorAll('[class*="flex-none"]');
                        const totalCards = cards.length;
                        let dots         = [];

                        if (dotsWrap && totalCards > 0) {
                            cards.forEach((_, i) => {
                                const dot = document.createElement('button');
                                dot.setAttribute('aria-label', 'Slide ' + (i + 1));
                                dot.className = 'w-2 h-2 rounded-full transition-all duration-200 ' + (i === 0 ? 'bg-[#083575] dark:bg-sky-400 scale-125' : 'bg-[#cbd5e1] dark:bg-slate-600');
                                dot.addEventListener('click', () => {
                                    carousel.scrollTo({ left: i * SCROLL_BY(), behavior: 'smooth' });
                                });
                                dotsWrap.appendChild(dot);
                                dots.push(dot);
                            });
                        }

                        function updateControls() {
                            const max = carousel.scrollWidth - carousel.clientWidth;
                            if (prevBtn) prevBtn.disabled = carousel.scrollLeft <= 4;
                            if (nextBtn) nextBtn.disabled = carousel.scrollLeft >= max - 4;

                            // Update dots
                            if (dots.length > 0) {
                                const idx = Math.round(carousel.scrollLeft / SCROLL_BY());
                                dots.forEach((d, i) => {
                                    if (i === idx) {
                                        d.className = d.className.replace('bg-[#cbd5e1] dark:bg-slate-600', '').trim();
                                        d.classList.add('bg-[#083575]', 'dark:bg-sky-400', 'scale-125');
                                    } else {
                                        d.classList.remove('bg-[#083575]', 'scale-125');
                                        d.classList.add('bg-[#cbd5e1]');
                                    }
                                });
                            }
                        }

                        carousel.addEventListener('scroll', updateControls, { passive: true });
                        updateControls();

                        if (prevBtn) prevBtn.addEventListener('click', () => carousel.scrollBy({ left: -SCROLL_BY(), behavior: 'smooth' }));
                        if (nextBtn) nextBtn.addEventListener('click', () => carousel.scrollBy({ left:  SCROLL_BY(), behavior: 'smooth' }));

                        // ---- Drag to scroll (mouse) ----
                        let isDragging = false, startX = 0, startScroll = 0;

                        carousel.addEventListener('mousedown', e => {
                            isDragging  = true;
                            startX      = e.pageX - carousel.offsetLeft;
                            startScroll = carousel.scrollLeft;
                            carousel.style.scrollBehavior = 'auto';
                        });

                        document.addEventListener('mouseup', () => {
                            isDragging = false;
                            carousel.style.scrollBehavior = '';
                        });

                        carousel.addEventListener('mousemove', e => {
                            if (!isDragging) return;
                            e.preventDefault();
                            const x     = e.pageX - carousel.offsetLeft;
                            const walk  = (x - startX) * 1.4;
                            carousel.scrollLeft = startScroll - walk;
                        });

                        // Prevent link navigation after drag
                        carousel.addEventListener('click', e => {
                            if (Math.abs(carousel.scrollLeft - startScroll) > 5) {
                                e.preventDefault();
                            }
                        }, true);

                        // Touch swipe support
                        let touchStartX = 0, touchScroll = 0;
                        carousel.addEventListener('touchstart', e => {
                            touchStartX = e.touches[0].clientX;
                            touchScroll = carousel.scrollLeft;
                        }, { passive: true });
                        carousel.addEventListener('touchmove', e => {
                            const delta = touchStartX - e.touches[0].clientX;
                            carousel.scrollLeft = touchScroll + delta;
                        }, { passive: true });
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
