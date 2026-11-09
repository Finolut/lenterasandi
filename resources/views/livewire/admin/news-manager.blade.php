<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-semibold text-[#0F172A]">Kelola Artikel & Berita</h2>
            <p class="text-xs text-[#64748B] mt-0.5">Publikasi siaran pers, artikel edukasi, dan pengumuman kedinasan.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            {{-- Sync Jatimprov Button Group --}}
            <div class="relative inline-flex" x-data="{ open: false }">
                <button type="button"
                        wire:click="syncJatimprovNews(25, 5, 1)"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 h-9 px-3 rounded-l-lg border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-[#334155] text-xs font-semibold transition-colors disabled:opacity-60"
                        title="Ambil 25 berita terbaru dari Diskominfo Jatim">
                    <svg wire:loading.remove wire:target="syncJatimprovNews" class="w-3.5 h-3.5 text-[#0284C7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <svg wire:loading wire:target="syncJatimprovNews" class="w-3.5 h-3.5 text-[#0284C7] animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="syncJatimprovNews">Tarik Jatimprov</span>
                    <span wire:loading wire:target="syncJatimprovNews">Menyinkronkan...</span>
                </button>
                <button type="button" @click="open = !open" @click.away="open = false"
                        wire:loading.attr="disabled"
                        class="h-9 px-2 rounded-r-lg border-y border-r border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-[#64748B] transition-colors cursor-pointer border-l border-l-[#F1F5F9]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown menu --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-full mt-1.5 w-60 bg-white rounded-xl shadow-lg border border-[#E2E8F0] py-1.5 z-50">
                    <div class="px-3 py-1.5 text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wider border-b border-[#F1F5F9]">
                        Jangkauan Sinkronisasi
                    </div>
                    @foreach([
                        ['label' => '15 Berita Terbaru', 'sub' => 'Hal 1 – 3', 'args' => [15, 3, 1]],
                        ['label' => '25 Berita (Rekomendasi)', 'sub' => 'Hal 1 – 5', 'args' => [25, 5, 1]],
                        ['label' => '50 Berita (Banyak)', 'sub' => 'Hal 1 – 10', 'args' => [50, 10, 1]],
                    ] as $opt)
                        <button type="button" @click="open = false"
                                wire:click="syncJatimprovNews({{ $opt['args'][0] }}, {{ $opt['args'][1] }}, {{ $opt['args'][2] }})"
                                class="w-full text-left px-3 py-2 hover:bg-[#F8FAFC] flex items-center justify-between cursor-pointer transition-colors">
                            <span class="text-xs font-medium text-[#0F172A]">{{ $opt['label'] }}</span>
                            <span class="text-[11px] text-[#94A3B8]">{{ $opt['sub'] }}</span>
                        </button>
                    @endforeach
                    <div class="my-1 border-t border-[#F1F5F9]"></div>
                    <div class="px-3 py-1 text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wider">Arsip Lebih Lama</div>
                    @foreach([
                        ['label' => 'Hal 4 – 8', 'sub' => '~30 berita', 'args' => [30, 5, 4]],
                        ['label' => 'Hal 8 – 12', 'sub' => '~30 berita', 'args' => [30, 5, 8]],
                    ] as $opt)
                        <button type="button" @click="open = false"
                                wire:click="syncJatimprovNews({{ $opt['args'][0] }}, {{ $opt['args'][1] }}, {{ $opt['args'][2] }})"
                                class="w-full text-left px-3 py-2 hover:bg-[#F8FAFC] flex items-center justify-between cursor-pointer transition-colors">
                            <span class="text-xs font-medium text-[#0F172A]">Tarik {{ $opt['label'] }}</span>
                            <span class="text-[11px] text-[#94A3B8]">{{ $opt['sub'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('admin.informasi.create') }}" wire:navigate.hover
               class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg bg-[#0284C7] hover:bg-[#0369A1] text-white text-xs font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Artikel
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session()->has('success'))
        <div class="flex items-center gap-2.5 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200/80 text-xs text-emerald-800">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session()->has('info'))
        <div class="flex items-center gap-2.5 px-4 py-3 rounded-lg bg-sky-50 border border-sky-200/80 text-xs text-sky-800">
            <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">{{ session('info') }}</span>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="flex items-center gap-2.5 px-4 py-3 rounded-lg bg-red-50 border border-red-200/80 text-xs text-red-800">
            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Bulk Action Bar --}}
    @if(count($selectedIds) > 0)
        <div class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg bg-sky-50 border border-sky-200">
            <div class="flex items-center gap-2 text-xs font-medium text-sky-800">
                <span class="w-5 h-5 rounded-full bg-[#0284C7] text-white flex items-center justify-center text-[11px] font-bold shrink-0">
                    {{ count($selectedIds) }}
                </span>
                artikel dipilih
            </div>
            <button wire:click="deleteSelected"
                    wire:confirm="Hapus {{ count($selectedIds) }} artikel yang dipilih? Tindakan ini tidak dapat dibatalkan."
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
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-3.5 h-3.5 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search"
                       placeholder="Cari judul artikel, topik, atau isi konten..."
                       class="w-full h-8 pl-8 pr-3 rounded-md border border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#0F172A] placeholder:text-[#94A3B8] focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none transition-colors" />
            </div>
            <div class="flex gap-2">
                <select wire:model.live="selectedCategory"
                        class="h-8 pl-3 pr-7 rounded-md border border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#334155] focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none cursor-pointer transition-colors">
                    <option value="Semua Kategori">Semua Kategori</option>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <select wire:model.live="selectedStatus"
                        class="h-8 pl-3 pr-7 rounded-md border border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#334155] focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none cursor-pointer transition-colors">
                    <option value="Semua Status">Semua Status</option>
                    <option value="Publik">Publik</option>
                    <option value="Draf">Draf</option>
                    <option value="Disembunyikan">Disembunyikan</option>
                </select>
                @if($search !== '' || $selectedCategory !== 'Semua Kategori' || $selectedStatus !== 'Semua Status')
                    <button wire:click="$set('search', ''); $set('selectedCategory', 'Semua Kategori'); $set('selectedStatus', 'Semua Status');"
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
                        <th class="py-2.5 px-3 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">Gambar</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">Artikel</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">Kategori</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B] hidden md:table-cell">Tanggal</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B] hidden lg:table-cell">Tayangan</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B]">Status</th>
                        <th class="py-2.5 px-4 text-[11px] font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F1F5F9]">
                    @forelse($filteredNews as $n)
                        <tr class="hover:bg-[#F8FAFC] transition-colors {{ in_array($n->id, $selectedIds) ? 'bg-sky-50/40' : '' }}">

                            {{-- Checkbox --}}
                            <td class="py-3 px-4 text-center">
                                <input type="checkbox" wire:model.live="selectedIds" value="{{ $n->id }}"
                                       class="w-4 h-4 rounded text-[#0284C7] border-[#E2E8F0] focus:ring-[#0284C7] cursor-pointer" />
                            </td>

                            {{-- Thumbnail --}}
                            <td class="py-3 px-3">
                                <div class="w-14 h-9 rounded overflow-hidden border border-[#E2E8F0] bg-[#F8FAFC] flex items-center justify-center shrink-0">
                                    @if($n->gambar)
                                        <img src="{{ $n->gambar }}" alt="{{ $n->judul }}"
                                             class="w-full h-full object-cover" loading="lazy">
                                    @else
                                        <svg class="w-4 h-4 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                            </td>

                            {{-- Judul --}}
                            <td class="py-3 px-4 max-w-[260px]">
                                <span class="font-semibold text-[#0F172A] line-clamp-2 leading-snug block">{{ $n->judul }}</span>
                                @if($n->ringkasan)
                                    <p class="text-[11px] text-[#64748B] mt-0.5 line-clamp-1">{{ $n->ringkasan }}</p>
                                @endif
                            </td>

                            {{-- Kategori --}}
                            <td class="py-3 px-4">
                                <div class="flex flex-wrap gap-1 max-w-[180px]">
                                    @foreach($n->kategori_list as $kat)
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-[#F1F5F9] text-[#334155] border border-[#E2E8F0]">
                                            {{ $kat }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td class="py-3 px-4 whitespace-nowrap text-[#64748B] hidden md:table-cell">
                                {{ $n->published_at ? $n->published_at->locale('id')->translatedFormat('d M Y') : '—' }}
                            </td>

                            {{-- Views --}}
                            <td class="py-3 px-4 whitespace-nowrap hidden lg:table-cell">
                                <span class="font-semibold text-[#0F172A]">{{ number_format($n->jumlah_dilihat ?? 0) }}x</span>
                            </td>

                            {{-- Status --}}
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($n->status === 'Publik')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Publik
                                    </span>
                                @elseif($n->status === 'Draf')
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
                                    <a href="{{ route('admin.informasi.edit', $n->id) }}" wire:navigate
                                       class="inline-flex items-center h-7 px-2.5 rounded-md border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-[#334155] text-xs font-medium transition-colors">
                                        Edit
                                    </a>
                                    <button wire:click="deleteNews({{ $n->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan."
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
                                @if($search !== '' || $selectedCategory !== 'Semua Kategori' || $selectedStatus !== 'Semua Status')
                                    <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                        <div class="w-9 h-9 rounded-lg bg-[#F1F5F9] flex items-center justify-center text-[#94A3B8] mb-2.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-[#0F172A]">Artikel tidak ditemukan</p>
                                        <p class="text-xs text-[#64748B] mt-1 mb-3">Tidak ada artikel yang sesuai dengan filter saat ini.</p>
                                        <button wire:click="$set('search', ''); $set('selectedCategory', 'Semua Kategori'); $set('selectedStatus', 'Semua Status');"
                                                class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-[#0284C7] bg-sky-50 border border-sky-200 hover:bg-sky-100 transition-colors">
                                            Reset Filter
                                        </button>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                        <div class="w-9 h-9 rounded-lg bg-[#F1F5F9] text-[#94A3B8] flex items-center justify-center mb-2.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-[#0F172A]">Belum ada artikel</p>
                                        <p class="text-xs text-[#64748B] mt-1 mb-3">Mulai publikasikan artikel edukasi persandian pertama.</p>
                                        <a href="{{ route('admin.informasi.create') }}" wire:navigate.hover
                                           class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-semibold bg-[#0284C7] hover:bg-[#0369A1] text-white transition-colors">
                                            + Tambah Artikel
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
                @if($filteredNews->total())
                    Menampilkan
                    <span class="font-semibold text-[#0F172A]">{{ $filteredNews->firstItem() }}</span>–<span class="font-semibold text-[#0F172A]">{{ $filteredNews->lastItem() }}</span>
                    dari <span class="font-semibold text-[#0F172A]">{{ $filteredNews->total() }}</span> artikel
                @else
                    Tidak ada artikel untuk ditampilkan
                @endif
            </span>
            <div>{{ $filteredNews->links('vendor.pagination.simple-custom') }}</div>
        </div>
    </div>
</div>
