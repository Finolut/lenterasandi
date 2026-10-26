<div class="space-y-6" x-data x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })">
    {{-- 1. Executive Portal Banner --}}
    <div class="rounded-xl bg-[#083575] text-white px-6 py-5 sm:px-7 sm:py-6 shadow-xs border border-[#0b489a]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-white/10 text-sky-200 text-xs font-medium mb-2 border border-white/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Sistem Aktif &bull; Diskominfo Provinsi Jawa Timur</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                    Dashboard Administrasi Lentera Sandi
                </h1>
                <p class="text-xs sm:text-sm text-slate-200 mt-1 max-w-2xl leading-relaxed">
                    Pusat pengelolaan literasi keamanan siber, dokumen digital persandian, dan verifikasi permohonan akses publik.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a
                    href="{{ route('admin.ebooks.create') }}"
                    wire:navigate
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold transition-colors shadow-xs"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah E-Book</span>
                </a>
                <a
                    href="{{ route('admin.informasi.create') }}"
                    wire:navigate
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition-colors border border-white/15"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Tulis Artikel</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 2. KPI Metrics Grid (4 Clean Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Card 1: Total E-Book --}}
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total E-Book</span>
                <div class="w-9 h-9 rounded-lg bg-sky-50 text-[#0284c7] flex items-center justify-center border border-sky-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalEbook) }}</span>
                <span class="text-xs font-medium text-slate-500">koleksi</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                <span class="text-emerald-700 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $ebookMingguIni > 0 ? "+{$ebookMingguIni} minggu ini" : 'Terdata aktif' }}
                </span>
                <span class="text-[11px] text-slate-600 bg-slate-50 border border-slate-200/60 px-2 py-0.5 rounded font-medium">
                    {{ $ebookPublik }} Publik
                </span>
            </div>
        </div>

        {{-- Card 2: Total Artikel / Informasi --}}
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Artikel</span>
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalInformasi) }}</span>
                <span class="text-xs font-medium text-slate-500">artikel</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                <span class="text-emerald-700 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $informasiMingguIni > 0 ? "+{$informasiMingguIni} minggu ini" : 'Terdata aktif' }}
                </span>
                <span class="text-[11px] text-slate-600 bg-slate-50 border border-slate-200/60 px-2 py-0.5 rounded font-medium">
                    {{ $informasiPublik }} Publik
                </span>
            </div>
        </div>

        {{-- Card 3: Total Unduhan --}}
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Unduhan</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalUnduhan) }}</span>
                <span class="text-xs font-medium text-slate-500">kali diunduh</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                <span class="text-slate-500">Akumulasi PDF</span>
                <span class="text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200/60 font-semibold px-2 py-0.5 rounded">
                    Akses Terbuka
                </span>
            </div>
        </div>

        {{-- Card 4: Total Pembaca Artikel --}}
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pembaca</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalViews) }}</span>
                <span class="text-xs font-medium text-slate-500">kali dilihat</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                <span class="text-slate-500">Pembaca artikel</span>
                <span class="text-[11px] text-slate-700 bg-slate-50 border border-slate-200/60 font-medium px-2 py-0.5 rounded">
                    Portal Jatim
                </span>
            </div>
        </div>
    </div>

    {{-- Notifikasi Pesan Masuk Baru --}}
    @if(($unreadPesan ?? 0) > 0)
        <div class="rounded-xl bg-amber-50 border border-amber-200/80 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
            <div class="flex items-center gap-3.5">
                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Ada {{ $unreadPesan }} Permohonan Akses Berkas Menunggu Verifikasi</h3>
                    <p class="text-xs text-slate-600 mt-0.5">Pemohon telah mengisi formulir permohonan akses berkas pendukung artikel.</p>
                </div>
            </div>
            <a href="{{ route('admin.pesan.index') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#083575] hover:bg-[#06295c] text-white text-xs font-semibold transition-colors shrink-0">
                <span>Buka Pesan Masuk</span>
                <span>&rarr;</span>
            </a>
        </div>
    @endif

    {{-- 3. Data Tables Section --}}
    <div class="space-y-6">
        {{-- Table 1: E-Book Terbaru --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Koleksi E-Book Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Materi literasi dan panduan keamanan siber yang terakhir diunggah</p>
                </div>

                <a
                    href="{{ route('admin.ebooks.index') }}"
                    wire:navigate
                    class="text-xs font-semibold text-[#0284c7] hover:text-[#0369a1] hover:underline flex items-center gap-1"
                >
                    <span>Lihat Semua ({{ $totalEbook }})</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($recentEbooks->isEmpty())
                <div class="py-12 px-6 flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 mb-2.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-900">Belum ada modul e-book</p>
                    <p class="text-xs text-slate-500 mt-0.5 mb-3">Mulai unggah materi e-book literasi keamanan digital.</p>
                    <a
                        href="{{ route('admin.ebooks.create') }}"
                        wire:navigate
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#0284c7] hover:bg-[#0369a1] transition-colors"
                    >
                        + Tambah E-Book
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-5">E-Book</th>
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4">Unduhan</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentEbooks as $ebook)
                                @php
                                    $coverSrc = $ebook->cover_image;
                                    $pdfSrc   = $ebook->file_url ? Storage::disk('public')->url($ebook->file_url) : null;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            {{-- Mini Cover Thumbnail --}}
                                            <div class="w-10 h-14 rounded overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs flex items-center justify-center relative shrink-0">
                                                @if($coverSrc)
                                                    <img
                                                        src="{{ $coverSrc }}"
                                                        alt="Cover {{ $ebook->judul }}"
                                                        class="w-full h-full object-cover"
                                                        loading="lazy"
                                                    >
                                                @elseif($pdfSrc)
                                                    <div class="pdf-cover-wrap relative w-full h-full bg-slate-100 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                                                        <div class="pdf-skeleton absolute inset-0 bg-slate-200 animate-pulse flex flex-col items-center justify-center text-slate-400 p-1">
                                                            <svg class="w-3.5 h-3.5 animate-spin text-[#0284c7]" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </div>
                                                        <img class="pdf-cover-img w-full h-full object-cover hidden" alt="{{ $ebook->judul }}">
                                                    </div>
                                                @else
                                                    <div class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400">
                                                        <span class="text-[9px] font-bold text-slate-500">PDF</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <a
                                                    href="{{ route('admin.ebooks.edit', $ebook->id) }}"
                                                    wire:navigate
                                                    class="font-semibold text-xs sm:text-sm text-slate-900 hover:text-[#0284c7] transition-colors line-clamp-1 block"
                                                    title="{{ $ebook->judul }}"
                                                >
                                                    {{ $ebook->judul }}
                                                </a>
                                                <div class="text-[11px] text-slate-500 flex items-center gap-1.5 mt-0.5">
                                                    <span>{{ $ebook->pengarang ?? 'Diskominfo Jatim' }}</span>
                                                    <span>&bull;</span>
                                                    <span>{{ $ebook->tanggal_upload }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium border border-slate-200/80 text-slate-700 bg-slate-50">
                                            {{ $ebook->kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-800 font-semibold text-xs">
                                        {{ number_format($ebook->jumlah_unduhan) }}x
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($ebook->status === 'Publik')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Publik
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Draf
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-5 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a
                                                href="{{ route('ebooks.show', $ebook->id) }}"
                                                target="_blank"
                                                class="p-1 rounded text-slate-500 hover:text-[#0284c7] hover:bg-slate-100 transition-colors"
                                                title="Lihat Portal Publik"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a
                                                href="{{ route('admin.ebooks.edit', $ebook->id) }}"
                                                wire:navigate
                                                class="px-2.5 py-1 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 border border-slate-200 transition-colors"
                                            >
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Table 2: Informasi & Berita Terbaru --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Artikel &amp; Berita Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar publikasi dan siaran pers keamanan siber terkini</p>
                </div>

                <a
                    href="{{ route('admin.informasi.index') }}"
                    wire:navigate
                    class="text-xs font-semibold text-[#0284c7] hover:text-[#0369a1] hover:underline flex items-center gap-1"
                >
                    <span>Lihat Semua ({{ $totalInformasi }})</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($recentArticles->isEmpty())
                <div class="py-12 px-6 flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 mb-2.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-900">Belum ada artikel berita</p>
                    <p class="text-xs text-slate-500 mt-0.5 mb-3">Tulis publikasi kegiatan dan edukasi persandian pertama.</p>
                    <a
                        href="{{ route('admin.informasi.create') }}"
                        wire:navigate
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#0284c7] hover:bg-[#0369a1] transition-colors"
                    >
                        + Tulis Artikel
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-5">Artikel</th>
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4">Dilihat</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentArticles as $art)
                                @php
                                    $gambar = $art->gambar;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            {{-- Mini Banner 16:9 --}}
                                            <div class="w-14 h-9 rounded bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                                @if($gambar)
                                                    <img src="{{ $gambar }}" alt="{{ $art->judul }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <a
                                                    href="{{ route('admin.informasi.edit', $art->id) }}"
                                                    wire:navigate
                                                    class="font-semibold text-xs sm:text-sm text-slate-900 hover:text-[#0284c7] transition-colors line-clamp-1 block"
                                                    title="{{ $art->judul }}"
                                                >
                                                    {{ $art->judul }}
                                                </a>
                                                <div class="text-[11px] text-slate-500 flex items-center gap-1.5 mt-0.5">
                                                    <span>{{ $art->creator->name ?? 'Redaksi' }}</span>
                                                    <span>&bull;</span>
                                                    <span>{{ $art->tanggal_publish }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-1 max-w-[200px]">
                                            @foreach($art->kategori_list as $kat)
                                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium border border-slate-200/80 text-slate-700 bg-slate-50">
                                                    {{ $kat }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-800 font-semibold text-xs">
                                        {{ number_format($art->jumlah_dilihat) }}x
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($art->status === 'Publik')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Publik
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Draf
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-5 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a
                                                href="{{ route('informasi.show', $art->id) }}"
                                                target="_blank"
                                                class="p-1 rounded text-slate-500 hover:text-[#0284c7] hover:bg-slate-100 transition-colors"
                                                title="Lihat Portal Publik"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a
                                                href="{{ route('admin.informasi.edit', $art->id) }}"
                                                wire:navigate
                                                class="px-2.5 py-1 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 border border-slate-200 transition-colors"
                                            >
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
