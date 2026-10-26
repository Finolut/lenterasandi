<div class="max-w-6xl mx-auto">
    {{-- CSS styling untuk Rich Editor --}}
    <style>
        .rich-editor {
            min-height: 240px;
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
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.75rem;
            margin-bottom: 0.4rem;
            line-height: 1.3;
        }
        .rich-editor h3 {
            font-size: 1.15rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 0.6rem;
            margin-bottom: 0.35rem;
            line-height: 1.35;
        }
        .rich-editor h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #334155;
            margin-top: 0.5rem;
            margin-bottom: 0.3rem;
        }
        .rich-editor p {
            margin-bottom: 0.5rem;
            line-height: 1.6;
            color: #334155;
        }
        .rich-editor ul {
            list-style-type: disc;
            padding-left: 1.4rem;
            margin-bottom: 0.5rem;
        }
        .rich-editor ol {
            list-style-type: decimal;
            padding-left: 1.4rem;
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
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                {{ $mode === 'add' ? 'Tambah E-Book' : 'Edit E-Book' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Lengkapi informasi materi publikasi e-book dan modul literasi keamanan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.ebooks.index') }}" wire:navigate.hover class="px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                Kembali
            </a>
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                type="button"
                class="px-4 py-2 rounded-lg bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="save">{{ $mode === 'add' ? 'Simpan E-Book' : 'Perbarui E-Book' }}</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </div>

    {{-- Success Alert --}}
    @if($successMessage)
        <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-xs text-emerald-800 flex items-center justify-between">
            <div class="flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-500 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-xs text-rose-800">
            <div class="font-semibold mb-1">Periksa kembali data yang diisi:</div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Kolom Kiri: Informasi E-Book (2 Kolom) --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 shadow-xs space-y-4">
                {{-- Judul E-Book --}}
                <div>
                    <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                        Judul E-Book <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        placeholder="Judul e-book..."
                        wire:model="judul"
                        class="w-full px-3.5 py-2 text-sm bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] placeholder-[#94a3b8] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
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
                >
                    <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                        Kategori <span class="text-rose-500">*</span>
                    </label>

                    {{-- Selected Category Tags (Instant Alpine) --}}
                    <div class="flex flex-wrap gap-1.5 mb-2" x-show="selected.length > 0">
                        <template x-for="(cat, idx) in selected" :key="idx">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-sky-50 text-[#0284c7] border border-sky-200">
                                <span x-text="cat"></span>
                                <button
                                    type="button"
                                    @click="remove(idx)"
                                    class="hover:text-rose-600 transition-colors cursor-pointer text-sm leading-none"
                                    title="Hapus"
                                >×</button>
                            </span>
                        </template>
                    </div>

                    {{-- Input Tag Manual --}}
                    <div class="flex gap-2">
                        <input
                            type="text"
                            x-model="input"
                            @keydown.enter.prevent="add()"
                            placeholder="Ketik kategori lalu tekan Enter..."
                            class="flex-1 px-3 py-1.5 text-xs sm:text-sm bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] placeholder-[#94a3b8] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                        />
                        <button
                            type="button"
                            @click="add()"
                            class="px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-[#334155] text-xs font-semibold transition-colors cursor-pointer shrink-0"
                        >
                            + Tambah
                        </button>
                    </div>
                    @error('selectedCategories') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror

                    {{-- Quick Category Pills (Suggested Chips with 0ms toggle) --}}
                    @if(count($suggestedCategories) > 0)
                        <div class="mt-2.5">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($suggestedCategories as $sug)
                                    <button
                                        type="button"
                                        @click="toggle('{{ addslashes($sug) }}')"
                                        :class="selected.includes('{{ addslashes($sug) }}') 
                                            ? 'bg-[#0284c7] text-white font-medium shadow-xs border-[#0284c7]' 
                                            : 'bg-white hover:bg-slate-50 text-[#64748b] border-[#cbd5e1]'"
                                        class="px-2.5 py-1 text-xs rounded-md border transition-all cursor-pointer"
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
                    <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
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
                        class="border border-[#cbd5e1] rounded-xl overflow-hidden bg-white focus-within:border-[#0284c7] focus-within:ring-1 focus-within:ring-[#0284c7] transition-all"
                    >
                        {{-- Formatting Toolbar --}}
                        <div class="bg-[#f8fafc] border-b border-[#e2e8f0] px-3 py-2 flex flex-wrap items-center gap-1 text-xs select-none">
                            {{-- Ukuran Teks / Heading Dropdown --}}
                            <select 
                                @change="format($event.target.value); $event.target.value = ''"
                                class="bg-white border border-[#cbd5e1] rounded-md px-2 py-1 text-xs font-medium text-[#334155] focus:outline-none cursor-pointer"
                            >
                                <option value="" disabled selected>Ukuran Teks</option>
                                <option value="p">Teks Normal (Paragraf)</option>
                                <option value="h2">Judul Besar (H2)</option>
                                <option value="h3">Judul Sedang (H3)</option>
                                <option value="h4">Subjudul (H4)</option>
                            </select>

                            <div class="h-4 w-px bg-[#cbd5e1] mx-1"></div>

                            {{-- Bold --}}
                            <button 
                                type="button" 
                                @click="exec('bold')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] font-bold transition-colors cursor-pointer" 
                                title="Tebal (Bold)"
                            >
                                <b>B</b>
                            </button>

                            {{-- Italic --}}
                            <button 
                                type="button" 
                                @click="exec('italic')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] italic font-serif transition-colors cursor-pointer" 
                                title="Miring (Italic)"
                            >
                                <i>I</i>
                            </button>

                            {{-- Underline --}}
                            <button 
                                type="button" 
                                @click="exec('underline')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] underline transition-colors cursor-pointer" 
                                title="Garis Bawah (Underline)"
                            >
                                <u>U</u>
                            </button>

                            <div class="h-4 w-px bg-[#cbd5e1] mx-1"></div>

                            {{-- Bullet List --}}
                            <button 
                                type="button" 
                                @click="exec('insertUnorderedList')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Daftar Poin (Bullet List)"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16M2 6h.01M2 12h.01M2 18h.01"/>
                                </svg>
                            </button>

                            {{-- Numbered List --}}
                            <button 
                                type="button" 
                                @click="exec('insertOrderedList')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Daftar Angka (Numbered List)"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                            </button>

                            {{-- Blockquote --}}
                            <button 
                                type="button" 
                                @click="format('blockquote')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] font-serif font-bold text-sm transition-colors cursor-pointer" 
                                title="Kutipan (Blockquote)"
                            >
                                “
                            </button>

                            <div class="h-4 w-px bg-[#cbd5e1] mx-1"></div>

                            {{-- Undo --}}
                            <button 
                                type="button" 
                                @click="exec('undo')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Batal (Undo)"
                            >
                                ↶
                            </button>

                            {{-- Redo --}}
                            <button 
                                type="button" 
                                @click="exec('redo')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Ulangi (Redo)"
                            >
                                ↷
                            </button>

                            {{-- Remove Format --}}
                            <button 
                                type="button" 
                                @click="exec('removeFormat')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-rose-600 text-[#475569] transition-colors cursor-pointer ml-auto" 
                                title="Hapus Format"
                            >
                                ✕
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
                            class="rich-editor p-4 min-h-[240px] focus:outline-none text-sm leading-relaxed w-full break-words"
                        ></div>
                    </div>
                    @error('deskripsi') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Meta Info: Pengarang, Penerbit, Tahun --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                    <div>
                        <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                            Penulis / Instansi <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            placeholder="Instansi penyusun..."
                            wire:model="pengarang"
                            class="w-full px-3 py-1.5 text-xs sm:text-sm bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] placeholder-[#94a3b8] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                        />
                        @error('pengarang') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                            Penerbit
                        </label>
                        <input
                            type="text"
                            placeholder="Penerbit..."
                            wire:model="penerbit"
                            class="w-full px-3 py-1.5 text-xs sm:text-sm bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] placeholder-[#94a3b8] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                        />
                        @error('penerbit') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                            Tahun Terbit
                        </label>
                        <input
                            type="number"
                            placeholder="{{ date('Y') }}"
                            wire:model="tahun"
                            class="w-full px-3 py-1.5 text-xs sm:text-sm bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] placeholder-[#94a3b8] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                        />
                        @error('tahun') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Status & Berkas (1 Kolom) --}}
        <div class="space-y-4">
            {{-- Status Publikasi Card --}}
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 shadow-xs">
                <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                    Status Publikasi
                </label>
                <select
                    wire:model.live="status"
                    class="w-full px-3 py-1.5 text-xs sm:text-sm bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all cursor-pointer font-medium mb-3"
                >
                    <option value="Publik">Publik</option>
                    <option value="Draf">Draf</option>
                    <option value="Disembunyikan">Disembunyikan</option>
                </select>

                <button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    type="button"
                    class="w-full py-2 rounded-lg bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer disabled:opacity-50"
                >
                    {{ $mode === 'add' ? 'Simpan E-Book' : 'Perbarui E-Book' }}
                </button>
            </div>

            {{-- Dokumen PDF Card --}}
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 shadow-xs">
                <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-2">
                    Dokumen PDF <span class="text-rose-500">*</span>
                </label>

                @if($filePdf)
                    <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-emerald-800 truncate">
                                {{ $filePdf->getClientOriginalName() }}
                            </div>
                            <div class="text-[11px] text-emerald-600">
                                {{ number_format($filePdf->getSize() / 1024 / 1024, 2) }} MB
                            </div>
                        </div>
                        <label class="px-2.5 py-1 text-xs font-semibold bg-white text-emerald-700 border border-emerald-300 rounded-md hover:bg-emerald-50 cursor-pointer shrink-0 transition-colors">
                            Ganti
                            <input type="file" wire:model="filePdf" accept=".pdf" class="hidden" />
                        </label>
                    </div>
                @elseif($existingPdfUrl)
                    <div class="p-3 rounded-lg bg-slate-50 border border-[#cbd5e1] space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-[#0f172a] truncate">Dokumen PDF Terpasang</span>
                            <a href="{{ Storage::disk('public')->url($existingPdfUrl) }}" target="_blank" class="text-[#0284c7] font-semibold hover:underline">
                                Lihat ↗
                            </a>
                        </div>
                        <label class="w-full block text-center py-1.5 text-xs font-semibold bg-white text-[#475569] border border-[#cbd5e1] rounded-md hover:bg-slate-100 cursor-pointer transition-colors">
                            Ganti Berkas PDF
                            <input type="file" wire:model="filePdf" accept=".pdf" class="hidden" />
                        </label>
                    </div>
                @else
                    <label class="border border-dashed border-[#cbd5e1] hover:border-[#0284c7] bg-[#f8fafc] hover:bg-sky-50/40 rounded-lg p-4 text-center cursor-pointer flex flex-col items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-[#64748b] mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs font-semibold text-[#0f172a]">Pilih File PDF</span>
                        <span class="text-[11px] text-[#94a3b8] mt-0.5">Maks. 50 MB</span>
                        <input type="file" wire:model="filePdf" accept=".pdf" class="hidden" />
                    </label>
                @endif

                <div wire:loading wire:target="filePdf" class="text-[11px] text-[#0284c7] font-medium flex items-center gap-1 mt-2">
                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Mengunggah PDF...</span>
                </div>
                @error('filePdf') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Cover Card (Pilihan Upload File atau URL) --}}
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 shadow-xs space-y-3">
                <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider">
                    Cover / Sampul <span class="text-[#94a3b8] font-normal lowercase">(opsional)</span>
                </label>

                {{-- Tab Switcher Unggah vs URL --}}
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-lg text-xs font-semibold text-[#475569]">
                    <button 
                        type="button"
                        wire:click="$set('coverSourceType', 'upload')"
                        class="flex-1 py-1 rounded-md transition-all text-center cursor-pointer {{ $coverSourceType === 'upload' ? 'bg-white text-[#0284c7] shadow-xs font-bold' : 'hover:text-[#0f172a]' }}"
                    >
                        📁 Unggah Berkas
                    </button>
                    <button 
                        type="button"
                        wire:click="$set('coverSourceType', 'url')"
                        class="flex-1 py-1 rounded-md transition-all text-center cursor-pointer {{ $coverSourceType === 'url' ? 'bg-white text-[#0284c7] shadow-xs font-bold' : 'hover:text-[#0f172a]' }}"
                    >
                        🔗 Tautan / URL
                    </button>
                </div>

                @if($coverSourceType === 'upload')
                    {{-- Mode Unggah File --}}
                    @if($fileCover)
                        <div class="flex items-center gap-3">
                            <img src="{{ $fileCover->temporaryUrl() }}" alt="Cover" class="w-14 h-18 object-cover rounded-md border border-[#cbd5e1] shrink-0" />
                            <div class="space-y-1.5 flex-1 min-w-0">
                                <span class="text-xs font-bold text-emerald-700 block truncate">✓ Cover Terpilih</span>
                                <div class="flex items-center gap-2">
                                    <label class="px-2.5 py-1 text-xs font-semibold bg-white text-[#475569] border border-[#cbd5e1] rounded-md hover:bg-slate-50 cursor-pointer transition-colors">
                                        Ganti
                                        <input type="file" wire:model="fileCover" accept="image/*" class="hidden" />
                                    </label>
                                    <button type="button" wire:click="removeCover" class="px-2 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-md transition-colors cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($existingCoverUrl && !str_starts_with($existingCoverUrl, 'http'))
                        <div class="flex items-center gap-3">
                            <img src="{{ Storage::disk('public')->url($existingCoverUrl) }}" alt="Cover" class="w-14 h-18 object-cover rounded-md border border-[#cbd5e1] shrink-0" />
                            <div class="space-y-1.5 flex-1 min-w-0">
                                <span class="text-xs font-medium text-[#475569] block">Cover Saat Ini</span>
                                <div class="flex items-center gap-2">
                                    <label class="px-2.5 py-1 text-xs font-semibold bg-white text-[#475569] border border-[#cbd5e1] rounded-md hover:bg-slate-50 cursor-pointer transition-colors">
                                        Ganti
                                        <input type="file" wire:model="fileCover" accept="image/*" class="hidden" />
                                    </label>
                                    <button type="button" wire:click="deleteExistingCover" class="px-2 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-md transition-colors cursor-pointer">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <label class="border border-dashed border-[#cbd5e1] hover:border-[#0284c7] bg-[#f8fafc] hover:bg-sky-50/40 rounded-lg p-3 text-center cursor-pointer flex flex-col items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-[#64748b] mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-semibold text-[#0f172a]">Pilih Gambar Cover</span>
                            <span class="text-[11px] text-[#94a3b8] mt-0.5">JPG, PNG, atau WebP</span>
                            <input type="file" wire:model="fileCover" accept="image/*" class="hidden" />
                        </label>
                    @endif

                    <div wire:loading wire:target="fileCover" class="text-[11px] text-[#0284c7] font-medium flex items-center gap-1 mt-2">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Mengunggah gambar...</span>
                    </div>
                    @error('fileCover') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                @else
                    {{-- Mode Ambil dari URL --}}
                    <div class="space-y-2">
                        <input 
                            type="url" 
                            wire:model.live.debounce.300ms="coverUrlInput" 
                            placeholder="https://contoh.com/cover-buku.jpg"
                            class="w-full px-3 py-1.5 text-xs bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none"
                        />
                        @error('coverUrlInput') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror

                        @if($coverUrlInput)
                            <div class="relative rounded-lg overflow-hidden border border-[#cbd5e1] bg-slate-100 aspect-[3/4] flex items-center justify-center">
                                <img 
                                    src="{{ $coverUrlInput }}" 
                                    alt="Preview Cover" 
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='<span class=\'text-xs text-rose-500 p-2 text-center\'>Tautan gambar tidak dapat dimuat</span>';"
                                />
                                <button 
                                    type="button" 
                                    wire:click="removeCover" 
                                    class="absolute top-1.5 right-1.5 px-2 py-0.5 rounded bg-black/70 hover:bg-black text-white text-[10px] font-semibold transition-colors cursor-pointer"
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
