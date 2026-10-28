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
    class="fixed lg:sticky top-0 left-0 w-64 h-screen self-start bg-[#0a1224] text-slate-400 flex flex-col shrink-0 border-r border-[#1e293b] select-none z-50 lg:z-40 transition-transform duration-250 ease-in-out overflow-hidden shadow-2xl lg:shadow-none"
>
    {{-- Header / Brand Section (h-16 to perfectly align with topbar) --}}
    <div class="h-16 px-5 border-b border-[#1e293b] bg-[#070d1a] flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-white p-1 flex items-center justify-center shrink-0 shadow-xs">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Lentera Sandi" class="w-full h-full object-contain">
            </div>
            <div class="leading-tight truncate">
                <a href="{{ route('admin.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="font-bold text-white tracking-tight hover:text-[#38bdf8] text-sm flex items-center transition-colors">
                    LENTERA<span class="text-[#38bdf8] ml-0.5">SANDI</span>
                </a>
                <div class="text-[10px] text-slate-400 font-medium">Panel Administrasi</div>
            </div>
        </div>

        <button 
            type="button" 
            @click="sidebarOpen = false" 
            class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
            title="Tutup Menu"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation Menu --}}
    <div class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
        {{-- Section: Menu Utama --}}
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase font-mono">
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
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-150 {{ $isActive ? 'bg-[#0284c7] text-white shadow-xs font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            {{-- SVG Icons --}}
                            @if($item['icon'] === 'dashboard')
                                <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            @elseif($item['icon'] === 'book')
                                <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            @elseif($item['icon'] === 'newspaper')
                                <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            @elseif($item['icon'] === 'inbox')
                                <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            @elseif($item['icon'] === 'users')
                                <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            @endif
                            <span class="truncate">{{ $item['label'] }}</span>
                        </div>

                        @if($item['badge'] !== null)
                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold font-mono {{ $item['icon'] === 'inbox' && !$isActive ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : ($isActive ? 'bg-white/20 text-white' : 'bg-[#1e293b] text-slate-400') }}">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Section: Pintasan Eksternal --}}
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase font-mono">
                Akses Publik
            </div>
            <a
                href="{{ route('home') }}"
                target="_blank"
                class="flex items-center justify-between px-3 py-2 rounded-lg text-xs sm:text-sm font-medium text-slate-400 hover:text-[#38bdf8] hover:bg-slate-800/60 transition-colors group mb-1"
            >
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-[#38bdf8] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Buka Portal Publik</span>
                </div>
                <svg class="w-3.5 h-3.5 opacity-50 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <button
                type="button"
                x-data
                @click="window.dispatchEvent(new CustomEvent('open-qr-modal', { detail: { url: '{{ url('/') }}', title: 'Portal Lentera Sandi', subtitle: 'Diskominfo Provinsi Jawa Timur' } }))"
                onclick="window.dispatchEvent(new CustomEvent('open-qr-modal', { detail: { url: '{{ url('/') }}', title: 'Portal Lentera Sandi', subtitle: 'Diskominfo Provinsi Jawa Timur' } }))"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs sm:text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors group cursor-pointer"
            >
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <span>QR Code Portal</span>
                </div>
            </button>
        </div>

        {{-- Section: Autentikasi / Logout --}}
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase font-mono">
                Sesi
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs sm:text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all duration-150 cursor-pointer group"
                >
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </div>
                </button>
            </form>
        </div>
    </div>

    {{-- User Profile Card at Bottom --}}
    <div class="p-3 border-t border-[#1e293b] bg-[#070d1a] mt-auto shrink-0">
        <div class="p-2.5 rounded-lg bg-[#0f172a] border border-[#1e293b] flex items-center gap-3">
            @if(auth()->user()?->avatar_url)
                <img src="{{ auth()->user()->getAvatar() }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-lg object-cover shrink-0 select-none border border-[#1e293b]" />
            @else
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-[#0284c7] to-[#083575] text-white flex items-center justify-center font-bold text-xs shrink-0 select-none">
                    {{ auth()->user()?->initials() ?? 'AD' }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <div class="text-xs font-semibold text-white truncate leading-tight">
                    {{ auth()->user()?->name ?? 'Administrator' }}
                </div>
                <div class="text-[10px] text-slate-400 truncate mt-0.5">
                    {{ auth()->user()?->email ?? 'admin@jatimprov.go.id' }}
                </div>
            </div>
        </div>
    </div>
</aside>
