<div x-data x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })" class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-semibold text-[#0F172A]">Kelola E-Book</h2>
            <p class="text-xs text-[#64748B] mt-0.5">Kelola arsip dokumen digital dan e-book literasi keamanan siber.</p>
        </div>
        <a href="{{ route('admin.ebooks.create') }}" wire:navigate.hover
           class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg bg-[#0284C7] hover:bg-[#0369A1] text-white text-xs font-semibold transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah E-Book
        </a>
    </div>

    {{-- Bulk Action Bar --}}
    @if(count($selectedIds) > 0)
        <div class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg bg-sky-50 border border-sky-200">
            <div class="flex items-center gap-2 text-xs font-medium text-sky-800">
                <span class="w-5 h-5 rounded-full bg-[#0284C7] text-white flex items-center justify-center text-[11px] font-bold shrink-0">
                    {{ count($selectedIds) }}
                </span>
                e-book dipilih
            </div>
            <button wire:click="deleteSelected"
                    wire:confirm="Hapus {{ count($selectedIds) }} e-book yang dipilih? File PDF dan cover terkait juga akan dihapus permanen."
                    type="button"
                    class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-rose-700 text-xs font-semibold transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Terpilih
            </button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-xl border border-[#E2E8F0] overflow-hidden">

        {{-- Integrated Toolbar --}}
        <div class="px-4 py-3 border-b border-[#E2E8F0] flex flex-col sm:flex-row gap-2">
            {{-- Search --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-3.5 h-3.5 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search"
                       placeholder="Cari judul, topik, atau kata kunci e-book..."
                       class="w-full h-8 pl-8 pr-3 rounded-md border border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#0F172A] placeholder:text-[#94A3B8] focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none transition-colors" />
            </div>
            {{-- Filters --}}
            <div class="flex gap-2">
                <select wire:model.live="selectedCategory"
                        class="h-8 pl-3 pr-7 rounded-md border border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#334155] focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none cursor-pointer transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <select wire:model.live="selectedStatus"
                        class="h-8 pl-3 pr-7 rounded-md border border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#334155] focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none cursor-pointer transition-colors">
                    <option value="">Semua Status</option>
                    <option value="Publik">Publik</option>
                    <option value="Draf">Draf</option>
                    <option value="Disembunyikan">Disembunyikan</option>
                </select>
                @if($search || $selectedCategory || $selectedStatus)
                    <button wire:click="$set('search', ''); $set('selectedCategory', ''); $set('selectedStatus', '');"
                            class="h-8 px-2.5 rounded-md border border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#64748B] hover:text-[#0284C7] hover:border-[#0284C7] transition-colors whitespace-nowrap">
                        Reset
                    </button>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
                        <th class="py-2.5 px-4 w-10 text-center">
                            <input type="checkbox" wire:model.live="selectAll"
                                   class="w-4 h-4 rounded text-[#0284C7] border-[#E2E8F0] focus:ring-[#0284C7] cursor-pointer" />
                        </th>
                        <th class="py-2.5 px-3 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">Cover</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">E-Book</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">Kategori</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B] hidden md:table-cell">Tanggal</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B] hidden lg:table-cell">Statistik</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">Status</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F1F5F9]">
                    @forelse($ebooks as $b)
                        @php
                            $coverSrc = $b->cover_image;
                            $pdfSrc   = $b->file_url ? Storage::disk('public')->url($b->file_url) : null;
                        @endphp
                        <tr class="hover:bg-[#F8FAFC] transition-colors {{ in_array((string)$b->id, $selectedIds) ? 'bg-sky-50/40' : '' }}">

                            {{-- Checkbox --}}
                            <td class="py-3 px-4 text-center">
                                <input type="checkbox" wire:model.live="selectedIds" value="{{ $b->id }}"
                                       class="w-4 h-4 rounded text-[#0284C7] border-[#E2E8F0] focus:ring-[#0284C7] cursor-pointer" />
                            </td>

                            {{-- Cover --}}
                            <td class="py-3 px-3">
                                <div class="w-9 h-12 rounded overflow-hidden border border-[#E2E8F0] bg-[#F8FAFC] flex items-center justify-center relative shrink-0">
                                    @if($coverSrc)
                                        <img src="{{ $coverSrc }}" alt="{{ $b->judul }}"
                                             class="w-full h-full object-cover" loading="lazy">
                                    @elseif($pdfSrc)
                                        <div class="pdf-cover-wrap relative w-full h-full overflow-hidden flex items-center justify-center" data-pdf-url="{{ $pdfSrc }}">
                                            <div class="pdf-skeleton absolute inset-0 bg-slate-200 animate-pulse flex items-center justify-center">
                                                <svg class="w-3 h-3 animate-spin text-[#0284C7]" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                            <img class="pdf-cover-img w-full h-full object-cover hidden" alt="{{ $b->judul }}">
                                        </div>
                                    @else
                                        <span class="text-[9px] font-bold text-[#94A3B8]">PDF</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Judul & Pengarang --}}
                            <td class="py-3 px-4 max-w-[260px]">
                                <div class="flex items-start gap-1.5">
                                    <span class="font-semibold text-[#0F172A] line-clamp-2 leading-snug">{{ $b->judul }}</span>
                                    @if($b->is_mingguan)
                                        <span title="E-Book Mingguan" class="shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 mt-0.5 text-[11px] text-[#64748B]">
                                    <span class="truncate">{{ $b->pengarang }}</span>
                                    @if($b->tahun)
                                        <span>&bull;</span>
                                        <span>{{ $b->tahun }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($b->kategori)
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-[#F1F5F9] text-[#334155] border border-[#E2E8F0]">
                                        {{ $b->kategori }}
                                    </span>
                                @else
                                    <span class="text-[#94A3B8]">—</span>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td class="py-3 px-4 whitespace-nowrap text-[#64748B] hidden md:table-cell">
                                {{ $b->tanggal_upload }}
                            </td>

                            {{-- Statistik --}}
                            <td class="py-3 px-4 whitespace-nowrap hidden lg:table-cell">
                                <div class="flex flex-col gap-0.5 text-[11px]">
                                    <span class="font-semibold text-[#0F172A]">{{ number_format($b->jumlah_dibaca ?? 0) }}x dibaca</span>
                                    <span class="text-[#64748B]">{{ number_format($b->jumlah_unduhan ?? 0) }}x diunduh</span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($b->status === 'Publik')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Publik
                                    </span>
                                @elseif($b->status === 'Draf')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Draf
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Disembunyikan
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3 px-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.ebooks.edit', $b->id) }}" wire:navigate
                                       class="inline-flex items-center h-7 px-2.5 rounded-md border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-[#334155] text-xs font-medium transition-colors">
                                        Edit
                                    </a>
                                    <button wire:click="deleteEbook({{ $b->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus e-book ini? File PDF dan cover juga akan dihapus."
                                            type="button"
                                            class="p-1.5 rounded-md text-[#94A3B8] hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                @if($search || $selectedCategory || $selectedStatus)
                                    <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                        <div class="w-9 h-9 rounded-lg bg-[#F1F5F9] flex items-center justify-center text-[#94A3B8] mb-2.5">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-[#0F172A]">E-Book tidak ditemukan</p>
                                        <p class="text-xs text-[#64748B] mt-1 mb-3">Tidak ada e-book yang cocok dengan filter saat ini.</p>
                                        <button wire:click="$set('search',''); $set('selectedCategory',''); $set('selectedStatus','');"
                                                class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-[#0284C7] bg-sky-50 border border-sky-200 hover:bg-sky-100 transition-colors">
                                            Reset Filter
                                        </button>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                        <div class="w-9 h-9 rounded-lg bg-[#F1F5F9] text-[#94A3B8] flex items-center justify-center mb-2.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-[#0F172A]">Belum ada e-book</p>
                                        <p class="text-xs text-[#64748B] mt-1 mb-3">Mulai publikasikan modul literasi keamanan siber pertama.</p>
                                        <a href="{{ route('admin.ebooks.create') }}" wire:navigate
                                           class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-semibold bg-[#0284C7] hover:bg-[#0369A1] text-white transition-colors">
                                            + Tambah E-Book
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-[#E2E8F0] flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#F8FAFC]">
            <span class="text-xs text-[#64748B]">
                Menampilkan
                <span class="font-semibold text-[#0F172A]">{{ $ebooks->firstItem() ?? 0 }}</span>–<span class="font-semibold text-[#0F172A]">{{ $ebooks->lastItem() ?? 0 }}</span>
                dari <span class="font-semibold text-[#0F172A]">{{ $ebooks->total() }}</span> e-book
            </span>
            <div>{{ $ebooks->links('vendor.pagination.simple-custom') }}</div>
        </div>
    </div>
</div>
