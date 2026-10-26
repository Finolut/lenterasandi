@if ($paginator->hasPages())
    <div class="flex items-center gap-1.5">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center text-xs rounded-lg border border-[#e2e8f0] dark:border-slate-800 bg-[#f8fafc] dark:bg-slate-900/60 text-[#94a3b8] dark:text-slate-600 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <button wire:click="previousPage" class="w-8 h-8 flex items-center justify-center text-xs rounded-lg border border-[#e2e8f0] dark:border-slate-800 bg-white dark:bg-slate-900 text-[#475569] dark:text-slate-300 hover:text-[#0284c7] dark:hover:text-sky-400 hover:border-[#0284c7]/40 dark:hover:border-sky-500/40 hover:bg-sky-50/50 dark:hover:bg-slate-800 shadow-2xs transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-8 h-8 flex items-center justify-center text-xs text-[#94a3b8] dark:text-slate-500">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center text-xs font-semibold rounded-lg bg-[#0284c7] text-white shadow-2xs">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" class="w-8 h-8 flex items-center justify-center text-xs font-medium rounded-lg border border-[#e2e8f0] dark:border-slate-800 bg-white dark:bg-slate-900 text-[#475569] dark:text-slate-300 hover:text-[#0284c7] dark:hover:text-sky-400 hover:border-[#0284c7]/40 dark:hover:border-sky-500/40 hover:bg-sky-50/50 dark:hover:bg-slate-800 shadow-2xs transition-colors cursor-pointer">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" class="w-8 h-8 flex items-center justify-center text-xs rounded-lg border border-[#e2e8f0] dark:border-slate-800 bg-white dark:bg-slate-900 text-[#475569] dark:text-slate-300 hover:text-[#0284c7] dark:hover:text-sky-400 hover:border-[#0284c7]/40 dark:hover:border-sky-500/40 hover:bg-sky-50/50 dark:hover:bg-slate-800 shadow-2xs transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        @else
            <span class="w-8 h-8 flex items-center justify-center text-xs rounded-lg border border-[#e2e8f0] dark:border-slate-800 bg-[#f8fafc] dark:bg-slate-900/60 text-[#94a3b8] dark:text-slate-600 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </div>
@endif
