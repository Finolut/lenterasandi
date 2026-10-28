<div>
    {{-- Header & Metrics Summary --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pesan Masuk &amp; Permohonan Akses</h1>
                @if($totalUnread > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                        {{ $totalUnread }} Belum Dibaca
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar permohonan akses berkas pendukung artikel dari pengunjung portal.</p>
        </div>

        @if($totalUnread > 0)
            <button
                type="button"
                wire:click="markAllAsRead"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-xs font-semibold text-slate-700 shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4 text-[#0284c7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Tandai Semua Dibaca</span>
            </button>
        @endif
    </div>

    {{-- Session Flash Message --}}
    @if(session()->has('success'))
        <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-xs sm:text-sm text-emerald-800 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            {{-- Search Bar --}}
            <div class="md:col-span-8 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Cari nama pemohon, instansi, email, telepon, atau judul..."
                    class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                />
            </div>

            {{-- Status Filter --}}
            <div class="md:col-span-4">
                <select
                    wire:model.live="selectedStatus"
                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-700 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all cursor-pointer font-medium"
                >
                    <option value="Semua">Semua Status ({{ $totalAll }})</option>
                    <option value="Belum Dibaca">Belum Dibaca ({{ $totalUnread }})</option>
                    <option value="Menunggu">Menunggu Verifikasi ({{ $totalPending }})</option>
                    <option value="Disetujui">Disetujui ({{ $totalApproved }})</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
        </div>

        {{-- Active Filters indicator --}}
        @if($search !== '' || $selectedStatus !== 'Semua')
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Filter aktif:</span>
                    @if($search !== '')
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                            "{{ $search }}"
                        </span>
                    @endif
                    @if($selectedStatus !== 'Semua')
                        <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 px-2 py-0.5 rounded font-medium">
                            Status: {{ $selectedStatus }}
                        </span>
                    @endif
                </div>
                <button
                    wire:click="$set('search', ''); $set('selectedStatus', 'Semua');"
                    class="text-xs font-semibold text-[#0284c7] hover:text-[#0369a1] hover:underline cursor-pointer"
                >
                    Reset Filter
                </button>
            </div>
        @endif
    </div>

    {{-- Bulk Action Bar --}}
    @if(count($selectedIds) > 0)
        <div class="mb-4 bg-sky-50 border border-sky-200 text-sky-800 rounded-lg px-4 py-2.5 flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2 text-xs font-medium">
                <span class="w-5 h-5 rounded-full bg-[#0284c7] text-white flex items-center justify-center text-[11px] font-bold">
                    {{ count($selectedIds) }}
                </span>
                <span>pesan dipilih</span>
            </div>
            <button
                wire:click="deleteSelected"
                wire:confirm="Hapus {{ count($selectedIds) }} pesan yang dipilih? Tindakan ini tidak dapat dibatalkan."
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
                        <th class="px-4 py-3">Pemohon</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3 hidden md:table-cell">Artikel &amp; Berkas</th>
                        <th class="px-4 py-3 hidden lg:table-cell">Waktu Pengajuan</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $msg->dibaca_at === null ? 'bg-sky-50/20' : '' }}">
                            {{-- Checkbox --}}
                            <td class="px-4 py-3.5 text-center">
                                <input
                                    type="checkbox"
                                    value="{{ $msg->id }}"
                                    wire:model.live="selectedIds"
                                    class="w-4 h-4 rounded text-[#0284c7] border-slate-300 focus:ring-[#0284c7] cursor-pointer"
                                />
                            </td>

                            {{-- Pemohon (Plain Text Tanpa Tombol/Navigasi) --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    @if($msg->dibaca_at === null)
                                        <span class="w-2 h-2 rounded-full bg-[#0284c7] shrink-0" title="Pesan Baru"></span>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="font-semibold text-xs sm:text-sm text-slate-900 select-text">
                                            {{ $msg->nama_lengkap }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            <span class="truncate max-w-[180px]">{{ $msg->instansi }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kontak (Plain Text Tanpa Link Mailto/Navigasi) --}}
                            <td class="px-4 py-3.5 text-xs text-slate-700">
                                <div class="flex items-center gap-1.5 font-medium select-text">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="truncate max-w-[170px]" title="{{ $msg->email }}">{{ $msg->email }}</span>
                                </div>
                                @if($msg->telepon)
                                    <div class="flex items-center gap-1.5 text-slate-500 mt-0.5 font-mono text-[11px] select-text">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span>{{ $msg->telepon }}</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Artikel & Berkas (Plain Text Tanpa Tombol/Navigasi) --}}
                            <td class="px-4 py-3.5 text-xs hidden md:table-cell">
                                @if($msg->informasi)
                                    <div class="font-medium text-slate-800 line-clamp-1 max-w-[220px]" title="{{ $msg->informasi->judul }}">
                                        {{ $msg->informasi->judul }}
                                    </div>
                                    @if($msg->informasi->file_pendukung_nama)
                                        <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5 truncate max-w-[200px]" title="{{ $msg->informasi->file_pendukung_nama }}">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="truncate">{{ $msg->informasi->file_pendukung_nama }}</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Artikel telah dihapus</span>
                                @endif
                            </td>

                            {{-- Waktu --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-500 hidden lg:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $msg->created_at ? $msg->created_at->diffForHumans() : '—' }}</span>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        wire:click="viewMessage({{ $msg->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-[#0284c7] bg-sky-50 hover:bg-sky-100 border border-sky-200/60 rounded-lg transition-colors cursor-pointer"
                                        title="Buka Detail Pesan"
                                    >
                                        <span>Detail</span>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="deleteMessage({{ $msg->id }})"
                                        wire:confirm="Hapus pesan permohonan dari {{ $msg->nama_lengkap }}?"
                                        class="inline-flex items-center gap-1 p-1.5 text-xs font-semibold text-rose-600 bg-rose-50/70 hover:bg-rose-100 border border-rose-200/60 rounded-lg transition-colors cursor-pointer"
                                        title="Hapus Pesan"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mb-2.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 mb-0.5">Belum Ada Pesan Masuk</h3>
                                    <p class="text-xs text-slate-500">Permohonan akses berkas dari pengunjung portal akan tampil di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        <div class="px-5 py-3.5 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/75">
            <span class="text-xs text-slate-500">
                @if($messages->total())
                    Menampilkan {{ $messages->firstItem() }} - {{ $messages->lastItem() }} dari {{ $messages->total() }} pesan masuk
                @endif
            </span>
            <div>
                {{ $messages->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL PERMOHONAN AKSES (DESAIN BERSIH & MUDAH DIBACA) --}}
    @if($viewingMessageId && $activeMessage)
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition.opacity.duration.150ms
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="detail-title"
            role="dialog"
            aria-modal="true"
            @keydown.escape.window="show = false; $wire.closeModal()"
        >
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" @click="show = false; $wire.closeModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-xl border border-slate-100">
                    
                    {{-- Header Modal --}}
                    <div class="px-6 py-4.5 bg-white border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-sky-50 text-[#0284c7] flex items-center justify-center shrink-0 border border-sky-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-bold text-slate-900" id="detail-title">
                                        Permohonan Akses #{{ $activeMessage->id }}
                                    </h3>
                                    @if($activeMessage->status === 'Disetujui')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✓ Disetujui
                                        </span>
                                    @elseif($activeMessage->status === 'Menunggu')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            ⏳ Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            ✕ Ditolak
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $activeMessage->tanggal_pengajuan }}</p>
                            </div>
                        </div>

                        <button 
                            @click="show = false; $wire.closeModal()" 
                            type="button" 
                            class="text-slate-400 hover:text-slate-600 rounded-lg p-1.5 hover:bg-slate-100 transition-colors cursor-pointer" 
                            title="Tutup Modal"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Body Modal --}}
                    <div class="p-6 space-y-4">
                        @if($flashMessage)
                            <div class="rounded-xl bg-emerald-50 border border-emerald-200/80 p-3 text-xs text-emerald-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="font-medium">{{ $flashMessage }}</span>
                            </div>
                        @endif

                        {{-- Section 1: Informasi Pemohon --}}
                        <div class="rounded-xl border border-slate-200/80 bg-slate-50/50 p-4">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-3">
                                Informasi Pemohon
                            </span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                                <div>
                                    <span class="text-slate-500 block text-[11px] mb-0.5">Nama Lengkap</span>
                                    <span class="font-semibold text-slate-900 text-sm">{{ $activeMessage->nama_lengkap }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px] mb-0.5">Instansi / Lembaga</span>
                                    <span class="font-medium text-slate-800">{{ $activeMessage->instansi ?: '—' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px] mb-0.5">Alamat Email</span>
                                    <span class="font-medium text-slate-800">{{ $activeMessage->email }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px] mb-0.5">Nomor WhatsApp</span>
                                    <span class="font-medium text-slate-800 font-mono">{{ $activeMessage->telepon ?: '—' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Berkas & Keperluan --}}
                        <div class="rounded-xl border border-slate-200/80 bg-slate-50/50 p-4 space-y-3">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                                Berkas yang Dimohonkan
                            </span>

                            @if($activeMessage->informasi)
                                <div>
                                    <span class="text-slate-500 block text-[11px] mb-0.5">Judul Artikel</span>
                                    <span class="font-medium text-slate-900 text-xs sm:text-sm">
                                        {{ $activeMessage->informasi->judul }}
                                    </span>
                                </div>

                                @if($activeMessage->informasi->file_pendukung_nama)
                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-white border border-slate-200 text-xs">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <svg class="w-4 h-4 text-[#0284c7] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium text-slate-800 truncate">{{ $activeMessage->informasi->file_pendukung_nama }}</span>
                                        </div>
                                        @if($activeMessage->informasi->file_pendukung_url)
                                            <a 
                                                href="{{ Storage::disk('public')->url($activeMessage->informasi->file_pendukung_url) }}" 
                                                target="_blank" 
                                                class="text-[#0284c7] hover:underline font-semibold shrink-0 text-xs ml-2"
                                            >
                                                Unduh File &darr;
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="text-slate-400 italic text-xs">Artikel terkait telah dihapus.</div>
                            @endif

                            <div>
                                <span class="text-slate-500 block text-[11px] mb-1">Keperluan / Alasan Akses</span>
                                <div class="p-3 bg-white rounded-lg border border-slate-200 text-xs text-slate-700 leading-relaxed italic">
                                    &ldquo;{{ $activeMessage->keperluan ?: 'Tidak ada keterangan keperluan yang dilampirkan.' }}&rdquo;
                                </div>
                            </div>
                        </div>

                        {{-- Section 3: Token Akses & Aksi --}}
                        <div class="rounded-xl border p-4 {{ $activeMessage->status === 'Disetujui' ? 'bg-emerald-50/40 border-emerald-200' : ($activeMessage->status === 'Menunggu' ? 'bg-amber-50/40 border-amber-200' : 'bg-slate-50 border-slate-200') }}">
                            @if($activeMessage->status === 'Menunggu')
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <h5 class="text-xs font-bold text-amber-900">Konfirmasi Permohonan</h5>
                                        <p class="text-[11px] text-amber-700 mt-0.5">Setujui untuk membuat kode token akses sekali pakai bagi pemohon.</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button
                                            type="button"
                                            wire:click="updateStatus('Ditolak')"
                                            class="px-3 py-1.5 rounded-lg border border-rose-300 text-rose-700 hover:bg-rose-50 text-xs font-semibold transition-colors cursor-pointer"
                                        >
                                            ✕ Tolak
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="approveAndSendToken({{ $activeMessage->id }})"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer disabled:opacity-50"
                                        >
                                            <span wire:loading.remove wire:target="approveAndSendToken({{ $activeMessage->id }})">✓ Setujui Permohonan</span>
                                            <span wire:loading wire:target="approveAndSendToken({{ $activeMessage->id }})">Memproses...</span>
                                        </button>
                                    </div>
                                </div>
                            @elseif($activeMessage->status === 'Disetujui')
                                @if($activeMessage->tokens && $activeMessage->tokens->count() > 0)
                                    @php $lastTok = $activeMessage->tokens->last(); @endphp
                                    <div class="space-y-3" x-data="{ copied: false }">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-bold text-slate-800">Token Akses Berkas</span>
                                            @if(!$lastTok->is_used)
                                                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                                    Aktif (Sekali Pakai)
                                                </span>
                                            @else
                                                <span class="text-[11px] font-semibold text-slate-500 bg-slate-200 px-2 py-0.5 rounded-full">
                                                    Sudah Digunakan
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 p-3 rounded-lg bg-white border border-slate-200">
                                            <code class="font-mono text-base font-bold text-slate-900 tracking-wider text-center sm:text-left select-all">
                                                {{ $lastTok->token }}
                                            </code>

                                            <div class="flex items-center justify-end gap-2 shrink-0">
                                                @if($activeMessage->formatted_wa_number)
                                                    <a
                                                        href="{{ $activeMessage->getWhatsappShareUrl($lastTok->token) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors cursor-pointer"
                                                        title="Kirim kode token via WhatsApp ke pemohon"
                                                    >
                                                        <span>Kirim via WA &rarr;</span>
                                                    </a>
                                                @endif
                                                <button
                                                    type="button"
                                                    @click="navigator.clipboard.writeText('{{ $lastTok->token }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer"
                                                >
                                                    <span x-show="!copied">Salin Token</span>
                                                    <span x-show="copied" class="text-emerald-700 font-bold">✓ Disalin</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs text-rose-700 font-medium">Permohonan ini telah ditolak.</span>
                                    <button
                                        type="button"
                                        wire:click="updateStatus('Menunggu')"
                                        class="px-3 py-1 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition-colors cursor-pointer"
                                    >
                                        Kembalikan ke Menunggu
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Section 4: Catatan Admin --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Catatan Admin (Internal)</label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="text"
                                    wire:model="adminNotes"
                                    placeholder="Tulis catatan internal admin jika ada..."
                                    class="flex-1 px-3 py-2 text-xs bg-slate-50 focus:bg-white border border-slate-200 rounded-lg text-slate-900 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                                />
                                <button
                                    type="button"
                                    wire:click="saveDetails"
                                    class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors cursor-pointer shrink-0"
                                >
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
                        <button
                            type="button"
                            @click="show = false; $wire.closeModal()"
                            class="px-4 py-2 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-xs font-semibold text-slate-700 shadow-2xs transition-colors cursor-pointer"
                        >
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
