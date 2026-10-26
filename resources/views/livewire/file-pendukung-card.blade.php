<div>
    @if($informasi->hasFilePendukung())
        {{-- CARD BERKAS PENDUKUNG SIMPEL & BERSIH --}}
        <div class="my-6 p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0f172a] shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all">
            @php
                $ext = strtolower($informasi->file_pendukung_tipe ?? pathinfo($informasi->file_pendukung_nama ?? '', PATHINFO_EXTENSION));
                $badgeClass = match(true) {
                    in_array($ext, ['pdf']) => 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200/80 dark:border-rose-900/60',
                    in_array($ext, ['doc', 'docx']) => 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900/60',
                    in_array($ext, ['xls', 'xlsx', 'csv']) => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-900/60',
                    in_array($ext, ['zip', 'rar', '7z', 'tar', 'gz']) => 'bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200/80 dark:border-amber-900/60',
                    default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
                };
            @endphp

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                {{-- Info Berkas --}}
                <div class="flex items-center gap-3.5 min-w-0">
                    {{-- File Icon Badge --}}
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex flex-col items-center justify-center shrink-0 font-bold text-[10px] tracking-wide {{ $badgeClass }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="uppercase leading-none text-[9px]">{{ $ext ?: 'FILE' }}</span>
                    </div>

                    {{-- Nama Berkas & Status Ringkas --}}
                    <div class="min-w-0">
                        <h4 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-white truncate" title="{{ $informasi->file_pendukung_nama ?? 'Dokumen Pendukung' }}">
                            {{ $informasi->file_pendukung_nama ?? 'Dokumen Pendukung' }}
                        </h4>
                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            <span>{{ $informasi->file_pendukung_ukuran ?? 'Tersedia' }}</span>
                            <span>•</span>
                            @if($informasi->filePendukungIsPublik())
                                <span class="text-emerald-600 dark:text-emerald-400 font-medium">Akses Publik</span>
                            @else
                                <span class="text-amber-600 dark:text-amber-400 font-medium">Perlu Izin</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi (Hanya 1 Tombol di Luar) --}}
                <div class="flex items-center gap-2 shrink-0">
                    @if($informasi->filePendukungIsPublik())
                        <a
                            href="{{ route('informasi.download-berkas', $informasi->id) }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-semibold shadow-xs transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Unduh</span>
                        </a>
                    @else
                        <button
                            type="button"
                            wire:click="openModal('form')"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-[#083575] hover:bg-[#06295c] dark:bg-sky-600 dark:hover:bg-sky-500 text-white text-xs sm:text-sm font-semibold shadow-xs transition-colors cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5 text-sky-200 dark:text-sky-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Ajukan Akses</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Pesan Status / Notifikasi --}}
            @if(session()->has('token_success'))
                <div class="mt-3 p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('token_success') }}</span>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mt-3 p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-700 dark:text-rose-300 font-medium">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- MODAL AKSES BERKAS --}}
        @if($showModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-slate-950/70 dark:bg-black/80 backdrop-blur-xs transition-opacity" wire:click="closeModal"></div>

                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-[#0f172a] text-left shadow-2xl transition-all w-full max-w-lg border border-slate-200 dark:border-slate-800">
                        {{-- Modal Header --}}
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                            <h3 class="font-bold text-slate-900 dark:text-white text-base">
                                {{ $modalTab === 'token' ? 'Masukkan Token Akses' : 'Pengajuan Akses Berkas' }}
                            </h3>
                            <button wire:click="closeModal" type="button" class="text-slate-400 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg p-1 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="p-6">
                            @if($modalTab === 'token')
                                {{-- VIEW INPUT TOKEN --}}
                                <form wire:submit="verifyAndDownloadToken" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                                            Kode Token Akses <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            wire:model="tokenInput"
                                            placeholder="CONTOH: LS-ABCD-1234"
                                            autofocus
                                            style="text-transform: uppercase;"
                                            class="w-full px-3 py-2.5 text-sm sm:text-base bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all font-mono font-bold tracking-widest text-center"
                                        />
                                    </div>

                                    @if($tokenError)
                                        <div class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-700 dark:text-rose-300 font-medium">
                                            {{ $tokenError }}
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                                        <button
                                            type="button"
                                            wire:click="setModalTab('form')"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-medium transition-colors cursor-pointer"
                                        >
                                            ← Formulir
                                        </button>

                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                wire:click="closeModal"
                                                class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs sm:text-sm font-semibold transition-colors cursor-pointer"
                                            >
                                                Batal
                                            </button>
                                            <button
                                                type="submit"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-semibold shadow-xs transition-colors cursor-pointer disabled:opacity-50"
                                            >
                                                <span wire:loading.remove wire:target="verifyAndDownloadToken">Unduh Berkas</span>
                                                <span wire:loading wire:target="verifyAndDownloadToken">Memeriksa...</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                {{-- VIEW FORMULIR PENGAJUAN --}}
                                @if($isSubmitted)
                                    <div class="text-center py-4">
                                        <div class="w-14 h-14 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-base font-bold text-slate-900 dark:text-white mb-1">Permohonan Terkirim</h4>
                                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-5">
                                            {{ $successMessage }}
                                        </p>
                                        <button
                                            type="button"
                                            wire:click="closeModal"
                                            class="px-5 py-2 rounded-xl bg-[#083575] hover:bg-[#06295c] dark:bg-sky-600 dark:hover:bg-sky-500 text-white text-xs sm:text-sm font-semibold transition-colors cursor-pointer"
                                        >
                                            Tutup
                                        </button>
                                    </div>
                                @else
                                    <form wire:submit="submitPermohonan" class="space-y-3.5">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                                Nama Lengkap <span class="text-rose-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                wire:model="namaLengkap"
                                                placeholder="Nama Anda"
                                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all"
                                            />
                                            @error('namaLengkap') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                                Instansi / Organisasi <span class="text-rose-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                wire:model="instansi"
                                                placeholder="Nama Instansi"
                                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all"
                                            />
                                            @error('instansi') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                                    Email <span class="text-rose-500">*</span>
                                                </label>
                                                <input
                                                    type="email"
                                                    wire:model="email"
                                                    placeholder="email@gmail.com"
                                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all"
                                                />
                                                @error('email') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                                    No. WhatsApp <span class="text-rose-500">*</span>
                                                </label>
                                                <input
                                                    type="tel"
                                                    wire:model="telepon"
                                                    placeholder="08xxxxxxxxxx"
                                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all"
                                                />
                                                @error('telepon') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                                Keperluan
                                            </label>
                                            <textarea
                                                rows="2"
                                                wire:model="keperluan"
                                                placeholder="Tujuan permohonan berkas (opsional)..."
                                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all"
                                            ></textarea>
                                            @error('keperluan') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="pt-2 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                                            <button
                                                type="button"
                                                wire:click="setModalTab('token')"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-semibold transition-colors cursor-pointer shadow-2xs"
                                            >
                                                <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                </svg>
                                                <span>Punya Token</span>
                                            </button>

                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    wire:click="closeModal"
                                                    class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs sm:text-sm font-semibold transition-colors cursor-pointer"
                                                >
                                                    Batal
                                                </button>
                                                <button
                                                    type="submit"
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#083575] hover:bg-[#06295c] dark:bg-sky-600 dark:hover:bg-sky-500 text-white text-xs sm:text-sm font-semibold shadow-xs transition-colors cursor-pointer disabled:opacity-50"
                                                >
                                                    <span wire:loading.remove wire:target="submitPermohonan">Kirim</span>
                                                    <span wire:loading wire:target="submitPermohonan">Mengirim...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
