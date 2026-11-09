<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-sm font-semibold text-[#0F172A]">E-Book Mingguan</h1>
            <p class="text-xs text-[#64748B] mt-0.5">Tentukan satu e-book yang ditampilkan pada bagian utama Beranda publik.</p>
        </div>
        <a
            href="{{ route('home') }}"
            target="_blank"
            class="inline-flex items-center gap-1.5 self-start sm:self-auto h-9 px-3 text-xs font-medium text-[#334155] bg-white hover:bg-[#F8FAFC] border border-[#E2E8F0] rounded-md transition-colors"
        >
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat Beranda
        </a>
    </div>

    {{-- Flash Notification --}}
    @if(session()->has('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex items-center gap-2.5 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800"
        >
            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-medium flex-1">{{ session('success') }}</span>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 cursor-pointer leading-none">✕</button>
        </div>
    @endif

    {{-- Section A: E-Book Aktif Saat Ini --}}
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Ditampilkan di Beranda</span>
            @if($currentMingguan)
                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    Otomatis (terbaru)
                </span>
            @endif
        </div>

        @if($currentMingguan)
            @php
                $coverSrc = $currentMingguan->cover_image;
                $pdfSrc   = $currentMingguan->file_url ? Storage::disk('public')->url($currentMingguan->file_url) : null;
            @endphp
            <div class="p-5 flex items-center gap-4">
                {{-- Cover --}}
                <div class="w-14 h-20 rounded-md overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-2xs">
                    @if($coverSrc)
                        <img src="{{ $coverSrc }}" alt="{{ $currentMingguan->judul }}" class="w-full h-full object-cover">
                    @elseif($pdfSrc)
                        <div class="pdf-cover-wrap relative w-full h-full bg-slate-100 overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                            <div class="pdf-skeleton absolute inset-0 bg-slate-200 animate-pulse"></div>
                            <img class="pdf-cover-img w-full h-full object-cover hidden" alt="{{ $currentMingguan->judul }}">
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-[9px] font-bold text-slate-400">PDF</span>
                        </div>
                    @endif
                </div>

                {{-- Detail --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Aktif di Beranda
                        </span>
                        @if($currentMingguan->kategori)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200/80">
                                {{ $currentMingguan->kategori }}
                            </span>
                        @endif
                    </div>

                    <p class="text-sm font-semibold text-slate-900 leading-snug line-clamp-2">
                        {{ $currentMingguan->judul }}
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5 truncate">
                        {{ $currentMingguan->pengarang ?? 'Penulis tidak dicantumkan' }}
                    </p>
                </div>

                {{-- Action --}}
                <div class="shrink-0">
                    <button
                        wire:click="unsetMingguan({{ $currentMingguan->id }})"
                        wire:confirm="Lepas e-book ini dari Beranda? Beranda akan kembali menampilkan e-book terbaru secara otomatis."
                        type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-600 bg-white hover:bg-rose-50 border border-rose-200 rounded-lg transition-colors cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Lepas dari Beranda
                    </button>
                </div>
            </div>
        @else
            <div class="px-5 py-6 text-center">
                <p class="text-xs text-slate-500 leading-relaxed">
                    Belum ada e-book yang dipilih. Beranda secara otomatis menampilkan <span class="font-semibold text-slate-700">e-book publik terbaru</span>.<br>
                    Pilih dari daftar di bawah untuk menetapkan e-book mingguan.
                </p>
            </div>
        @endif
    </div>

    {{-- Section B: Pilih E-Book --}}
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden">

        {{-- Toolbar --}}
        <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-3">
            <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider sm:mr-auto">Pilih E-Book</span>

            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari judul atau pengarang..."
                        class="h-8 pl-8 pr-3 w-52 text-xs bg-white border border-slate-200 rounded-lg text-slate-800 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                    />
                </div>

                {{-- Category --}}
                <select
                    wire:model.live="selectedCategory"
                    class="h-8 px-2.5 text-xs bg-white border border-slate-200 rounded-lg text-slate-700 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors cursor-pointer"
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
                        class="h-8 px-2.5 text-xs font-medium text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer"
                    >Reset</button>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="px-5 py-3 w-12"></th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Pengarang</th>
                        <th class="px-4 py-3 hidden md:table-cell">Kategori</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ebooks as $b)
                        @php
                            $coverSrc = $b->cover_image;
                            $pdfSrc   = $b->file_url ? Storage::disk('public')->url($b->file_url) : null;
                            $isActive = $b->is_mingguan;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $isActive ? 'bg-emerald-50/40' : '' }}">

                            {{-- Cover --}}
                            <td class="px-5 py-3">
                                <div class="w-8 h-11 rounded overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs flex items-center justify-center shrink-0">
                                    @if($coverSrc)
                                        <img src="{{ $coverSrc }}" alt="{{ $b->judul }}" class="w-full h-full object-cover" loading="lazy">
                                    @elseif($pdfSrc)
                                        <div class="pdf-cover-wrap relative w-full h-full overflow-hidden" data-pdf-url="{{ $pdfSrc }}">
                                            <div class="pdf-skeleton absolute inset-0 bg-slate-200 animate-pulse"></div>
                                            <img class="pdf-cover-img w-full h-full object-cover hidden" alt="{{ $b->judul }}">
                                        </div>
                                    @else
                                        <span class="text-[8px] font-bold text-slate-400">PDF</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Judul --}}
                            <td class="px-4 py-3 max-w-[280px]">
                                <span class="text-xs font-semibold text-slate-900 line-clamp-1 leading-snug block">
                                    {{ $b->judul }}
                                </span>
                                <span class="text-[11px] text-slate-400 sm:hidden truncate block mt-0.5">
                                    {{ $b->pengarang ?? '—' }}
                                </span>
                            </td>

                            {{-- Pengarang --}}
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="text-xs text-slate-500 truncate block max-w-[180px]">
                                    {{ $b->pengarang ?? '—' }}
                                </span>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200/60">
                                    {{ $b->kategori }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                @if($isActive)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Dipajang
                                    </span>
                                @else
                                    <button
                                        wire:click="setMingguan({{ $b->id }})"
                                        wire:confirm="Pilih &quot;{{ $b->judul }}&quot; sebagai E-Book Mingguan? E-Book yang sebelumnya aktif akan digantikan."
                                        type="button"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold text-[#0284c7] bg-sky-50 hover:bg-sky-100 border border-sky-200 transition-colors cursor-pointer"
                                    >
                                        Pilih
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center">
                                <p class="text-xs text-slate-400">Tidak ada e-book yang sesuai dengan pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($ebooks->hasPages())
            <div class="px-5 py-3 border-t border-slate-100">
                {{ $ebooks->links() }}
            </div>
        @endif

    </div>
</div>
