@props(['title' => 'Dashboard'])

<header class="h-16 bg-white/95 backdrop-blur-xs border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30">
    {{-- Left: Page Context & Title + Mobile Toggle --}}
    <div class="flex items-center gap-2.5 min-w-0">
        <button
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 -ml-1 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer"
            aria-label="Buka Menu Sidebar"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="flex items-center gap-2 min-w-0">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 hidden sm:inline">Admin /</span>
            <h1 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight truncate">
                {{ $title }}
            </h1>
        </div>
    </div>

    {{-- Right: Live Date & Status --}}
    <div class="flex items-center gap-3 shrink-0">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200/80 text-xs text-slate-600">
            <svg class="w-3.5 h-3.5 text-[#0284c7] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="font-medium text-slate-700 hidden sm:inline">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
            <span class="font-medium text-slate-700 sm:hidden">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d M Y') }}</span>
        </div>
    </div>
</header>
