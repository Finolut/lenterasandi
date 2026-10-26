<div class="max-w-6xl mx-auto">
    {{-- Styling untuk Rich Editor Content --}}
    <style>
        .rich-editor {
            min-height: 280px;
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
        .rich-editor h2 { font-size: 1.45rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; color: #0f172a; line-height: 1.3; }
        .rich-editor h3 { font-size: 1.25rem; font-weight: 700; margin-top: 0.85rem; margin-bottom: 0.4rem; color: #1e293b; line-height: 1.35; }
        .rich-editor h4 { font-size: 1.05rem; font-weight: 600; margin-top: 0.7rem; margin-bottom: 0.35rem; color: #334155; line-height: 1.4; }
        .rich-editor p { margin-bottom: 0.75rem; line-height: 1.75; color: #334155; }
        .rich-editor ul { list-style-type: disc; margin-left: 1.5rem; margin-bottom: 0.75rem; }
        .rich-editor ol { list-style-type: decimal; margin-left: 1.5rem; margin-bottom: 0.75rem; }
        .rich-editor li { margin-bottom: 0.25rem; }
        .rich-editor blockquote { border-left: 3px solid #0284c7; padding-left: 0.85rem; color: #475569; font-style: italic; margin: 0.85rem 0; background: #f8fafc; padding-top: 0.35rem; padding-bottom: 0.35rem; border-radius: 0 0.5rem 0.5rem 0; }
        .rich-editor:empty:before { content: attr(placeholder); color: #94a3b8; pointer-events: none; display: block; }
    </style>

    {{-- Header --}}
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                {{ $mode === 'add' ? 'Tambah Artikel' : 'Edit Artikel' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tulis siaran pers, agenda kegiatan, atau artikel edukasi keamanan siber.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.informasi.index') }}" wire:navigate.hover class="px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                Kembali
            </a>
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                type="button"
                class="px-4 py-2 rounded-lg bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="save">{{ $mode === 'add' ? 'Simpan Artikel' : 'Perbarui Artikel' }}</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </div>

    {{-- Alerts --}}
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
        {{-- Kolom Kiri: Konten Utama (2 Kolom) --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 shadow-xs space-y-4">
                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                        Judul <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        placeholder="Judul artikel..."
                        wire:model="title"
                        class="w-full px-3.5 py-2 text-sm bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] placeholder-[#94a3b8] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none transition-all"
                    />
                    @error('title') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
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

                {{-- Konten Artikel dengan Rich Text Formatting Toolbar --}}
                <div>
                    <label class="block text-xs font-bold text-[#334155] uppercase tracking-wider mb-1.5">
                        Konten Artikel <span class="text-rose-500">*</span>
                    </label>

                    <div 
                        wire:ignore
                        x-data="{
                            html: @entangle('content'),
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
                                    $wire.set('content', val, false);
                                }, 300);
                            },
                            onBlur() {
                                this.isFocused = false;
                                let val = this.$refs.editor.innerHTML;
                                this.html = val;
                                $wire.set('content', val, false);
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
                                title="Daftar Poin"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16M2 6h.01M2 12h.01M2 18h.01"/></svg>
                            </button>

                            {{-- Numbered List --}}
                            <button 
                                type="button" 
                                @click="exec('insertOrderedList')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Daftar Nomor"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 5v3m0 3v3m0 3v3"/></svg>
                            </button>

                            {{-- Blockquote --}}
                            <button 
                                type="button" 
                                @click="format('blockquote')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] font-serif font-bold text-sm transition-colors cursor-pointer" 
                                title="Kutipan (Quote)"
                            >
                                “ ”
                            </button>

                            <div class="h-4 w-px bg-[#cbd5e1] mx-1"></div>

                            {{-- Undo --}}
                            <button 
                                type="button" 
                                @click="exec('undo')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Urungkan (Undo)"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2M3 10l6-6M3 10l6 6"/></svg>
                            </button>

                            {{-- Redo --}}
                            <button 
                                type="button" 
                                @click="exec('redo')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Ulangi (Redo)"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m15-7l-6-6m6 6l-6 6"/></svg>
                            </button>

                            {{-- Remove Format --}}
                            <button 
                                type="button" 
                                @click="exec('removeFormat')" 
                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 hover:text-[#0f172a] text-[#475569] transition-colors cursor-pointer" 
                                title="Hapus Format"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- WYSIWYG Content Area --}}
                        <div
                            x-ref="editor"
                            contenteditable="true"
                            @focus="isFocused = true"
                            @input="update()"
                            @blur="onBlur()"
                            placeholder="Tulis isi konten artikel di sini..."
                            class="rich-editor p-4 min-h-[280px] focus:outline-none text-sm leading-relaxed w-full break-words"
                        ></div>
                    </div>
                    @error('content') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pengaturan & Media (1 Kolom) --}}
        <div class="space-y-5">
            {{-- Card 1: Publikasi --}}
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 sm:p-5 shadow-xs space-y-3.5">
                <h3 class="text-xs font-bold text-[#0f172a] uppercase tracking-wider border-b border-[#f1f5f9] pb-2.5">
                    Publikasi
                </h3>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-[#475569] mb-1">Status</label>
                    <select
                        wire:model="status"
                        class="w-full px-3 py-2 text-xs bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none cursor-pointer font-medium"
                    >
                        <option value="Publik">Publik</option>
                        <option value="Draf">Draf</option>
                        <option value="Disembunyikan">Disembunyikan</option>
                    </select>
                </div>

                {{-- Tanggal Publikasi --}}
                <div>
                    <label class="block text-xs font-semibold text-[#475569] mb-1">Tanggal Publikasi</label>
                    <input
                        type="text"
                        placeholder="DD / MM / YYYY"
                        wire:model="publishDate"
                        class="w-full px-3 py-2 text-xs font-mono bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none"
                    />
                </div>

                {{-- Tombol Simpan Cepat --}}
                <div class="pt-2 border-t border-[#f1f5f9]">
                    <button
                        wire:click="save"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        type="button"
                        class="w-full py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-semibold rounded-lg shadow-xs transition-colors cursor-pointer text-center disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="save">{{ $mode === 'add' ? 'Simpan Artikel' : 'Perbarui Artikel' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </div>

            {{-- Card 2: Media & Berkas --}}
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 sm:p-5 shadow-xs space-y-4">
                <h3 class="text-xs font-bold text-[#0f172a] uppercase tracking-wider border-b border-[#f1f5f9] pb-2.5">
                    Thumbnail Gambar
                </h3>

                {{-- Pilihan Sumber Gambar: Upload atau URL --}}
                <div class="space-y-2">
                    <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-lg text-xs font-semibold text-[#475569]">
                        <button 
                            type="button"
                            wire:click="$set('imageSourceType', 'upload')"
                            class="flex-1 py-1 rounded-md transition-all text-center cursor-pointer {{ $imageSourceType === 'upload' ? 'bg-white text-[#0284c7] shadow-xs font-bold' : 'hover:text-[#0f172a]' }}"
                        >
                            📁 Unggah Berkas
                        </button>
                        <button 
                            type="button"
                            wire:click="$set('imageSourceType', 'url')"
                            class="flex-1 py-1 rounded-md transition-all text-center cursor-pointer {{ $imageSourceType === 'url' ? 'bg-white text-[#0284c7] shadow-xs font-bold' : 'hover:text-[#0f172a]' }}"
                        >
                            🔗 Tautan / URL
                        </button>
                    </div>

                    @if($imageSourceType === 'upload')
                        {{-- Mode Unggah File --}}
                        @if($image)
                            <div class="rounded-lg border border-[#cbd5e1] bg-slate-50 p-2 flex items-center justify-between gap-2.5">
                                <img src="{{ $image->temporaryUrl() }}" class="w-14 h-10 object-cover rounded border border-[#e2e8f0]">
                                <span class="text-xs font-medium text-emerald-700 truncate flex-1">Gambar dipilih</span>
                                <button type="button" wire:click="removeImage" class="text-xs text-rose-600 hover:underline shrink-0 cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        @elseif($existingImage && !str_starts_with($existingImage, 'http'))
                            <div class="rounded-lg border border-[#cbd5e1] bg-slate-50 p-2 flex items-center justify-between gap-2.5">
                                <img src="{{ Storage::url($existingImage) }}" class="w-14 h-10 object-cover rounded border border-[#e2e8f0]">
                                <span class="text-xs font-medium text-[#475569] truncate flex-1">Gambar saat ini</span>
                                <button type="button" wire:click="removeImage" class="text-xs text-rose-600 hover:underline shrink-0 cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        @else
                            <label class="flex items-center justify-center gap-2 p-3 border border-dashed border-[#cbd5e1] hover:border-[#0284c7] rounded-lg bg-[#f8fafc] hover:bg-sky-50/30 cursor-pointer transition-colors group">
                                <input type="file" wire:model="image" class="hidden" accept="image/*">
                                <svg class="w-4 h-4 text-[#94a3b8] group-hover:text-[#0284c7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs font-medium text-[#0284c7]">Pilih File Gambar</span>
                            </label>
                        @endif
                        @error('image') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror
                    @else
                        {{-- Mode Ambil dari URL --}}
                        <div class="space-y-2">
                            <input 
                                type="url" 
                                wire:model.live.debounce.300ms="imageUrlInput" 
                                placeholder="https://contoh.com/gambar.jpg"
                                class="w-full px-3 py-1.5 text-xs bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none"
                            />
                            @error('imageUrlInput') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror

                            @if($imageUrlInput)
                                <div class="relative rounded-lg overflow-hidden border border-[#cbd5e1] bg-slate-100 aspect-video flex items-center justify-center">
                                    <img 
                                        src="{{ $imageUrlInput }}" 
                                        alt="Preview" 
                                        class="w-full h-full object-cover"
                                        onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='<span class=\'text-xs text-rose-500 p-2 text-center\'>Tautan gambar tidak dapat dimuat</span>';"
                                    />
                                    <button 
                                        type="button" 
                                        wire:click="removeImage" 
                                        class="absolute top-1.5 right-1.5 px-2 py-0.5 rounded bg-black/70 hover:bg-black text-white text-[10px] font-semibold transition-colors cursor-pointer"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Berkas Pendukung --}}
                <div class="space-y-1.5 pt-2 border-t border-[#f1f5f9]">
                    <label class="block text-xs font-semibold text-[#475569]">Berkas Pendukung</label>
                    @if($existingSupportingFileUrl && !$supportingFile)
                        <div class="p-2 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between gap-2">
                            <span class="text-xs font-medium text-[#0f172a] truncate">{{ $existingSupportingFileName ?? 'Berkas' }}</span>
                            <button type="button" wire:click="deleteExistingSupportingFile" class="text-xs text-rose-600 hover:underline shrink-0 cursor-pointer">
                                Hapus
                            </button>
                        </div>
                    @elseif($supportingFile)
                        <div class="p-2 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-2">
                            <span class="text-xs font-medium text-emerald-800 truncate">{{ $supportingFile->getClientOriginalName() }}</span>
                            <label class="text-xs text-[#0284c7] hover:underline cursor-pointer shrink-0">
                                Ganti
                                <input type="file" wire:model="supportingFile" class="hidden">
                            </label>
                        </div>
                    @else
                        <label class="flex items-center justify-center gap-2 p-3 border border-dashed border-[#cbd5e1] hover:border-[#0284c7] rounded-lg bg-[#f8fafc] hover:bg-sky-50/30 cursor-pointer transition-colors group">
                            <input type="file" wire:model="supportingFile" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt">
                            <svg class="w-4 h-4 text-[#94a3b8] group-hover:text-[#0284c7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="text-xs font-medium text-[#0284c7]">Unggah Dokumen</span>
                        </label>
                    @endif
                    @error('supportingFile') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror
                </div>

                {{-- Kebijakan Akses Berkas --}}
                <div class="pt-2 border-t border-[#f1f5f9]">
                    <label class="block text-xs font-semibold text-[#475569] mb-1">Akses Berkas</label>
                    <select
                        wire:model="fileAccessType"
                        class="w-full px-3 py-2 text-xs bg-white border border-[#cbd5e1] rounded-lg text-[#0f172a] focus:border-[#0284c7] outline-none cursor-pointer font-medium"
                    >
                        <option value="Publik">Akses Publik</option>
                        <option value="Perlu Ajukan">Perlu Pengajuan Akses</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
