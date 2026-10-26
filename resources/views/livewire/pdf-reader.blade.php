<div class="flex flex-col h-screen bg-[#1a1a1a] text-white overflow-hidden select-none">
    {{-- Top Bar --}}
    <header class="bg-[#2d2d2d] border-b border-[#444444] px-4 py-2.5 flex items-center justify-between shrink-0 z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('ebooks.index') }}" class="inline-flex items-center justify-center font-medium border text-xs px-3 py-1 bg-transparent text-[#cccccc] border-[#555555] hover:text-white hover:border-[#888888] transition-colors">
                ← Kembali
            </a>
            <div class="h-4 w-px bg-[#555555]"></div>
            <div class="text-sm text-[#cccccc] max-w-xs md:max-w-md truncate font-medium">
                {{ $title }}
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- Page Navigation --}}
            <div class="flex items-center gap-1 bg-[#1a1a1a] border border-[#555555] px-2 py-1">
                <button wire:click="prevPage" class="text-[#cccccc] text-xs px-1.5 hover:text-white cursor-pointer" title="Halaman sebelumnya">◀</button>
                <span class="text-xs font-mono text-[#cccccc] px-1 min-w-[50px] text-center">{{ $currentPage }} / {{ $totalPages }}</span>
                <button wire:click="nextPage" class="text-[#cccccc] text-xs px-1.5 hover:text-white cursor-pointer" title="Halaman berikutnya">▶</button>
            </div>

            {{-- Zoom Control --}}
            <div class="hidden sm:flex items-center gap-1 bg-[#1a1a1a] border border-[#555555] px-2 py-1">
                <button wire:click="zoomOut" class="text-[#cccccc] text-xs px-1.5 hover:text-white cursor-pointer" title="Zoom Out">−</button>
                <span class="text-xs font-mono text-[#cccccc] px-1 min-w-[42px] text-center">{{ $zoom }}%</span>
                <button wire:click="zoomIn" class="text-[#cccccc] text-xs px-1.5 hover:text-white cursor-pointer" title="Zoom In">+</button>
            </div>

            <div class="hidden md:block h-4 w-px bg-[#555555]"></div>

            {{-- Search in doc --}}
            <div class="hidden lg:flex items-center gap-2 bg-[#1a1a1a] border border-[#555555] px-2 py-1 w-44">
                <span class="text-[#aaaaaa] text-xs">🔍</span>
                <input
                    type="text"
                    wire:model.live="searchQuery"
                    placeholder="Cari dalam dokumen"
                    class="bg-transparent text-xs text-[#cccccc] placeholder-[#666666] focus:outline-none w-full"
                />
            </div>

            <div class="h-4 w-px bg-[#555555]"></div>

            {{-- Download --}}
            <a href="#" class="inline-flex items-center justify-center font-medium border text-xs px-3 py-1 bg-transparent text-[#cccccc] border-[#555555] hover:bg-[#333333] hover:text-white transition-colors">
                Unduh PDF
            </a>
        </div>
    </header>

    {{-- PDF Canvas Area --}}
    <div class="flex-1 overflow-auto flex items-start justify-center py-8 px-4 bg-[#404040]">
        {{-- PDF Simulated Page --}}
        <div
            class="flex flex-col items-center gap-3 w-full transition-all duration-150"
            style="max-width: {{ 720 * ($zoom / 100) }}px;"
        >
            <div class="bg-white text-[#1a1a1a] shadow-2xl w-full" style="aspect-ratio: 8.5 / 11;">
                <div class="p-8 sm:p-12 flex flex-col gap-6 h-full justify-between">
                    {{-- Header of page --}}
                    <div>
                        <div class="border-b-2 border-[#e0e0e0] pb-4">
                            <div class="h-6 bg-[#d0d0d0] w-3/4 mb-2 rounded-xs"></div>
                            <div class="h-3 bg-[#e8e8e8] w-1/2 rounded-xs"></div>
                        </div>

                        {{-- Body content simulation --}}
                        <div class="flex gap-6 mt-6">
                            <div class="flex-1 space-y-3">
                                <div class="h-4 bg-[#e0e0e0] w-full rounded-xs"></div>
                                <div class="h-4 bg-[#e0e0e0] w-full rounded-xs"></div>
                                <div class="h-4 bg-[#e0e0e0] w-5/6 rounded-xs"></div>
                                <div class="h-4 bg-[#e8e8e8] w-full rounded-xs"></div>
                                <div class="h-4 bg-[#e8e8e8] w-4/5 rounded-xs"></div>
                                <div class="h-20 bg-[#f5f5f5] border border-[#e0e0e0] p-4 text-xs font-mono text-[#777777] rounded-xs mt-4">
                                    [ BAB {{ $currentPage }}: Prinsip Keamanan & Manajemen Akses Pengguna ]
                                    <div class="text-[10px] text-[#999999] mt-2">Kombinasi huruf besar, huruf kecil, angka, dan karakter khusus.</div>
                                </div>
                                <div class="h-4 bg-[#e0e0e0] w-full rounded-xs"></div>
                                <div class="h-4 bg-[#e0e0e0] w-3/4 rounded-xs"></div>
                                <div class="h-4 bg-[#e8e8e8] w-full rounded-xs"></div>
                                <div class="h-4 bg-[#e8e8e8] w-2/3 rounded-xs"></div>
                            </div>
                            <div class="hidden sm:block w-48 space-y-2 shrink-0">
                                <div class="bg-[#f5f5f5] border border-[#e0e0e0] p-3">
                                    <div class="h-3 bg-[#d8d8d8] w-full mb-2 rounded-xs"></div>
                                    <div class="h-3 bg-[#e0e0e0] w-4/5 mb-2 rounded-xs"></div>
                                    <div class="h-3 bg-[#e0e0e0] w-3/5 rounded-xs"></div>
                                </div>
                                <div class="h-32 bg-[#e8e8e8] border border-[#dddddd] flex items-center justify-center text-[#aaaaaa] text-xs font-mono">
                                    ILUSTRASI
                                </div>
                                <div class="h-3 bg-[#e8e8e8] w-3/4 rounded-xs"></div>
                                <div class="h-3 bg-[#e8e8e8] w-full rounded-xs"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer of page --}}
                    <div class="flex items-center justify-between pt-3 border-t border-[#f0f0f0]">
                        <span class="text-[10px] text-[#bbbbbb] font-mono">Diskominfo Provinsi Jawa Timur — LENTERA SANDI</span>
                        <span class="text-[10px] text-[#bbbbbb] font-mono">{{ $currentPage }}</span>
                    </div>
                </div>
            </div>

            {{-- Page pill indicator --}}
            <div class="text-xs font-mono text-[#aaaaaa] bg-[#333333] px-3 py-1 border border-[#555555] rounded-xs">
                Halaman {{ $currentPage }} dari {{ $totalPages }}
            </div>
        </div>
    </div>

    {{-- Bottom Toolbar --}}
    <footer class="bg-[#2d2d2d] border-t border-[#444444] px-4 py-2 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2 sm:gap-3">
            <button
                wire:click="toggleSidebar"
                class="text-xs text-[#aaaaaa] border border-[#555555] px-3 py-1 hover:text-white hover:border-[#888888] font-mono cursor-pointer transition-colors"
            >
                📖 Daftar Isi
            </button>
            <button class="hidden sm:inline text-xs text-[#aaaaaa] border border-[#555555] px-3 py-1 hover:text-white font-mono cursor-pointer transition-colors">
                📄 Tampilan Halaman
            </button>
            <button class="hidden md:inline text-xs text-[#aaaaaa] border border-[#555555] px-3 py-1 hover:text-white font-mono cursor-pointer transition-colors">
                ⤢ Layar Penuh
            </button>
        </div>

        {{-- Jump to page buttons --}}
        <div class="flex items-center gap-1 overflow-x-auto">
            @foreach([1, 2, 3, 4, 5, '...', 32] as $p)
                @if($p === '...')
                    <span class="px-1 text-xs text-[#777777] font-mono">...</span>
                @else
                    <button
                        wire:click="goToPage({{ (int)$p }})"
                        class="w-6 h-6 text-[10px] font-mono rounded-xs transition-colors cursor-pointer {{ $currentPage === (int)$p ? 'bg-white text-[#1a1a1a] font-bold' : 'text-[#888888] hover:text-white hover:bg-[#444444]' }}"
                    >
                        {{ $p }}
                    </button>
                @endif
            @endforeach
        </div>
    </footer>
</div>
