<div 
    x-data="{
        showModal: @entangle('showGenerateModal'),
        openGenerate() {
            $wire.set('formInformasiId', null, false);
            $wire.set('formJumlah', 1, false);
            $wire.set('formCatatan', '', false);
            $wire.set('newlyGeneratedTokens', [], false);
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        }
    }"
    class="space-y-6"
>
    {{-- Header Page --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Token Akses Berkas Pendukung</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola token sekali pakai untuk mengunduh lampiran/berkas pendukung artikel.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button
                type="button"
                @click="openGenerate()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Generate Token Baru</span>
            </button>
        </div>
    </div>

    {{-- Flash Notification --}}
    @if($flashMessage)
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-xs sm:text-sm text-emerald-800 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ $flashMessage }}</span>
            </div>
            <button wire:click="$set('flashMessage', '')" class="text-emerald-500 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Token Dibuat</span>
                <div class="text-2xl font-bold text-slate-900 mt-1 tracking-tight">{{ number_format($totalTokens) }}</div>
            </div>
            <div class="w-9 h-9 rounded-lg bg-sky-50 text-[#0284c7] border border-sky-100 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
        </div>

        {{-- Tersedia --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Token Tersedia (1x Pakai)</span>
                <div class="text-2xl font-bold text-emerald-800 mt-1 tracking-tight">{{ number_format($availableTokens) }}</div>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Sudah Digunakan --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Sudah Digunakan (Hangus)</span>
                <div class="text-2xl font-bold text-slate-700 mt-1 tracking-tight">{{ number_format($usedTokens) }}</div>
            </div>
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs">
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            {{-- Search Input --}}
            <div class="relative flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari kode token, judul artikel, atau catatan pemohon..."
                    class="w-full pl-9 pr-4 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none"
                />
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Status & Artikel Filter --}}
            <div class="flex items-center gap-2 flex-wrap">
                <select
                    wire:model.live="selectedStatus"
                    class="px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-700 focus:border-[#0284c7] outline-none cursor-pointer font-medium"
                >
                    <option value="Semua">Semua Status</option>
                    <option value="Tersedia">Tersedia (Belum Dipakai)</option>
                    <option value="Sudah Digunakan">Sudah Digunakan</option>
                </select>

                {{-- Artikel Filter --}}
                <select
                    wire:model.live="selectedInformasiId"
                    class="px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-700 focus:border-[#0284c7] outline-none cursor-pointer font-medium max-w-[220px] truncate"
                >
                    <option value="">Semua Berkas Artikel</option>
                    @foreach($articlesWithFiles as $art)
                        <option value="{{ $art->id }}">{{ $art->judul }}</option>
                    @endforeach
                </select>

                {{-- Bulk Delete Button --}}
                @if(count($selectedIds) > 0)
                    <button
                        type="button"
                        wire:click="deleteSelected"
                        wire:confirm="Yakin ingin menghapus {{ count($selectedIds) }} token yang dipilih?"
                        class="px-3 py-2 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors cursor-pointer shrink-0"
                    >
                        Hapus ({{ count($selectedIds) }})
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Tokens Table --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="w-10 px-4 py-3 text-center">
                            <input
                                type="checkbox"
                                wire:model.live="selectAll"
                                class="w-4 h-4 rounded text-[#0284c7] border-slate-300 focus:ring-[#0284c7] cursor-pointer"
                            />
                        </th>
                        <th class="px-4 py-3">Kode Token</th>
                        <th class="px-4 py-3">Artikel &amp; Berkas</th>
                        <th class="px-4 py-3">Catatan / Penerima</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Dibuat Oleh</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tokens as $t)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ in_array($t->id, $selectedIds) ? 'bg-sky-50/30' : '' }}">
                            {{-- Checkbox --}}
                            <td class="px-4 py-3.5 text-center">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedIds"
                                    value="{{ $t->id }}"
                                    class="w-4 h-4 rounded text-[#0284c7] border-slate-300 focus:ring-[#0284c7] cursor-pointer"
                                />
                            </td>

                            {{-- Token Code with Copy Button --}}
                            <td class="px-4 py-3.5">
                                <div 
                                    x-data="{ copied: false }" 
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md font-mono font-semibold text-xs {{ $t->is_used ? 'bg-slate-100 text-slate-400 line-through' : 'bg-slate-900 text-emerald-400' }}"
                                >
                                    <span>{{ $t->token }}</span>
                                    <button
                                        type="button"
                                        @click="navigator.clipboard.writeText('{{ $t->token }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="hover:text-white cursor-pointer transition-colors p-0.5"
                                        title="Salin Token"
                                    >
                                        <template x-if="!copied">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </template>
                                        <template x-if="copied">
                                            <span class="text-[10px] text-emerald-300 font-sans font-bold">✓</span>
                                        </template>
                                    </button>
                                </div>
                            </td>

                            {{-- Artikel & Berkas --}}
                            <td class="px-4 py-3.5 max-w-[280px]">
                                @if($t->informasi)
                                    <div class="font-medium text-slate-900 truncate" title="{{ $t->informasi->judul }}">
                                        {{ $t->informasi->judul }}
                                    </div>
                                    <div class="text-[11px] text-[#0284c7] truncate flex items-center gap-1 mt-0.5">
                                        <span>📁 {{ $t->informasi->file_pendukung_nama ?? 'Berkas Pendukung' }}</span>
                                        @if($t->informasi->file_pendukung_ukuran)
                                            <span class="text-slate-500">({{ $t->informasi->file_pendukung_ukuran }})</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Artikel telah dihapus</span>
                                @endif
                            </td>

                            {{-- Catatan / Pemohon --}}
                            <td class="px-4 py-3.5 max-w-[200px]">
                                @if($t->permohonan)
                                    <div class="font-medium text-slate-900 truncate">{{ $t->permohonan->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $t->permohonan->instansi }}</div>
                                @elseif($t->catatan)
                                    <span class="text-slate-700 font-medium">{{ $t->catatan }}</span>
                                @else
                                    <span class="text-slate-400 italic">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($t->is_used)
                                    <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Sudah Digunakan</span>
                                    </div>
                                    @if($t->used_at)
                                        <div class="text-[10px] text-slate-400 mt-0.5 font-mono">
                                            {{ $t->used_at->translatedFormat('d M Y H:i') }}
                                        </div>
                                    @endif
                                @else
                                    <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Tersedia (1x Pakai)</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Dibuat Oleh --}}
                            <td class="px-4 py-3.5 whitespace-nowrap text-slate-600">
                                <div>{{ $t->creator->name ?? 'Admin' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $t->created_at->translatedFormat('d M Y') }}</div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <button
                                    type="button"
                                    wire:click="deleteToken({{ $t->id }})"
                                    wire:confirm="Yakin ingin menghapus token {{ $t->token }}?"
                                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                    title="Hapus Token"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                <span class="font-medium text-xs text-slate-500">Belum ada token akses berkas pendukung.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tokens->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/75">
                {{ $tokens->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL GENERATE TOKEN BARU --}}
    <div 
        x-show="showModal"
        x-cloak
        x-transition.opacity.duration.150ms
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;"
        @keydown.escape.window="closeModal()"
    >
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="closeModal()"></div>

        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden p-6 space-y-4">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284c7] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Generate Token Akses</h3>
                    </div>
                    <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer p-1">✕</button>
                </div>

                    {{-- Jika Ada Token Yang Baru Saja Dibuat (Result Screen) --}}
                    @if(count($newlyGeneratedTokens) > 0)
                        <div class="space-y-3">
                            <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                                <span class="font-bold">✓ Berhasil digenerate!</span> Token berikut dapat langsung disalin dan diberikan kepada pemohon (berlaku sekali pakai).
                            </div>

                            <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                @foreach($newlyGeneratedTokens as $tokenCode)
                                    <div 
                                        x-data="{ copied: false }" 
                                        class="p-2.5 bg-slate-50 border border-slate-300 rounded-lg flex items-center justify-between gap-2"
                                    >
                                        <span class="font-mono font-bold text-[#0284c7] text-sm tracking-wider">{{ $tokenCode }}</span>
                                        <button
                                            type="button"
                                            @click="navigator.clipboard.writeText('{{ $tokenCode }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                                            :class="copied ? 'bg-emerald-600 text-white' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-300'"
                                        >
                                            <span x-text="copied ? '✓ Tersalin!' : 'Salin Token'"></span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button
                                    type="button"
                                    @click="closeModal()"
                                    class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                                >
                                    Selesai
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- Form Inputs --}}
                        <div class="space-y-3.5">
                            {{-- Pilih Artikel --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Pilih Artikel Berkas <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    wire:model="formInformasiId"
                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:border-[#0284c7] outline-none cursor-pointer"
                                >
                                    <option value="">-- Pilih Artikel Yang Memiliki Berkas --</option>
                                    @foreach($articlesWithFiles as $art)
                                        <option value="{{ $art->id }}">
                                            {{ $art->judul }} (📁 {{ $art->file_pendukung_nama ?? 'Berkas' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('formInformasiId') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Jumlah Token --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Jumlah Token Yang Ingin Dibuat <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    min="1"
                                    max="20"
                                    wire:model="formJumlah"
                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:border-[#0284c7] outline-none"
                                />
                                <span class="text-[11px] text-slate-500 mt-0.5 block">Tiap token berlaku sekali pakai (1 user/unduhan).</span>
                                @error('formJumlah') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Catatan / Penerima --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Catatan Penerima / Keperluan
                                </label>
                                <input
                                    type="text"
                                    placeholder="Contoh: Untuk Dinas Kominfo Kota X / Bpk. Rudi"
                                    wire:model="formCatatan"
                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:border-[#0284c7] outline-none"
                                />
                                @error('formCatatan') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Actions --}}
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button
                                    type="button"
                                    @click="closeModal()"
                                    class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button
                                    type="button"
                                    wire:click="generateTokens"
                                    wire:loading.attr="disabled"
                                    wire:target="generateTokens"
                                    class="px-5 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer flex items-center gap-1.5 disabled:opacity-50"
                                >
                                    <span wire:loading.remove wire:target="generateTokens">Generate Sekarang &rarr;</span>
                                    <span wire:loading wire:target="generateTokens">Memproses...</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
