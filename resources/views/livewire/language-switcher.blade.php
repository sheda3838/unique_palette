<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" type="button" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition-all text-[#2C3E50] dark:text-gray-200 font-bold uppercase tracking-wider text-xs">
        <span class="flex items-center gap-2">
            @if($currentLocale === 'en')
            <span class="text-lg">🇬🇧</span> English
            @elseif($currentLocale === 'si')
            <span class="text-lg">🇱🇰</span> සිංහල
            @elseif($currentLocale === 'ta')
            <span class="text-lg">🇱🇰</span> தமிழ்
            @endif
        </span>
        <svg class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-[70] overflow-hidden">

        <button wire:click="switchLanguage('en')" class="w-full text-left px-6 py-3 hover:bg-teal-50 dark:hover:bg-gray-700 flex items-center gap-3 transition-colors {{ $currentLocale === 'en' ? 'bg-teal-50 dark:bg-gray-700 text-[#1ABC9C]' : 'text-gray-600 dark:text-gray-300' }}">
            <span class="text-lg">🇬🇧</span>
            <span class="font-bold uppercase tracking-widest text-[10px]">English</span>
        </button>

        <button wire:click="switchLanguage('si')" class="w-full text-left px-6 py-3 hover:bg-teal-50 dark:hover:bg-gray-700 flex items-center gap-3 transition-colors {{ $currentLocale === 'si' ? 'bg-teal-50 dark:bg-gray-700 text-[#1ABC9C]' : 'text-gray-600 dark:text-gray-300' }}">
            <span class="text-lg">🇱🇰</span>
            <span class="font-bold uppercase tracking-widest text-[10px]">සිංහල</span>
        </button>

        <button wire:click="switchLanguage('ta')" class="w-full text-left px-6 py-3 hover:bg-teal-50 dark:hover:bg-gray-700 flex items-center gap-3 transition-colors {{ $currentLocale === 'ta' ? 'bg-teal-50 dark:bg-gray-700 text-[#1ABC9C]' : 'text-gray-600 dark:text-gray-300' }}">
            <span class="text-lg">🇱🇰</span>
            <span class="font-bold uppercase tracking-widest text-[10px]">தமிழ்</span>
        </button>
    </div>
</div>