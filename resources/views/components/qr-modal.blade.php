{{-- Global QR Code Modal Component for Lentera Sandi --}}
<div
    x-data="{
        isOpen: false,
        qrUrl: window.location.origin,
        qrTitle: 'Portal Lentera Sandi',
        qrSubtitle: 'Dinas Komunikasi dan Informatika Provinsi Jawa Timur',
        copied: false,
        qrColor: '#083575',

        openModal(url = null, title = null, subtitle = null) {
            this.qrUrl = url || window.location.origin;
            this.qrTitle = title || 'Portal Lentera Sandi';
            this.qrSubtitle = subtitle || 'Dinas Komunikasi dan Informatika Provinsi Jawa Timur';
            this.isOpen = true;
            this.$nextTick(() => {
                this.renderQR();
            });
        },

        closeModal() {
            this.isOpen = false;
        },

        renderQR() {
            const canvas = this.$refs.qrCanvas;
            if (!canvas) return;
            
            if (typeof window.QRCode === 'undefined') {
                console.warn('QRCode library not ready yet');
                return;
            }

            window.QRCode.toCanvas(canvas, this.qrUrl, {
                width: 240,
                margin: 2,
                color: {
                    dark: this.qrColor,
                    light: '#FFFFFF'
                },
                errorCorrectionLevel: 'H'
            }, function (error) {
                if (error) console.error(error);
            });
        },

        copyUrl() {
            navigator.clipboard.writeText(this.qrUrl).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2500);
            });
        },

        downloadPNG() {
            if (typeof window.QRCode === 'undefined') return;
            
            // Render high-res canvas (1000px) for sharp quality
            const tempCanvas = document.createElement('canvas');
            window.QRCode.toCanvas(tempCanvas, this.qrUrl, {
                width: 1000,
                margin: 3,
                color: {
                    dark: this.qrColor,
                    light: '#FFFFFF'
                },
                errorCorrectionLevel: 'H'
            }, (error) => {
                if (error) {
                    console.error(error);
                    return;
                }
                const link = document.createElement('a');
                const safeName = this.qrTitle.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-');
                link.download = `qrcode-${safeName}.png`;
                link.href = tempCanvas.toDataURL('image/png');
                link.click();
            });
        }
    }"
    @open-qr-modal.window="openModal($event.detail?.url, $event.detail?.title, $event.detail?.subtitle)"
    x-cloak
>
    {{-- Backdrop & Modal Dialog --}}
    <div
        x-show="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs transition-opacity"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="closeModal()"
    >
        <div
            x-show="isOpen"
            @click.outside="closeModal()"
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 w-full max-w-md overflow-hidden transform transition-all"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-gradient-to-r from-[#083575] to-[#0284c7] text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold leading-tight" x-text="qrTitle">QR Code Portal</h3>
                        <p class="text-[11px] text-sky-100 truncate max-w-[220px]" x-text="qrSubtitle">Tautan Cepat</p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="closeModal()"
                    class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer"
                    title="Tutup (Esc)"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 text-center">
                {{-- QR Canvas Container --}}
                <div class="inline-block p-4 rounded-2xl bg-white border-2 border-slate-200 dark:border-slate-700 shadow-md mb-4">
                    <canvas x-ref="qrCanvas" class="w-[200px] h-[200px] mx-auto block"></canvas>
                </div>

                {{-- Input URL Editable --}}
                <div class="mb-5 text-left">
                    <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">
                        Tautan Website (URL)
                    </label>
                    <div class="flex items-center gap-1.5">
                        <input
                            type="text"
                            x-model="qrUrl"
                            @input="renderQR()"
                            placeholder="https://..."
                            class="flex-1 px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white font-mono focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none"
                        />
                        <button
                            type="button"
                            @click="copyUrl()"
                            class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition-colors cursor-pointer shrink-0 border border-slate-200 dark:border-slate-700 flex items-center gap-1"
                            title="Salin URL ke Clipboard"
                        >
                            <span x-show="!copied">Salin</span>
                            <span x-show="copied" class="text-emerald-600 font-bold">✓ Tersalin</span>
                        </button>
                    </div>
                </div>

                {{-- Action Button: Download PNG --}}
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button
                        type="button"
                        @click="downloadPNG()"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#083575] hover:bg-[#06295c] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Download PNG</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
