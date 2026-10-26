<div class="space-y-6">
    {{-- Top Bar / Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Kelola Artikel &amp; Berita</h1>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-sky-50 text-[#0284c7] border border-sky-200/60">
                    {{ number_format($totalNews) }} Artikel
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Publikasi siaran pers, artikel edukasi keamanan siber, agenda kegiatan, dan pengumuman kedinasan.</p>
        </div>
        <a href="{{ route('admin.informasi.create') }}" wire:navigate.hover class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition-colors cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Artikel</span>
        </a>
    </div>

    {{-- Search + Filters Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            {{-- Search Bar --}}
            <div class="md:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Cari judul artikel, topik, atau isi konten..."
                    class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                />
            </div>

            {{-- Kategori Filter --}}
            <div class="md:col-span-3">
                <select
                    wire:model.live="selectedCategory"
                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-700 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all cursor-pointer font-medium"
                >
                    <option value="Semua Kategori">Semua Kategori</option>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="md:col-span-3">
                <select
                    wire:model.live="selectedStatus"
                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-700 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all cursor-pointer font-medium"
                >
                    <option value="Semua Status">Semua Status</option>
                    <option value="Publik">Publik</option>
                    <option value="Draf">Draf</option>
                    <option value="Disembunyikan">Disembunyikan</option>
                </select>
            </div>
        </div>

        {{-- Active Filters indicator --}}
        @if($search !== '' || $selectedCategory !== 'Semua Kategori' || $selectedStatus !== 'Semua Status')
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Filter aktif:</span>
                    @if($search !== '')
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                            "{{ $search }}"
                        </span>
                    @endif
                    @if($selectedCategory !== 'Semua Kategori')
                        <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 px-2 py-0.5 rounded font-medium">
                            {{ $selectedCategory }}
                        </span>
                    @endif
                    @if($selectedStatus !== 'Semua Status')
                        <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-medium">
                            {{ $selectedStatus }}
                        </span>
                    @endif
                </div>
                <button
                    wire:click="$set('search', ''); $set('selectedCategory', 'Semua Kategori'); $set('selectedStatus', 'Semua Status');"
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
                <span>artikel dipilih</span>
            </div>
            <button
                wire:click="deleteSelected"
                wire:confirm="Hapus {{ count($selectedIds) }} artikel yang dipilih? Tindakan ini tidak dapat dibatalkan."
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
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Judul &amp; Artikel</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3 hidden md:table-cell">Tanggal Publikasi</th>
                        <th class="px-4 py-3 hidden lg:table-cell">Tayangan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($filteredNews as $n)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ in_array($n->id, $selectedIds) ? 'bg-sky-50/30' : '' }}">
                            {{-- Checkbox --}}
                            <td class="px-4 py-3.5 text-center">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedIds"
                                    value="{{ $n->id }}"
                                    class="w-4 h-4 rounded text-[#0284c7] border-slate-300 focus:ring-[#0284c7] cursor-pointer"
                                />
                            </td>

                            {{-- Image Thumbnail --}}
                            <td class="px-4 py-3.5">
                                <div class="w-14 h-9 rounded overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs flex items-center justify-center relative shrink-0">
                                    @if($n->gambar)
                                        <img
                                            src="{{ $n->gambar }}"
                                            alt="{{ $n->judul }}"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Judul & Ringkasan --}}
                            <td class="px-4 py-3.5 max-w-[280px]">
                                <span class="text-xs sm:text-sm font-semibold text-slate-900 line-clamp-2 leading-snug">
                                    {{ $n->judul }}
                                </span>
                                @if($n->ringkasan)
                                    <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">
                                        {{ $n->ringkasan }}
                                    </p>
                                @endif
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @foreach($n->kategori_list as $kat)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium border bg-slate-100 text-slate-700 border-slate-200/60">
                                            {{ $kat }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Tanggal Publish --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-500 hidden md:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $n->published_at ? $n->published_at->locale('id')->translatedFormat('d M Y') : '—' }}</span>
                                </div>
                            </td>

                            {{-- Views Count --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-700 hidden lg:table-cell">
                                <div class="flex items-center gap-1 font-semibold text-slate-900">
                                    <svg class="w-3.5 h-3.5 text-[#0284c7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>{{ number_format($n->jumlah_dilihat ?? 0) }}x</span>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($n->status === 'Publik')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Publik
                                    </span>
                                @elseif($n->status === 'Draf')
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
                                    <a href="{{ route('admin.informasi.edit', $n->id) }}" wire:navigate class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-[#0284c7] bg-sky-50 hover:bg-sky-100 border border-sky-200/60 rounded-lg transition-colors cursor-pointer" title="Edit Artikel">
                                        <span>Edit</span>
                                    </a>


                                    {{-- Delete --}}
                                    <button
                                        wire:click="deleteNews({{ $n->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan."
                                        type="button"
                                        class="inline-flex items-center gap-1 p-1.5 text-xs font-semibold text-rose-600 bg-rose-50/70 hover:bg-rose-100 border border-rose-200/60 rounded-lg transition-colors cursor-pointer"
                                        title="Hapus Artikel"
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
                                @if($search !== '' || $selectedCategory !== 'Semua Kategori' || $selectedStatus !== 'Semua Status')
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 mb-2.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 mb-0.5">Artikel Tidak Ditemukan</h3>
                                        <p class="text-xs text-slate-500 mb-3">Tidak ada artikel yang sesuai dengan filter atau kata kunci yang dipilih.</p>
                                        <button
                                            wire:click="$set('search', ''); $set('selectedCategory', 'Semua Kategori'); $set('selectedStatus', 'Semua Status');"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-[#0284c7] bg-sky-50 border border-sky-200 hover:bg-sky-100 transition-colors cursor-pointer"
                                        >
                                            Reset Semua Filter
                                        </button>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mb-2.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 mb-0.5">Belum Ada Artikel</h3>
                                        <p class="text-xs text-slate-500 mb-3">Mulai publikasikan artikel pengumuman atau edukasi persandian pertama.</p>
                                        <a
                                            href="{{ route('admin.informasi.create') }}"
                                            wire:navigate.hover
                                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#0284c7] hover:bg-[#0369a1] text-white shadow-xs transition-colors cursor-pointer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Tambah Artikel Pertama
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
                @if($filteredNews->total())
                    Menampilkan <span class="font-semibold text-slate-900">{{ $filteredNews->firstItem() }}</span>–<span class="font-semibold text-slate-900">{{ $filteredNews->lastItem() }}</span> dari <span class="font-semibold text-slate-900">{{ $filteredNews->total() }}</span> artikel
                @else
                    Tidak ada artikel untuk ditampilkan
                @endif
            </span>
            <div>
                {{ $filteredNews->links('vendor.pagination.simple-custom') }}
            </div>
        </div>
    </div>
</div>
