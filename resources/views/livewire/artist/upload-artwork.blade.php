<div class="py-24 bg-gray-50/50 dark:bg-gray-900/50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter">Share Your Masterpiece</h2>
            <div class="h-1.5 w-16 bg-[#1ABC9C] mx-auto mt-4 rounded-full"></div>
            <p class="mt-4 text-gray-500 font-bold uppercase tracking-widest text-xs">All submissions are carefully curated for approval</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-xl shadow-teal-50/50 border-2 border-teal-50 dark:border-gray-700 overflow-hidden transition-all duration-500">
            <form wire:submit.prevent="save" class="divide-y divide-gray-50 dark:divide-gray-700">
                <div class="p-10 lg:p-12 space-y-12">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {{-- Left Column: Details --}}
                        <div class="space-y-8">
                            <!-- Title -->
                            <div class="group">
                                <label for="title" class="block text-xs font-black text-[#1ABC9C] uppercase tracking-[0.2em] mb-3">{{ __('Artwork Title') }}</label>
                                <input id="title" type="text"
                                    class="block w-full px-6 py-4 border-2 border-teal-50 focus:border-[#1ABC9C] focus:ring-0 text-base rounded-2xl font-bold text-[#2C3E50] placeholder-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 transition-all"
                                    placeholder="Enter artwork title..."
                                    wire:model="title" />
                                <x-input-error for="title" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div class="group">
                                <label for="price" class="block text-xs font-black text-[#1ABC9C] uppercase tracking-[0.2em] mb-3">{{ __('Investment Value (LKR)') }}</label>
                                <input id="price" type="number" step="0.01"
                                    class="block w-full px-6 py-4 border-2 border-teal-50 focus:border-[#1ABC9C] focus:ring-0 text-base rounded-2xl font-bold text-[#2C3E50] placeholder-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 transition-all font-mono"
                                    placeholder="0.00"
                                    wire:model="price" />
                                <x-input-error for="price" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="group">
                                <label for="description" class="block text-xs font-black text-[#1ABC9C] uppercase tracking-[0.2em] mb-3">{{ __('The Masterpiece Story') }}</label>
                                <textarea id="description" rows="5"
                                    class="block w-full px-6 py-4 border-2 border-teal-50 focus:border-[#1ABC9C] focus:ring-0 text-base rounded-2xl font-bold text-[#2C3E50] placeholder-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 transition-all resize-none"
                                    placeholder="Describe the inspiration behind your piece..."
                                    wire:model="description"></textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>
                        </div>

                        {{-- Right Column: Image Upload --}}
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-[#1ABC9C] uppercase tracking-[0.2em] mb-3">{{ __('Visual Preview') }}</label>

                            <div class="relative">
                                <div class="mt-1 flex justify-center px-6 pt-10 pb-10 border-4 border-teal-50 dark:border-gray-700 border-dashed rounded-[30px] hover:border-[#1ABC9C] transition-colors relative group overflow-hidden bg-teal-50/20">
                                    <div class="space-y-4 text-center w-full">

                                        @if ($image)
                                            <div class="relative group/image cursor-pointer w-full">
                                                <label for="artwork-image" class="cursor-pointer block">
                                                    <img src="{{ $image->temporaryUrl() }}" class="mx-auto h-72 w-full object-cover rounded-2xl shadow-2xl transition-transform duration-500 group-hover/image:scale-[1.02]">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/image:opacity-100 transition-opacity rounded-2xl flex flex-col items-center justify-center gap-3">
                                                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-md border border-white/30">
                                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-white font-black text-xs uppercase tracking-widest">Change Artwork</p>
                                                    </div>
                                                </label>

                                                <input
                                                    id="artwork-image"
                                                    type="file"
                                                    accept="image/png,image/jpeg,image/webp"
                                                    class="sr-only"
                                                    wire:model="image"
                                                >
                                            </div>
                                        @else
                                            <div class="p-8">
                                                <div class="mx-auto h-20 w-20 bg-white rounded-2xl shadow-sm flex items-center justify-center text-[#1ABC9C] mb-6">
                                                    <svg class="h-10 w-10" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>

                                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                                    <label for="artwork-image" class="relative cursor-pointer font-black text-[#1ABC9C] hover:text-teal-600 transition-colors uppercase tracking-widest">
                                                        <span>Select Artwork</span>

                                                        <input
                                                            id="artwork-image"
                                                            type="file"
                                                            accept="image/png,image/jpeg,image/webp"
                                                            class="sr-only"
                                                            wire:model="image"
                                                        >
                                                    </label>
                                                </div>

                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2">
                                                    High-quality JPG/PNG/WebP (Max 10MB)
                                                </p>
                                            </div>
                                        @endif

                                        <div wire:loading wire:target="image" class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm z-10 flex items-center justify-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="animate-spin h-10 w-10 text-[#1ABC9C]" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="mt-4 text-[#1ABC9C] font-black uppercase tracking-widest text-[10px]">Processing Image...</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <x-input-error for="image" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="px-10 py-8 bg-gray-50/50 dark:bg-gray-700/50 flex flex-col sm:flex-row justify-between items-center gap-6">
                    <a href="{{ route('artist.artworks') }}" class="text-[#2C3E50] font-black uppercase tracking-widest text-xs hover:text-[#1ABC9C] transition-colors">
                        Cancel & Return
                    </a>

                    <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save, image"
                        class="w-full sm:w-auto px-12 py-5 bg-[#1ABC9C] text-white text-base font-black rounded-2xl hover:bg-teal-600 transition-all shadow-xl shadow-teal-100 uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3">
                        <span wire:loading.remove wire:target="save">Confirm Submission</span>
                        <span wire:loading wire:target="save">Submitting Piece...</span>
                        <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
