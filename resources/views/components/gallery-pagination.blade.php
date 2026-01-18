<div>
    @if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center space-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <span class="p-3 rounded-2xl bg-gray-50 text-gray-300 cursor-not-allowed border border-gray-100 transition-all">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </span>
        @else
        <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="p-3 rounded-2xl bg-white text-[#1ABC9C] border-2 border-teal-50 hover:border-[#1ABC9C] hover:bg-teal-50 transition-all shadow-sm">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <span class="px-4 py-3 text-gray-400 font-black">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <span class="px-6 py-3 rounded-2xl bg-[#1ABC9C] text-white font-black text-base shadow-lg shadow-teal-100 transition-all border-2 border-[#1ABC9C]">
            {{ $page }}
        </span>
        @else
        <button wire:click="gotoPage({{ $page }})" class="px-6 py-3 rounded-2xl bg-white text-[#2C3E50] font-black text-base border-2 border-teal-50 hover:border-[#1ABC9C] hover:text-[#1ABC9C] transition-all shadow-sm">
            {{ $page }}
        </button>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="p-3 rounded-2xl bg-white text-[#1ABC9C] border-2 border-teal-50 hover:border-[#1ABC9C] hover:bg-teal-50 transition-all shadow-sm">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        @else
        <span class="p-3 rounded-2xl bg-gray-50 text-gray-300 cursor-not-allowed border border-gray-100 transition-all">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </span>
        @endif
    </nav>
    @endif
</div>