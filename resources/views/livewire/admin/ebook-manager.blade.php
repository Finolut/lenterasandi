<div x-data x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })" class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Kelola E-Book</h1>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-sky-50 text-[#0284c7] border border-sky-200/60">
                    {{ number_format($totalEbook) }} Koleksi
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola arsip dokumen digital, panduan teknis, dan e-book literasi keamanan digital.</p>
        </div>
        <a href="{{ route('admin.ebooks.create') }}" wire:navigate.hover class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition-colors cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah E-Book</span>
        </a>
    </div>

    {{-- Search & Filters Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            {{-- Search Input --}}
            <div class="md:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Cari judul, topik, atau kata kunci e-book..."
                    class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                />
            </div>

            {{-- Kategori Select --}}
            <div class="md:col-span-3">
                <select
                    wire:model.live="selectedCategory"
                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-700 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all cursor-pointer font-medium"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Select --}}
            <div class="md:col-span-3">
                <select
                    wire:model.live="selectedStatus"
                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-700 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all cursor-pointer font-medium"
                >
                    <option value="">Semua Status</option>
                    <option value="Publik">Publik</option>
                    <option value="Draf">Draf</option>
                    <option value="Disembunyikan">Disembunyikan</option>
                </select>
            </div>
        </div>

        {{-- Active Filters indicator --}}
        @if($search || $selectedCategory || $selectedStatus)
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Filter aktif:</span>
                    @if($search)
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                            "{{ $search }}"
                        </span>
                    @endif
                    @if($selectedCategory)
                        <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 px-2 py-0.5 rounded font-medium">
                            {{ $selectedCategory }}
                        </span>
                    @endif
                    @if($selectedStatus)
                        <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-medium">
                            {{ $selectedStatus }}
                        </span>
                    @endif
                </div>
                <button
                    wire:click="$set('search', ''); $set('selectedCategory', ''); $set('selectedStatus', '');"
                    class="text-xs font-semibold text-[#0284c7] hover:text-[#0369a1] hover:underline cursor-pointer"
                >
                    Reset Filter
                </button>
            </div>
        @endif
    </div>

    {{-- Bulk Action Bar --}}
    @if(count($selectedIds) > 0)
        <div class="bg-sky-50 border border-sky-200 text-sky-800 rounded-lg px-4 py-2.5 flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2 text-xs font-medium">
                <span class="w-5 h-5 rounded-full bg-[#0284c7] text-white flex items-center justify-center text-[11px] font-bold">
                    {{ count($selectedIds) }}
                </span>
                <span>e-book dipilih</span>
            </div>
            <button
                wire:click="deleteSelected"
                wire:confirm="Hapus {{ count($selectedIds) }} e-book yang dipilih? File PDF dan cover terkait juga akan dihapus permanen."
                type="button"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 bg-white border border-rose-200 hover:bg-rose-50 px-3 py-1.5 rounded-lg shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Hapus Terpilih</span>
            </button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3 w-10 text-center">
                            <input
                                type="checkbox"
                                wire:model.live="selectAll"
                                class="w-4 h-4 rounded text-[#0284c7] border-slate-300 focus:ring-[#0284c7] cursor-pointer"
                            />
                        </th>
                        <th class="px-4 py-3">Cover</th>
                        <th class="px-4 py-3">Informasi E-Book</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3 hidden md:table-cell">Tanggal</th>
                        <th class="px-4 py-3 hidden lg:table-cell">Unduhan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ebooks as $b)
                        @php
                            $coverSrc = $b->cover_image;
                            $pdfSrc   = $b->file_url ? Storage::disk('public')->url($b->file_url) : null;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ in_array((string)$b->id, $selectedIds) ? 'bg-sky-50/30' : '' }}">
                            {{-- Checkbox --}}
                            <td class="px-4 py-3.5 text-center">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedIds"
                                    value="{{ $b->id }}"
                                    class="w-4 h-4 rounded text-[#0284c7] border-slate-300 focus:ring-[#0284c7] cursor-pointer"
                                />
                            </td>

                            {{-- Cover with dynamic PDF.js extraction fallback --}}
                            <td class="px-4 py-3.5">
                                <div class="w-10 h-14 rounded overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs flex items-center justify-center relative shrink-0">
                                    @if($coverSrc)
                                        <img
                                            src="{{ $coverSrc }}"
                                            alt="Cover {{ $b->judul }}"
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
                                        <div class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400">
                                            <span class="text-[9px] font-bold text-slate-500">PDF</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Judul & Pengarang --}}
                            <td class="px-4 py-3.5 max-w-[280px]">
                                <span class="text-xs sm:text-sm font-semibold text-slate-900 line-clamp-2 leading-snug">
                                    {{ $b->judul }}
                                </span>
                                <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                    <span class="truncate">{{ $b->pengarang }}</span>
                                    @if($b->tahun)
                                        <span>&bull;</span>
                                        <span>{{ $b->tahun }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $b->kategori }}
                                </span>
                            </td>

                            {{-- Tanggal Upload --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-500 hidden md:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $b->tanggal_upload }}</span>
                                </div>
                            </td>

                            {{-- Unduhan --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-700 hidden lg:table-cell">
                                <div class="flex items-center gap-1 font-semibold text-slate-900">
                                    <svg class="w-3.5 h-3.5 text-[#0284c7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span>{{ number_format($b->jumlah_unduhan) }}x</span>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($b->status === 'Publik')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Publik
                                    </span>
                                @elseif($b->status === 'Draf')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Draf
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Disembunyikan
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.ebooks.edit', $b->id) }}" wire:navigate class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-[#0284c7] bg-sky-50 hover:bg-sky-100 border border-sky-200/60 rounded-lg transition-colors cursor-pointer" title="Edit E-Book">
                                        <span>Edit</span>
                                    </a>


                                    {{-- Delete --}}
                                    <button
                                        wire:click="deleteEbook({{ $b->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus e-book ini? File PDF dan cover juga akan dihapus."
                                        type="button"
                                        class="inline-flex items-center gap-1 p-1.5 text-xs font-semibold text-rose-600 bg-rose-50/70 hover:bg-rose-100 border border-rose-200/60 rounded-lg transition-colors cursor-pointer"
                                        title="Hapus E-Book"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                @if($search || $selectedCategory || $selectedStatus)
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 mb-2.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 mb-0.5">E-Book Tidak Ditemukan</h3>
                                        <p class="text-xs text-slate-500 mb-3">Tidak ada e-book yang cocok dengan filter atau kata kunci pencarian.</p>
                                        <button
                                            wire:click="$set('search',''); $set('selectedCategory',''); $set('selectedStatus','');"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-[#0284c7] bg-sky-50 border border-sky-200 hover:bg-sky-100 transition-colors cursor-pointer"
                                        >
                                            Reset Semua Filter
                                        </button>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mb-2.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 mb-0.5">Belum Ada E-Book</h3>
                                        <p class="text-xs text-slate-500 mb-3">Mulai publikasikan modul literasi keamanan siber dan persandian pertama.</p>
                                        <a
                                            href="{{ route('admin.ebooks.create') }}"
                                            wire:navigate
                                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#0284c7] hover:bg-[#0369a1] text-white shadow-xs transition-colors cursor-pointer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Tambah E-Book Pertama
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer + Pagination --}}
        <div class="px-5 py-3.5 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/75">
            <span class="text-xs text-slate-500">
                Menampilkan <span class="font-semibold text-slate-900">{{ $ebooks->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-900">{{ $ebooks->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-900">{{ $ebooks->total() }}</span> e-book
            </span>
            <div>
                {{ $ebooks->links('vendor.pagination.simple-custom') }}
            </div>
        </div>
    </div>
</div>
