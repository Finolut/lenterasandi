@props(['active' => 'Dashboard'])

@php
$sidebarCounts = \Illuminate\Support\Facades\Cache::remember('admin_sidebar_counts', 300, function () {
    return [
        'ebook'     => \App\Models\Ebook::count(),
        'informasi' => \App\Models\Informasi::count(),
        'pesan'     => \App\Models\PermohonanAkses::whereNull('dibaca_at')->count(),
        'user'      => \App\Models\User::count(),
    ];
});

$ebookCount = $sidebarCounts['ebook'];
$informasiCount = $sidebarCounts['informasi'];
$pesanCount = $sidebarCounts['pesan'];
$userCount = $sidebarCounts['user'];

$navItems = [
    [
        'label' => 'Dashboard',
        'route' => 'admin.dashboard',
        'icon'  => 'dashboard',
        'badge' => null,
    ],
    [
        'label' => 'Kelola E-Book',
        'route' => 'admin.ebooks.index',
        'icon'  => 'book',
        'badge' => $ebookCount,
    ],
    [
        'label' => 'Kelola Artikel',
        'route' => 'admin.informasi.index',
        'icon'  => 'newspaper',
        'badge' => $informasiCount,
    ],
    [
        'label' => 'Pesan Masuk',
        'route' => 'admin.pesan.index',
        'icon'  => 'inbox',
        'badge' => $pesanCount > 0 ? $pesanCount : null,
    ],
    [
        'label' => 'Kelola Pengguna',
        'route' => 'admin.users.index',
        'icon'  => 'users',
        'badge' => $userCount,
    ],
];
@endphp

