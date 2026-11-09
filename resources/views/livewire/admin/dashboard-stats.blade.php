<div class="space-y-6" x-data x-init="$nextTick(() => { if (window.renderPdfCovers) window.renderPdfCovers(); })">

    {{-- 1. Compact Banner --}}
    <div class="rounded-xl bg-[#083575] text-white px-6 py-4 sm:px-7 sm:py-5 border border-[#0b489a]">
        <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white leading-tight">
            Dashboard Administrasi Lentera Sandi
        </h1>
        <p class="text-xs text-slate-300 mt-0.5 max-w-2xl">
            Pusat pengelolaan e-book, artikel, dan verifikasi permohonan akses publik.
        </p>
    </div>

    {{-- 2. KPI Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total E-Book --}}
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Total E-Book</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284c7] flex items-center justify-center border border-sky-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalEbook) }}</div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">{{ $ebookPublik }} publik</span>
                <span class="text-slate-400">{{ $ebookDraf }} draf</span>
            </div>
        </div>

        {{-- Card 2: Total Artikel --}}
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Total Artikel</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalInformasi) }}</div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">{{ $informasiPublik }} publik</span>
                <span class="text-slate-400">{{ $informasiDraf }} draf</span>
            </div>
        </div>

        {{-- Card 3: Total Dibaca E-Book --}}
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Dibaca E-Book</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalPembaca) }}</div>
            <div class="mt-2 pt-2 border-t border-slate-100 text-[11px]">
                <span class="text-slate-500">Total kali dibaca</span>
            </div>
        </div>

        {{-- Card 4: Total Dilihat Artikel --}}
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Dilihat Artikel</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalViews) }}</div>
            <div class="mt-2 pt-2 border-t border-slate-100 text-[11px]">
                <span class="text-slate-500">Total kali dilihat</span>
            </div>
        </div>
    </div>

    {{-- 3. Permohonan Akses Terbaru --}}
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Permohonan Akses Terbaru</h2>
                <p class="text-xs text-slate-500 mt-0.5">5 permohonan terkini, prioritas Pending</p>
            </div>
            <a
                href="{{ route('admin.pesan.index') }}"
                wire:navigate
                class="text-xs font-semibold text-[#0284c7] hover:text-[#0369a1] hover:underline flex items-center gap-1"
            >
                <span>Lihat Semua @if(($unreadPesan ?? 0) > 0)<span class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">{{ $unreadPesan }}</span>@endif</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($recentMessages->isEmpty())
            <div class="py-10 px-6 flex flex-col items-center justify-center text-center">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-900">Belum ada permohonan akses</p>
                <p class="text-xs text-slate-500 mt-0.5">Permohonan dari publik akan muncul di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-5">Pemohon</th>
                            <th class="py-3 px-4 hidden sm:table-cell">Dokumen</th>
                            <th class="py-3 px-4 hidden md:table-cell">Tanggal</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentMessages as $msg)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-5">
                                    <div class="font-semibold text-slate-900">{{ $msg->nama_lengkap }}</div>
                                    @if($msg->instansi)
                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $msg->instansi }}</div>
                                    @endif
                                    {{-- Mobile: show dokumen & tanggal inline --}}
                                    <div class="sm:hidden text-[11px] text-slate-500 mt-0.5">
                                        {{ $msg->informasi?->judul ? \Illuminate\Support\Str::limit($msg->informasi->judul, 40) : '—' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 hidden sm:table-cell">
                                    <span class="text-slate-700 line-clamp-1 max-w-[200px] block" title="{{ $msg->informasi?->judul }}">
                                        {{ $msg->informasi?->judul ? \Illuminate\Support\Str::limit($msg->informasi->judul, 50) : '—' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 hidden md:table-cell text-slate-600">
                                    {{ $msg->created_at->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($msg->status === 'Pending')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @elseif($msg->status === 'Disetujui')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- 4. E-Book Terbaru --}}
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">E-Book Terbaru</h2>
                <p class="text-xs text-slate-500 mt-0.5">5 e-book terakhir yang diunggah</p>
            </div>
            <a
                href="{{ route('admin.ebooks.index') }}"
                wire:navigate
                class="text-xs font-semibold text-[#0284c7] hover:text-[#0369a1] hover:underline flex items-center gap-1"
            >
                <span>Lihat Semua E-Book ({{ $totalEbook }})</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($recentEbooks->isEmpty())
            <div class="py-10 px-6 flex flex-col items-center justify-center text-center">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-900">Belum ada e-book</p>
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
                            <th class="py-3 px-4 hidden sm:table-cell">Kategori</th>
                            <th class="py-3 px-4 hidden md:table-cell">Tanggal Unggah</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentEbooks as $ebook)
                            @php
                                $cover = $ebook->cover_image ?? null;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-5">
                                    <div class="flex items-center gap-3">
                                        {{-- Cover thumbnail --}}
                                        <div class="w-8 h-11 rounded bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($cover)
                                                <img
                                                    src="{{ $cover }}"
                                                    alt="{{ $ebook->judul }}"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                    decoding="async"
                                                >
                                            @else
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-semibold text-slate-900 line-clamp-1 block" title="{{ $ebook->judul }}">
                                                {{ $ebook->judul }}
                                            </span>
                                            {{-- Mobile: kategori & tanggal inline --}}
                                            <div class="sm:hidden text-[11px] text-slate-500 mt-0.5">
                                                {{ $ebook->kategori ?? '—' }} &bull; {{ $ebook->created_at->locale('id')->translatedFormat('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 hidden sm:table-cell">
                                    @if($ebook->kategori)
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium border border-slate-200/80 text-slate-700 bg-slate-50">
                                            {{ $ebook->kategori }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 hidden md:table-cell text-slate-600">
                                    {{ $ebook->created_at->locale('id')->translatedFormat('d M Y') }}
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
                                    <a
                                        href="{{ route('ebooks.show', $ebook->id) }}"
                                        target="_blank"
                                        class="p-1 rounded text-slate-500 hover:text-[#0284c7] hover:bg-slate-100 transition-colors inline-flex"
                                        title="Lihat Detail"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
