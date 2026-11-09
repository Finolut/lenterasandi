<div class="max-w-6xl mx-auto space-y-6">
    {{-- CSS styling untuk Rich Editor --}}
    <style>
        .rich-editor {
            min-height: 220px;
            height: auto;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-y: visible;
            overflow-x: hidden;
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }
        .rich-editor:focus {
            outline: none;
        }
        .rich-editor p,
        .rich-editor div,
        .rich-editor h2,
        .rich-editor h3,
        .rich-editor h4,
        .rich-editor span,
        .rich-editor li,
        .rich-editor blockquote {
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: break-word;
            word-wrap: break-word;
            max-width: 100%;
        }
        .rich-editor h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.75rem;
            margin-bottom: 0.35rem;
            line-height: 1.3;
        }
        .rich-editor h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 0.6rem;
            margin-bottom: 0.3rem;
            line-height: 1.35;
        }
        .rich-editor h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #334155;
            margin-top: 0.5rem;
            margin-bottom: 0.25rem;
        }
        .rich-editor p {
            margin-bottom: 0.5rem;
            line-height: 1.6;
            color: #334155;
        }
        .rich-editor ul {
            list-style-type: disc;
            padding-left: 1.35rem;
            margin-bottom: 0.5rem;
        }
        .rich-editor ol {
            list-style-type: decimal;
            padding-left: 1.35rem;
            margin-bottom: 0.5rem;
        }
        .rich-editor li {
            margin-bottom: 0.2rem;
        }
        .rich-editor blockquote {
            border-left: 3px solid #0284c7;
            padding-left: 0.75rem;
            font-style: italic;
            color: #475569;
            margin: 0.5rem 0;
            background: #f8fafc;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            border-radius: 0 4px 4px 0;
        }
        .rich-editor[contenteditable]:empty:before {
            content: attr(placeholder);
            color: #94a3b8;
            pointer-events: none;
            display: block;
        }
    </style>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E2E8F0]">
        <div>
            <h1 class="text-sm font-semibold text-[#0F172A]">
                {{ $mode === 'add' ? 'Tambah E-Book' : 'Edit E-Book' }}
            </h1>
            <p class="text-xs text-[#64748B] mt-0.5">Lengkapi informasi materi publikasi e-book dan modul literasi keamanan.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a 
                href="{{ route('admin.ebooks.index') }}" 
                wire:navigate.hover 
                class="h-9 px-3.5 rounded-md border border-[#E2E8F0] bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 shadow-2xs transition-colors cursor-pointer inline-flex items-center"
            >
                Kembali
            </a>
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                type="button"
                class="h-9 px-4 rounded-md bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-medium shadow-2xs transition-colors flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="save">{{ $mode === 'add' ? 'Simpan E-Book' : 'Perbarui E-Book' }}</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </div>

    {{-- Success Alert --}}
    @if($successMessage)
        <div class="rounded-lg bg-emerald-50 border border-emerald-200/80 px-4 py-3 text-xs text-emerald-800 flex items-center justify-between">
            <div class="flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-500 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-lg bg-rose-50 border border-rose-200/80 px-4 py-3 text-xs text-rose-800">
            <div class="font-semibold mb-1">Periksa kembali data yang diisi:</div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Grid (2 Columns on Left, 1 Column on Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Informasi E-Book (2 Kolom) --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5">
                {{-- Judul E-Book --}}
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">
                        Judul E-Book <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        placeholder="Masukkan judul e-book..."
                        wire:model="judul"
                        class="w-full h-9 px-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                    />
                    @error('judul') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Kategori (Instant Alpine 0ms Selection) --}}
                <div 
                    x-data="{
                        selected: @entangle('selectedCategories').live,
                        input: '',
                        add(val) {
                            let target = (val || this.input).trim();
                            if (!target) return;
                            let parts = target.split(',').map(s => s.trim()).filter(Boolean);
                            parts.forEach(p => {
                                if (!this.selected.includes(p)) {
                                    this.selected.push(p);
                                }
                            });
                            this.input = '';
                        },
                        remove(idx) {
                            this.selected.splice(idx, 1);
                        },
                        toggle(cat) {
                            let idx = this.selected.indexOf(cat);
                            if (idx > -1) {
                                this.selected.splice(idx, 1);
                            } else {
                                this.selected.push(cat);
                            }
                        }
                    }"
                    class="space-y-2"
                >
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>

                    {{-- Selected Category Tags (Instant Alpine) --}}
                    <div class="flex flex-wrap gap-1.5" x-show="selected.length > 0">
                        <template x-for="(cat, idx) in selected" :key="idx">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-sky-50 text-[#0284c7] border border-sky-200/80">
                                <span x-text="cat"></span>
                                <button
                                    type="button"
                                    @click="remove(idx)"
                                    class="text-sky-400 hover:text-rose-600 transition-colors cursor-pointer text-xs"
                                    title="Hapus Kategori"
                                >✕</button>
                            </span>
                        </template>
                    </div>

                    {{-- Input Tag Manual --}}
                    <div class="flex gap-2">
                        <input
                            type="text"
                            x-model="input"
                            @keydown.enter.prevent="add()"
                            placeholder="Ketik kategori lalu tekan Enter atau klik Tambah..."
                            class="flex-1 h-9 px-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                        />
                        <button
                            type="button"
                            @click="add()"
                            class="h-9 px-3.5 rounded-md bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-medium transition-colors cursor-pointer shrink-0"
                        >
                            + Tambah
                        </button>
                    </div>
                    @error('selectedCategories') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror

                    {{-- Quick Category Pills (Suggested Chips) --}}
                    @if(count($suggestedCategories) > 0)
                        <div class="pt-1">
                            <span class="text-[11px] font-medium text-slate-400 block mb-1.5">Rekomendasi Kategori:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($suggestedCategories as $sug)
                                    <button
                                        type="button"
                                        @click="toggle('{{ addslashes($sug) }}')"
                                        :class="selected.includes('{{ addslashes($sug) }}') 
                                            ? 'bg-[#0284c7] text-white border-[#0284c7]' 
                                            : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border-slate-200/80'"
                                        class="h-7 px-2.5 text-xs rounded-md border font-medium transition-colors cursor-pointer inline-flex items-center gap-1"
                                    >
                                        <span x-text="selected.includes('{{ addslashes($sug) }}') ? '✓' : '+'"></span>
                                        <span>{{ $sug }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Deskripsi / Sinopsis dengan Rich Text Formatting Toolbar --}}
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">
                        Deskripsi / Sinopsis <span class="text-rose-500">*</span>
                    </label>

                    <div 
                        wire:ignore
                        x-data="{
                            html: @entangle('deskripsi'),
                            isFocused: false,
                            debounceTimer: null,
                            init() {
                                this.$refs.editor.innerHTML = this.html || '';
                                this.$watch('html', val => {
                                    if (!this.isFocused && this.$refs.editor.innerHTML !== (val || '')) {
                                        this.$refs.editor.innerHTML = val || '';
                                    }
                                });
                            },
                            update() {
                                let val = this.$refs.editor.innerHTML;
                                this.html = val;
                                clearTimeout(this.debounceTimer);
                                this.debounceTimer = setTimeout(() => {
                                    $wire.set('deskripsi', val, false);
                                }, 300);
                            },
                            onBlur() {
                                this.isFocused = false;
                                let val = this.$refs.editor.innerHTML;
                                this.html = val;
                                $wire.set('deskripsi', val, false);
                            },
                            exec(cmd, val = null) {
                                document.execCommand(cmd, false, val);
                                this.update();
                                this.$refs.editor.focus();
                            },
                            format(tag) {
                                document.execCommand('formatBlock', false, tag);
                                this.update();
                                this.$refs.editor.focus();
                            }
                        }"
                        class="border border-slate-200 rounded-md overflow-hidden bg-white focus-within:border-[#0284c7] focus-within:ring-1 focus-within:ring-[#0284c7] transition-colors"
                    >
                        {{-- Formatting Toolbar --}}
                        <div class="bg-slate-50/70 border-b border-slate-200/80 px-2.5 py-1.5 flex flex-wrap items-center gap-1 text-xs select-none">
                            {{-- Heading Dropdown --}}
                            <select 
                                @change="format($event.target.value); $event.target.value = ''"
                                class="h-7 bg-white border border-slate-200 rounded px-2 text-xs font-medium text-slate-700 focus:outline-none cursor-pointer"
                            >
                                <option value="" disabled selected>Ukuran Teks</option>
                                <option value="p">Teks Normal (Paragraf)</option>
                                <option value="h2">Judul Besar (H2)</option>
                                <option value="h3">Judul Sedang (H3)</option>
                                <option value="h4">Subjudul (H4)</option>
                            </select>

                            <div class="h-4 w-px bg-slate-200 mx-1"></div>

                            {{-- Bold --}}
                            <button 
                                type="button" 
                                @click="exec('bold')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Tebal (Bold)"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 12h9a4 4 0 0 1 0 8H6v-8zm0 0h8a3.5 3.5 0 0 0 0-7H6v7z"/>
                                </svg>
                            </button>

                            {{-- Italic --}}
                            <button 
                                type="button" 
                                @click="exec('italic')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Miring (Italic)"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/>
                                </svg>
                            </button>

                            {{-- Underline --}}
                            <button 
                                type="button" 
                                @click="exec('underline')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Garis Bawah (Underline)"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/>
                                </svg>
                            </button>

                            <div class="h-4 w-px bg-slate-200 mx-1"></div>

                            {{-- Bullet List --}}
                            <button 
                                type="button" 
                                @click="exec('insertUnorderedList')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Daftar Poin"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/>
                                    <circle cx="4" cy="6" r="1.5" fill="currentColor"/><circle cx="4" cy="12" r="1.5" fill="currentColor"/><circle cx="4" cy="18" r="1.5" fill="currentColor"/>
                                </svg>
                            </button>

                            {{-- Numbered List --}}
                            <button 
                                type="button" 
                                @click="exec('insertOrderedList')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Daftar Angka"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/>
                                    <path d="M4 6h1v4M4 10h2M3 14h2a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3v1h3"/>
                                </svg>
                            </button>

                            {{-- Blockquote --}}
                            <button 
                                type="button" 
                                @click="format('blockquote')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Kutipan (Blockquote)"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1zM15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/>
                                </svg>
                            </button>

                            <div class="h-4 w-px bg-slate-200 mx-1"></div>

                            {{-- Undo --}}
                            <button 
                                type="button" 
                                @click="exec('undo')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Batal (Undo)"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/>
                                </svg>
                            </button>

                            {{-- Redo --}}
                            <button 
                                type="button" 
                                @click="exec('redo')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200/70 text-slate-600 hover:text-slate-900 transition-colors cursor-pointer" 
                                title="Ulangi (Redo)"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7"/>
                                </svg>
                            </button>

                            {{-- Remove Format --}}
                            <button 
                                type="button" 
                                @click="exec('removeFormat')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition-colors cursor-pointer ml-auto" 
                                title="Hapus Format"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/><path d="m20 20-5-5"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Editable Content Area --}}
                        <div 
                            x-ref="editor"
                            contenteditable="true"
                            @focus="isFocused = true"
                            @input="update()"
                            @blur="onBlur()"
                            placeholder="Tulis ringkasan atau sinopsis e-book di sini..."
                            class="rich-editor p-3.5 min-h-[220px] focus:outline-none text-xs sm:text-sm leading-relaxed text-slate-800 w-full break-words"
                        ></div>
                    </div>
                    @error('deskripsi') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Meta Info: Pengarang, Penerbit, Tahun --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Penulis / Instansi <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            placeholder="Instansi penyusun..."
                            wire:model="pengarang"
                            class="w-full h-9 px-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                        />
                        @error('pengarang') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Penerbit
                        </label>
                        <input
                            type="text"
                            placeholder="Penerbit..."
                            wire:model="penerbit"
                            class="w-full h-9 px-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                        />
                        @error('penerbit') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Tahun Terbit
                        </label>
                        <input
                            type="number"
                            placeholder="{{ date('Y') }}"
                            wire:model="tahun"
                            class="w-full h-9 px-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                        />
                        @error('tahun') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Status & Berkas (1 Kolom) --}}
        <div class="space-y-5">
            {{-- Status Publikasi Card --}}
            <div class="bg-white rounded-xl border border-slate-200/80 p-4 sm:p-5 shadow-xs space-y-2">
                <label class="block text-xs font-semibold text-slate-900">
                    Status Publikasi
                </label>
                <select
                    wire:model.live="status"
                    class="w-full h-9 px-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-md text-slate-800 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors cursor-pointer font-medium"
                >
                    <option value="Publik">Publik</option>
                    <option value="Draf">Draf</option>
                    <option value="Disembunyikan">Disembunyikan</option>
                </select>
                <p class="text-[11px] text-slate-500">Status menentukan visibilitas e-book pada katalog publik.</p>
            </div>

            {{-- Dokumen PDF Card --}}
            <div class="bg-white rounded-xl border border-slate-200/80 p-4 sm:p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-semibold text-slate-900">
                        Dokumen PDF <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] text-slate-400 font-normal">Maks. 50 MB</span>
                </div>

                @if($filePdf)
                    <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200/80 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-emerald-900 truncate">
                                {{ $filePdf->getClientOriginalName() }}
                            </div>
                            <div class="text-[11px] text-emerald-700 mt-0.5">
                                {{ number_format($filePdf->getSize() / 1024 / 1024, 2) }} MB
                            </div>
                        </div>
                        <label class="h-7 px-2.5 inline-flex items-center text-xs font-medium bg-white text-emerald-800 border border-emerald-300 rounded-md hover:bg-emerald-50 cursor-pointer shrink-0 transition-colors shadow-2xs">
                            Ganti
                            <input type="file" wire:model="filePdf" accept=".pdf" class="hidden" />
                        </label>
                    </div>
                @elseif($existingPdfUrl)
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 space-y-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-800 truncate">Berkas PDF Terpasang</span>
                            <a href="{{ Storage::disk('public')->url($existingPdfUrl) }}" target="_blank" class="text-[#0284c7] font-medium hover:underline text-xs inline-flex items-center gap-1">
                                <span>Lihat</span>
                                <span>↗</span>
                            </a>
                        </div>
                        <div
                            x-data="{ isDropping: false }"
                            @dragover.prevent="isDropping = true"
                            @dragleave.prevent="isDropping = false"
                            @drop.prevent="
                                isDropping = false;
                                if ($event.dataTransfer.files.length > 0) {
                                    $refs.filePdfInput.files = $event.dataTransfer.files;
                                    $refs.filePdfInput.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            "
                            :class="isDropping ? 'border-[#0284c7] bg-sky-50/50' : 'border-dashed border-slate-300 bg-white'"
                            class="border rounded-md p-2 transition-colors text-center"
                        >
                            <label class="w-full block py-1.5 text-xs font-medium text-slate-700 hover:text-slate-900 cursor-pointer transition-colors">
                                Ganti Berkas PDF <span class="text-[11px] text-slate-400 font-normal">(Klik atau seret PDF)</span>
                                <input type="file" x-ref="filePdfInput" wire:model="filePdf" accept=".pdf" class="hidden" />
                            </label>
                        </div>
                    </div>
                @else
                    <div
                        x-data="{ isDropping: false }"
                        @dragover.prevent="isDropping = true"
                        @dragleave.prevent="isDropping = false"
                        @drop.prevent="
                            isDropping = false;
                            if ($event.dataTransfer.files.length > 0) {
                                $refs.filePdfInput.files = $event.dataTransfer.files;
                                $refs.filePdfInput.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        "
                        :class="isDropping ? 'border-[#0284c7] bg-sky-50/50 ring-1 ring-[#0284c7]' : 'border-slate-300 hover:border-[#0284c7] bg-slate-50/50 hover:bg-sky-50/30'"
                        class="border-2 border-dashed rounded-lg p-5 text-center cursor-pointer flex flex-col items-center justify-center transition-colors relative"
                        @click="$refs.filePdfInput.click()"
                    >
                        <svg class="w-7 h-7 text-[#0284c7] mb-2 transition-transform" :class="isDropping ? 'scale-110' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span class="text-xs font-semibold text-slate-900">
                            <span class="text-[#0284c7]">Pilih berkas</span> atau seret file PDF
                        </span>
                        <span class="text-[11px] text-slate-500 mt-0.5">Dokumen berformat PDF (Maks. 50 MB)</span>
                        <input type="file" x-ref="filePdfInput" wire:model="filePdf" accept=".pdf" class="hidden" />
                    </div>
                @endif

                <div wire:loading wire:target="filePdf" class="text-[11px] text-[#0284c7] font-medium flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Mengunggah dokumen PDF...</span>
                </div>
                @error('filePdf') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror
            </div>

            {{-- Cover Card (Compact) --}}
            <div class="bg-white rounded-xl border border-slate-200/80 p-4 sm:p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-semibold text-slate-900">
                        Cover / Sampul
                    </label>
                    <span class="text-[11px] text-slate-400">Opsional</span>
                </div>

                {{-- Tab Switcher --}}
                <div class="p-1 bg-slate-100 rounded-md flex items-center gap-1 text-xs font-medium text-slate-600">
                    <button 
                        type="button"
                        wire:click="$set('coverSourceType', 'upload')"
                        class="flex-1 h-7 rounded transition-colors text-center cursor-pointer {{ $coverSourceType === 'upload' ? 'bg-white text-slate-900 shadow-2xs font-semibold' : 'hover:text-slate-900' }}"
                    >
                        Unggah Berkas
                    </button>
                    <button 
                        type="button"
                        wire:click="$set('coverSourceType', 'url')"
                        class="flex-1 h-7 rounded transition-colors text-center cursor-pointer {{ $coverSourceType === 'url' ? 'bg-white text-slate-900 shadow-2xs font-semibold' : 'hover:text-slate-900' }}"
                    >
                        Tautan URL
                    </button>
                </div>

                @if($coverSourceType === 'upload')
                    @if($fileCover)
                        <div class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-200 bg-slate-50/50">
                            <img src="{{ $fileCover->temporaryUrl() }}" alt="Cover" class="w-12 h-16 object-cover rounded border border-slate-200 shrink-0" />
                            <div class="space-y-1.5 flex-1 min-w-0">
                                <span class="text-xs font-medium text-emerald-700 block truncate">✓ Cover Terpilih</span>
                                <div class="flex items-center gap-2">
                                    <label class="h-7 px-2.5 inline-flex items-center text-xs font-medium bg-white text-slate-700 border border-slate-200 rounded-md hover:bg-slate-50 cursor-pointer transition-colors shadow-2xs">
                                        Ganti
                                        <input type="file" wire:model="fileCover" accept="image/*" class="hidden" />
                                    </label>
                                    <button type="button" wire:click="removeCover" class="h-7 px-2 inline-flex items-center text-xs font-medium text-rose-600 hover:bg-rose-50 rounded-md transition-colors cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($existingCoverUrl && !str_starts_with($existingCoverUrl, 'http'))
                        <div class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-200 bg-slate-50/50">
                            <img src="{{ Storage::disk('public')->url($existingCoverUrl) }}" alt="Cover" class="w-12 h-16 object-cover rounded border border-slate-200 shrink-0" />
                            <div class="space-y-1.5 flex-1 min-w-0">
                                <span class="text-xs font-medium text-slate-700 block">Cover Saat Ini</span>
                                <div class="flex items-center gap-2">
                                    <label class="h-7 px-2.5 inline-flex items-center text-xs font-medium bg-white text-slate-700 border border-slate-200 rounded-md hover:bg-slate-50 cursor-pointer transition-colors shadow-2xs">
                                        Ganti
                                        <input type="file" wire:model="fileCover" accept="image/*" class="hidden" />
                                    </label>
                                    <button type="button" wire:click="deleteExistingCover" class="h-7 px-2 inline-flex items-center text-xs font-medium text-rose-600 hover:bg-rose-50 rounded-md transition-colors cursor-pointer">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            x-data="{ isDropping: false }"
                            @dragover.prevent="isDropping = true"
                            @dragleave.prevent="isDropping = false"
                            @drop.prevent="
                                isDropping = false;
                                if ($event.dataTransfer.files.length > 0) {
                                    $refs.fileCoverInput.files = $event.dataTransfer.files;
                                    $refs.fileCoverInput.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            "
                            :class="isDropping ? 'border-[#0284c7] bg-sky-50/50 ring-1 ring-[#0284c7]' : 'border-slate-300 hover:border-[#0284c7] bg-slate-50/50 hover:bg-sky-50/30'"
                            class="border-2 border-dashed rounded-lg p-3.5 text-center cursor-pointer flex flex-col items-center justify-center transition-colors relative"
                            @click="$refs.fileCoverInput.click()"
                        >
                            <svg class="w-6 h-6 text-slate-400 mb-1 transition-transform" :class="isDropping ? 'scale-110 text-[#0284c7]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-medium text-slate-800">
                                <span class="text-[#0284c7] font-semibold">Pilih</span> atau seret cover
                            </span>
                            <span class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, atau WebP (Maks. 5 MB)</span>
                            <input type="file" x-ref="fileCoverInput" wire:model="fileCover" accept="image/*" class="hidden" />
                        </div>
                    @endif

                    <div wire:loading wire:target="fileCover" class="text-[11px] text-[#0284c7] font-medium flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Mengunggah gambar cover...</span>
                    </div>
                    @error('fileCover') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror
                @else
                    <div class="space-y-2">
                        <input 
                            type="url" 
                            wire:model.live.debounce.300ms="coverUrlInput" 
                            placeholder="https://contoh.com/cover-buku.jpg"
                            class="w-full h-9 px-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-colors"
                        />
                        @error('coverUrlInput') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror

                        @if($coverUrlInput)
                            <div class="relative rounded-md overflow-hidden border border-slate-200 bg-slate-100 aspect-[3/4] flex items-center justify-center">
                                <img 
                                    src="{{ $coverUrlInput }}" 
                                    alt="Preview Cover" 
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='<span class=\'text-xs text-rose-500 p-2 text-center\'>Tautan gambar tidak dapat dimuat</span>';"
                                />
                                <button 
                                    type="button" 
                                    wire:click="removeCover" 
                                    class="absolute top-1.5 right-1.5 px-2 py-0.5 rounded bg-slate-900/80 hover:bg-slate-900 text-white text-[10px] font-medium transition-colors cursor-pointer"
                                >
                                    Hapus
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