<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:sticky top-0 left-0 w-64 h-screen self-start bg-[#083575] text-sky-100 flex flex-col shrink-0 border-r border-[#0b489a] select-none z-50 lg:z-40 transition-transform duration-200 ease-in-out overflow-hidden"
>
    {{-- Header / Brand Section --}}
    <div class="h-16 px-4 border-b border-[#0b489a] bg-[#062859] flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 rounded-md bg-white p-1 flex items-center justify-center shrink-0 shadow-2xs">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Lentera Sandi" class="w-full h-full object-contain">
            </div>
            <div class="leading-tight truncate">
                <a href="{{ route('admin.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="font-semibold text-white tracking-wide hover:text-cyan-300 text-sm flex items-center transition-colors">
                    LENTERA<span class="text-cyan-400 font-bold ml-1">SANDI</span>
                </a>
                <div class="text-[11px] text-sky-200/70 font-normal">Panel Administrasi</div>
            </div>
        </div>

        <button 
            type="button" 
            @click="sidebarOpen = false" 
            class="lg:hidden p-1.5 rounded-md text-sky-200 hover:text-white hover:bg-white/10 transition-colors cursor-pointer"
            title="Tutup Menu"
            aria-label="Tutup Menu"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation Menu --}}
    <div class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
        {{-- Section: Menu Utama --}}
        <div>
            <div class="px-3 mb-1.5 text-[11px] font-semibold tracking-wider text-sky-200/60 uppercase">
                Menu Utama
            </div>
            <nav class="space-y-1">
                @foreach($navItems as $item)
                    @php
                        $isActive = ($active === $item['label']) || (request()->routeIs($item['route'].'*'));
                    @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        wire:navigate
                        @click="sidebarOpen = false"
                        class="group relative flex items-center justify-between h-11 px-3 rounded-md text-xs sm:text-sm font-medium transition-colors duration-150 {{ $isActive ? 'bg-[#0e438c] text-white' : 'text-sky-100/80 hover:text-white hover:bg-white/[0.06]' }}"
                    >
                        {{-- Thin cyan vertical indicator for active item --}}
                        @if($isActive)
                            <span class="absolute left-0 top-2 bottom-2 w-1 bg-cyan-400 rounded-r-full"></span>
                        @endif

                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Lucide Icons --}}
                            @if($item['icon'] === 'dashboard')
                                <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-white' : 'text-sky-300/70 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                                </svg>
                            @elseif($item['icon'] === 'book')
                                <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-white' : 'text-sky-300/70 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path d="M12 7v14"/>
                                    <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                                </svg>
                            @elseif($item['icon'] === 'newspaper')
                                <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-white' : 'text-sky-300/70 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                    <path d="M18 14h-8"/>
                                    <path d="M15 18h-5"/>
                                    <path d="M10 6h8v4h-8V6Z"/>
                                </svg>
                            @elseif($item['icon'] === 'inbox')
                                <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-white' : 'text-sky-300/70 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                                    <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                                </svg>
                            @elseif($item['icon'] === 'users')
                                <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-white' : 'text-sky-300/70 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            @endif
                            <span class="truncate">{{ $item['label'] }}</span>
                        </div>

                        {{-- Subtle Numerical Badge --}}
                        @if($item['badge'] !== null)
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium font-mono min-w-[20px] text-center shrink-0 {{ $isActive ? 'bg-cyan-500/15 text-cyan-200 border border-cyan-400/25' : 'bg-sky-400/10 text-sky-200/90 border border-sky-400/20' }}">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Section: Akses Publik --}}
        <div>
            <div class="px-3 mb-1.5 text-[11px] font-semibold tracking-wider text-sky-200/60 uppercase">
                Akses Publik
            </div>
            <div class="space-y-1">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="group flex items-center justify-between h-11 px-3 rounded-md text-xs sm:text-sm font-medium text-sky-100/80 hover:text-white hover:bg-white/[0.06] transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-sky-300/70 group-hover:text-white shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path d="M15 3h6v6"/>
                            <path d="M10 14 21 3"/>
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        </svg>
                        <span>Buka Portal Publik</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-sky-200/50 group-hover:text-white group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <button
                    type="button"
                    x-data
                    @click="window.dispatchEvent(new CustomEvent('open-qr-modal', { detail: { url: '{{ url('/') }}', title: 'Portal Lentera Sandi', subtitle: 'Diskominfo Provinsi Jawa Timur' } }))"
                    onclick="window.dispatchEvent(new CustomEvent('open-qr-modal', { detail: { url: '{{ url('/') }}', title: 'Portal Lentera Sandi', subtitle: 'Diskominfo Provinsi Jawa Timur' } }))"
                    class="w-full group flex items-center justify-between h-11 px-3 rounded-md text-xs sm:text-sm font-medium text-sky-100/80 hover:text-white hover:bg-white/[0.06] transition-colors cursor-pointer"
                >
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-sky-300/70 group-hover:text-white shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <rect width="5" height="5" x="3" y="3" rx="1"/>
                            <rect width="5" height="5" x="16" y="3" rx="1"/>
                            <rect width="5" height="5" x="3" y="16" rx="1"/>
                            <path d="M21 16h-3a2 2 0 0 0-2 2v3"/>
                            <path d="M21 21v.01"/>
                            <path d="M12 7v3a2 2 0 0 1-2 2H7"/>
                            <path d="M3 12h.01"/>
                            <path d="M12 3h.01"/>
                            <path d="M12 16v.01"/>
                            <path d="M16 12h1"/>
                            <path d="M21 12v.01"/>
                            <path d="M12 21v-1"/>
                        </svg>
                        <span>QR Code Portal</span>
                    </div>
                </button>
            </div>
        </div>

        {{-- Section: Sesi --}}
        <div>
            <div class="px-3 mb-1.5 text-[11px] font-semibold tracking-wider text-sky-200/60 uppercase">
                Sesi
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full group flex items-center justify-between h-11 px-3 rounded-md text-xs sm:text-sm font-medium text-sky-100/80 hover:text-white hover:bg-white/[0.06] transition-colors cursor-pointer"
                >
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-sky-300/70 group-hover:text-white shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                        <span>Logout</span>
                    </div>
                </button>
            </form>
        </div>
    </div>

    {{-- User Profile at Bottom --}}
    <div class="px-4 py-3 border-t border-[#0b489a] bg-[#062859] mt-auto shrink-0 flex items-center gap-3">
        @if(auth()->user()?->avatar_url)
            <img src="{{ auth()->user()->getAvatar() }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-md object-cover shrink-0 select-none border border-sky-300/30" />
        @else
            <div class="w-9 h-9 rounded-md bg-white/10 text-white flex items-center justify-center font-semibold text-xs shrink-0 select-none border border-white/10">
                {{ auth()->user()?->initials() ?? 'AD' }}
            </div>
        @endif
        <div class="min-w-0 flex-1">
            <div class="text-xs font-medium text-white truncate leading-tight">
                {{ auth()->user()?->name ?? 'Administrator' }}
            </div>
            <div class="text-[11px] text-sky-200/70 truncate mt-0.5">
                {{ auth()->user()?->email ?? 'admin@jatimprov.go.id' }}
            </div>
        </div>
    </div>
</aside>
