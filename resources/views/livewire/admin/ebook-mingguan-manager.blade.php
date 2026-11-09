<div class="space-y-6">
    {{-- Header & Penjelasan --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-200/80">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                E-Book Rekomendasi Mingguan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                Tentukan satu e-book unggulan yang ditampilkan secara khusus pada banner utama (Hero) di Beranda publik Lentera Sandi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ route('home') }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg shadow-2xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Lihat di Beranda Publik</span>
                <span class="text-slate-400">↗</span>
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 cursor-pointer text-sm">✕</button>
        </div>
    @endif

    {{-- 1. CURRENT ACTIVE SPOTLIGHT CARD --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50/75 border-b border-slate-200/80 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status Beranda Saat Ini</span>
            </div>
            @if($currentMingguan)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    E-Book Khusus Terpasang
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Otomatis (E-Book Publik Terbaru)
                </span>
            @endif
        </div>

        <div class="p-5 sm:p-6">
            @if($currentMingguan)
                @php
                    $coverSrc = $currentMingguan->cover_image;
                    $pdfSrc   = $currentMingguan->file_url ? Storage::disk('public')->url($currentMingguan->file_url) : null;
                    $cleanDeskripsi = strip_tags($currentMingguan->deskripsi ?? '');
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                    {{-- Cover Thumbnail --}}
                    <div class="md:col-span-3 lg:col-span-2 flex justify-center">
                        <div class="w-32 sm:w-36 aspect-[2/3] rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm relative shrink-0">
                            @if($coverSrc)
                                <img
                                    src="{{ $coverSrc }}"
                                    alt="Cover {{ $currentMingguan->judul }}"
                                    class="w-full h-full object-cover"
                                >
                            @elseif($pdfSrc)
                                <div class="pdf-cover-wrap relative w-full h-full bg-slate-100 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                                    <div class="pdf-skeleton absolute inset-0 bg-slate-200 animate-pulse flex flex-col items-center justify-center text-slate-400 p-2">
                                        <svg class="w-5 h-5 animate-spin text-[#0284c7]" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <img class="pdf-cover-img w-full h-full object-cover hidden" alt="{{ $currentMingguan->judul }}">
                                </div>
                            @else
                                <div class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400">
                                    <span class="text-xs font-bold text-slate-500">PDF</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Informasi Detail E-Book Mingguan --}}
                    <div class="md:col-span-9 lg:col-span-10 flex flex-col justify-between h-full space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                    <svg class="w-3.5 h-3.5 text-amber-500 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    E-Book Mingguan Aktif
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $currentMingguan->kategori }}
                                </span>
                            </div>

                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
                                {{ $currentMingguan->judul }}
                            </h2>

                            <div class="flex items-center gap-2 text-xs text-slate-600 flex-wrap">
                                @if($currentMingguan->pengarang)
                                    <span>Penulis: <strong class="text-slate-800">{{ $currentMingguan->pengarang }}</strong></span>
                                @endif
                                @if($currentMingguan->penerbit)
                                    <span>&bull;</span>
                                    <span>Penerbit: <strong class="text-slate-800">{{ $currentMingguan->penerbit }}</strong></span>
                                @endif
                                @if($currentMingguan->tahun)
                                    <span>&bull;</span>
                                    <span>Tahun: <strong class="text-slate-800">{{ $currentMingguan->tahun }}</strong></span>
                                @endif
                            </div>

                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed">
                                {{ !empty(trim($cleanDeskripsi)) ? $cleanDeskripsi : 'Tidak ada ringkasan atau deskripsi e-book.' }}
                            </p>
                        </div>

                        {{-- Metadata & Actions --}}
                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1 font-medium text-slate-700">
                                    <svg class="w-4 h-4 text-[#0284c7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ number_format($currentMingguan->jumlah_dibaca ?? 0) }}x dibaca
                                </span>
                                <span class="flex items-center gap-1 font-medium text-slate-700">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    {{ number_format($currentMingguan->jumlah_unduhan ?? 0) }}x diunduh
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('admin.ebooks.edit', $currentMingguan->id) }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors cursor-pointer"
                                >
                                    <span>Edit E-Book</span>
                                </a>

                                <button
                                    wire:click="unsetMingguan({{ $currentMingguan->id }})"
                                    wire:confirm="Lepas e-book ini dari rekomendasi mingguan? Beranda akan kembali menampilkan e-book terbaru secara otomatis."
                                    type="button"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors cursor-pointer"
                                    title="Lepas status Mingguan"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Lepas dari Beranda</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-6 text-center max-w-lg mx-auto">
                    <div class="w-12 h-12 rounded-full bg-sky-50 text-[#0284c7] flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Belum Ada E-Book Mingguan yang Dipilih</h3>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                        Saat ini Beranda publik secara otomatis menampilkan <strong>e-book publik terbaru</strong> pada banner Hero. Anda dapat memilih salah satu e-book dari daftar di bawah untuk mengunci rekomendasi mingguan.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- 2. CATALOG SELECTOR SECTION --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
        {{-- Toolbar Card Filter --}}
        <div class="p-4 sm:p-5 border-b border-slate-200/80 bg-white">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Pilih E-Book untuk Dipajang</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar e-book publik yang tersedia untuk dijadikan rekomendasi mingguan.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                    {{-- Search Input --}}
                    <div class="relative min-w-[240px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cari judul atau pengarang..."
                            class="w-full h-9 pl-9 pr-3 text-xs bg-white border border-slate-200 rounded-lg text-slate-800 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                        />
                    </div>

                    {{-- Category Filter --}}
                    <select
                        wire:model.live="selectedCategory"
                        class="h-9 px-3 text-xs bg-white border border-slate-200 rounded-lg text-slate-800 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors cursor-pointer"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>

                    @if($search || $selectedCategory)
                        <button
                            wire:click="$set('search', ''); $set('selectedCategory', '');"
                            type="button"
                            class="h-9 px-3 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer shrink-0"
                            title="Reset filter"
                        >
                            Reset
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table List E-Book --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3 w-14 text-center">Cover</th>
                        <th class="px-4 py-3">Informasi E-Book</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3 hidden md:table-cell">Statistik</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ebooks as $b)
                        @php
                            $coverSrc = $b->cover_image;
                            $pdfSrc   = $b->file_url ? Storage::disk('public')->url($b->file_url) : null;
                            $isActive = $b->is_mingguan;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $isActive ? 'bg-amber-50/30' : '' }}">
                            {{-- Cover --}}
                            <td class="px-4 py-3 text-center">
                                <div class="w-9 h-12 rounded overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs flex items-center justify-center relative mx-auto shrink-0">
                                    @if($coverSrc)
                                        <img
                                            src="{{ $coverSrc }}"
                                            alt="{{ $b->judul }}"
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
                                            <img class="pdf-cover-img w-full h-full object-cover hidden" alt="{{ $b->judul }}">
                                        </div>
                                    @else
                                        <span class="text-[9px] font-bold text-slate-500">PDF</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Judul & Pengarang --}}
                            <td class="px-4 py-3 max-w-[320px]">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if($isActive)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            <svg class="w-2.5 h-2.5 text-amber-500 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Aktif
                                        </span>
                                    @endif
                                    <span class="text-xs sm:text-sm font-semibold text-slate-900 line-clamp-1">
                                        {{ $b->judul }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                    <span class="truncate">{{ $b->pengarang ?? 'Penulis tidak dicantumkan' }}</span>
                                    @if($b->tahun)
                                        <span>&bull;</span>
                                        <span>{{ $b->tahun }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $b->kategori }}
                                </span>
                            </td>

                            {{-- Statistik --}}
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-600 hidden md:table-cell">
                                <div class="flex items-center gap-3 text-[11px]">
                                    <span title="Dibaca">{{ number_format($b->jumlah_dibaca ?? 0) }}x dibaca</span>
                                    <span>&bull;</span>
                                    <span title="Diunduh">{{ number_format($b->jumlah_unduhan ?? 0) }}x diunduh</span>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                @if($isActive)
                                    <div class="inline-flex items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Sedang Dipajang</span>
                                        </span>
                                    </div>
                                @else
                                    <button
                                        wire:click="setMingguan({{ $b->id }})"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#0284c7] hover:bg-[#0369a1] shadow-2xs transition-colors cursor-pointer"
                                        title="Pilih sebagai E-Book Mingguan"
                                    >
                                        <svg class="w-3.5 h-3.5 text-sky-100 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span>Jadikan E-Book Mingguan</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-xs">
                                Tidak ada e-book yang cocok dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($ebooks->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200/80 bg-slate-50/50">
                {{ $ebooks->links() }}
            </div>
        @endif
    </div>
</div>

