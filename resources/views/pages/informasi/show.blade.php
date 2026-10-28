@php
    $informasi = \App\Models\Informasi::where('status', 'Publik')->findOrFail($id);
    
    // Increment view count
    $informasi->increment('jumlah_dilihat');

    // Calculate reading time estimate
    $wordCount = str_word_count(strip_tags($informasi->konten ?? ''));
    $readMinutes = max(1, (int) ceil($wordCount / 180));

    // Related articles
    $related = \App\Models\Informasi::where('status', 'Publik')
        ->where('id', '!=', $informasi->id)
        ->latest('published_at')
        ->take(3)
        ->get();
@endphp

<x-layouts.app active="Artikel" title="{{ $informasi->judul }} - Lentera Sandi">
    <div class="w-full bg-[#f8fafc] dark:bg-[#0b1329] text-[#0f172a] dark:text-[#f8fafc] pb-20 transition-colors duration-200">
        {{-- Main Article Content Area --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- Article Header --}}
            <header class="mb-6">
                {{-- Category Badges --}}
                @if(count($informasi->kategori_list) > 0)
                    <div class="flex items-center gap-1.5 mb-3 flex-wrap">
                        @foreach($informasi->kategori_list as $kat)
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-[#38bdf8] dark:border-sky-800 text-[#0284c7] dark:text-sky-300 bg-[#f0f9ff] dark:bg-sky-950/60">
                                {{ $kat }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Headline Title --}}
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#0f3b7a] dark:text-sky-200 tracking-tight leading-snug sm:leading-tight mb-4">
                    {{ $informasi->judul }}
                </h1>

                {{-- Author & Meta Info Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-3 py-3 border-y border-[#e2e8f0] dark:border-slate-800 text-xs text-[#64748b] dark:text-slate-400">
                    <div class="flex items-center gap-3">
                        @php
                            $authorAvatar = $informasi->creator ? $informasi->creator->getAvatar() : null;
                        @endphp
                        @if($authorAvatar)
                            <img src="{{ $authorAvatar }}" alt="{{ $informasi->creator->name }}" class="w-9 h-9 rounded-full object-cover border border-[#cbd5e1] dark:border-slate-700 shadow-2xs shrink-0">
                        @elseif($informasi->creator)
                            <div class="w-9 h-9 rounded-full bg-[#0284c7] text-white flex items-center justify-center font-bold text-xs shadow-2xs shrink-0">
                                {{ $informasi->creator->initials() }}
                            </div>
                        @else
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Redaksi Lentera Sandi" class="w-9 h-9 rounded-full object-cover p-0.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0">
                        @endif
                        <div>
                            <div class="font-bold text-[#0f172a] dark:text-slate-100 text-sm leading-snug">
                                {{ $informasi->creator->name ?? 'Redaksi Lentera Sandi' }}
                            </div>
                            <div class="text-[11px] text-[#64748b] dark:text-slate-400">
                                Penulis / Redaksi
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-[#64748b] dark:text-slate-400 flex-wrap">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#94a3b8] dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $informasi->tanggal_publish }}</span>
                        </span>
                        <span>•</span>
                        <span>👁 {{ $informasi->jumlah_dilihat }} dilihat</span>
                    </div>
                </div>
            </header>

            {{-- Main Featured Banner Image (16:9 Standard, Tidak Terpotong) --}}
            @if($informasi->gambar)
                <div class="mb-8 rounded-2xl overflow-hidden border border-[#e2e8f0] dark:border-slate-800 shadow-sm bg-[#0f172a]">
                    <div class="w-full aspect-video sm:aspect-[16/9] relative overflow-hidden flex items-center justify-center">
                        <img
                            src="{{ $informasi->gambar }}"
                            alt="{{ $informasi->judul }}"
                            class="w-full h-full object-cover object-center"
                        >
                    </div>
                </div>
            @endif

            {{-- Article Body (Gaya Baca Berita Nyaman & Elegan dengan Dukungan Rich Text) --}}
            <article class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e2e8f0] dark:border-slate-800 p-6 sm:p-10 shadow-xs mb-10 transition-colors duration-200">
                <style>
                    .article-prose h2 { font-size: 1.45rem; font-weight: 700; color: #0f172a; margin-top: 1.5rem; margin-bottom: 0.5rem; line-height: 1.35; }
                    .article-prose h3 { font-size: 1.25rem; font-weight: 600; color: #1e293b; margin-top: 1.25rem; margin-bottom: 0.4rem; line-height: 1.4; }
                    .article-prose h4 { font-size: 1.1rem; font-weight: 600; color: #334155; margin-top: 1rem; margin-bottom: 0.35rem; }
                    .article-prose p { margin-bottom: 1rem; line-height: 1.8; color: #334155; }
                    .article-prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
                    .article-prose ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
                    .article-prose li { margin-bottom: 0.35rem; line-height: 1.7; }
                    .article-prose blockquote { border-left: 4px solid #0284c7; padding-left: 1rem; font-style: italic; color: #475569; margin: 1.25rem 0; background: #f8fafc; padding-top: 0.5rem; padding-bottom: 0.5rem; border-radius: 0 8px 8px 0; }
                    .article-prose a { color: #0284c7; text-decoration: underline; }
                    .article-prose b, .article-prose strong { font-weight: 700; color: #0f172a; }
                    .dark .article-prose h2 { color: #f1f5f9; }
                    .dark .article-prose h3 { color: #e2e8f0; }
                    .dark .article-prose h4 { color: #cbd5e1; }
                    .dark .article-prose p { color: #cbd5e1; }
                    .dark .article-prose blockquote { color: #94a3b8; background: #1e293b; border-color: #38bdf8; }
                    .dark .article-prose a { color: #38bdf8; }
                    .dark .article-prose b, .dark .article-prose strong { color: #f8fafc; }
                    .article-prose u { text-decoration: underline; }
                    .article-prose i, .article-prose em { font-style: italic; }
                </style>
                <div class="article-prose text-[#334155] dark:text-slate-300 text-base sm:text-[17px] leading-relaxed">
                    @if(strip_tags($informasi->konten) !== $informasi->konten)
                        {!! $informasi->konten !!}
                    @else
                        @php
                            $paragraphs = preg_split('/(\r\n|\n|\r)/', $informasi->konten);
                        @endphp
                        @foreach($paragraphs as $para)
                            @php $trimmed = trim($para); @endphp
                            @if(!empty($trimmed))
                                <p class="leading-[1.8]">{{ $trimmed }}</p>
                            @endif
                        @endforeach
                    @endif
                </div>
            </article>


            {{-- Berkas / Dokumen Pendukung (Opsional: Publik / Perlu Ajukan) --}}
            <livewire:file-pendukung-card :informasi-id="$informasi->id" />

            {{-- Related News Section (Format Kartu 16:9 yang Selaras) --}}
            @if($related->count() > 0)
                <section class="mt-12">
                    <div class="flex items-center justify-between mb-6 pb-2 border-b border-[#e2e8f0] dark:border-slate-800">
                        <h2 class="text-lg sm:text-xl font-bold text-[#0f3b7a] dark:text-sky-300 flex items-center gap-2">
                            <span>Artikel Terkait</span>
                        </h2>
                        <a
                            href="{{ route('informasi.index') }}"
                            class="text-xs sm:text-sm font-semibold text-[#0284c7] dark:text-sky-400 hover:text-[#0369a1] dark:hover:text-sky-300 hover:underline"
                        >
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                        @foreach($related as $rel)
                            <article class="bg-white dark:bg-slate-900 rounded-xl border border-[#e2e8f0] dark:border-slate-800 overflow-hidden shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 group flex flex-col h-full">
                                <div class="relative w-full aspect-video bg-[#0f172a] overflow-hidden">
                                    <a href="{{ route('informasi.show', $rel->id) }}" wire:navigate.hover class="block w-full h-full">
                                        @if($rel->gambar)
                                            <img
                                                src="{{ $rel->gambar }}"
                                                alt="{{ $rel->judul }}"
                                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-[#f1f5f9] dark:bg-slate-800 text-[#94a3b8] dark:text-slate-400 p-3 text-center">
                                                <svg class="w-8 h-8 stroke-current stroke-1 fill-none mb-1 text-[#94a3b8] dark:text-slate-400" viewBox="0 0 24 24">
                                                    <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                                </svg>
                                                <span class="text-[10px] font-medium text-[#64748b] dark:text-slate-400">Berita</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>

                                <div class="p-4 flex flex-col flex-1 justify-between">
                                    <div>
                                        <div class="flex items-center gap-1.5 mb-2 flex-wrap">
                                            @foreach($rel->kategori_list as $kat)
                                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold border border-[#38bdf8] dark:border-sky-800 text-[#0284c7] dark:text-sky-300 bg-[#f0f9ff] dark:bg-sky-950/60">
                                                    {{ $kat }}
                                                </span>
                                            @endforeach
                                            <span class="text-xs text-[#cbd5e1] dark:text-slate-600">•</span>
                                            <span class="text-[11px] text-[#64748b] dark:text-slate-400">{{ $rel->tanggal_publish }}</span>
                                        </div>

                                        <h3 class="text-sm font-bold text-[#0f3b7a] dark:text-slate-100 group-hover:text-[#1d4ed8] dark:group-hover:text-sky-300 transition-colors leading-snug line-clamp-2 mb-2">
                                            <a href="{{ route('informasi.show', $rel->id) }}" wire:navigate.hover>
                                                {{ $rel->judul }}
                                            </a>
                                        </h3>
                                    </div>

                                    <div class="pt-3 border-t border-[#f1f5f9] dark:border-slate-800 mt-3">
                                        <a
                                            href="{{ route('informasi.show', $rel->id) }}"
                                            wire:navigate.hover
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-[#0284c7] dark:text-sky-400 hover:text-[#0369a1] dark:hover:text-sky-300 transition-colors"
                                        >
                                            <span>Baca Artikel</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-layouts.app>
