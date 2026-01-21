<div>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
            <div class="text-center md:text-left">
                <h2 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter">My Artworks Showcase</h2>
                <div class="h-1.5 w-20 bg-[#1ABC9C] mx-auto md:mx-0 mt-4 rounded-full"></div>
            </div>
            <a href="{{ route('artist.upload') }}" class="group flex items-center gap-3 px-8 py-4 bg-[#1ABC9C] text-white font-black rounded-2xl hover:bg-teal-600 transition-all shadow-xl shadow-teal-50/50 uppercase tracking-widest text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Upload New Masterpiece
            </a>
        </div>

        {{-- Alerts --}}
        @if (session()->has('message'))
        <div class="mb-8 bg-teal-50 border-2 border-[#1ABC9C] text-[#1ABC9C] px-8 py-4 rounded-[30px] font-black uppercase tracking-wider text-center shadow-sm" role="alert">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-8 bg-red-50 border-2 border-red-100 text-red-600 px-8 py-4 rounded-[30px] font-black uppercase tracking-wider text-center shadow-sm" role="alert">
            {{ session('error') }}
        </div>
        @endif

        {{-- Artworks Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($artworks as $artwork)
            <div wire:key="artwork-{{ $artwork->id }}" class="group bg-white dark:bg-gray-800 rounded-[40px] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 border border-gray-100 dark:border-gray-700 flex flex-col h-full cursor-pointer relative" wire:click="viewArtwork({{ $artwork->id }})">
                {{-- Floating Status Badge --}}
                <div class="absolute top-6 right-6 z-30 pointer-events-none">
                    <span class="px-5 py-2.5 text-[11px] font-black rounded-2xl shadow-2xl uppercase tracking-[0.15em] border-2 backdrop-blur-md
                        {{ $artwork->status === 'approved' ? 'bg-[#1ABC9C] text-white border-white/40' : 
                           ($artwork->status === 'sold' ? 'bg-[#2C3E50] text-white border-white/20' : 
                           ($artwork->status === 'rejected' ? 'bg-red-600 text-white border-white/30' : 'bg-amber-500 text-white border-white/40 shadow-amber-200/50')) }}">
                        {{ $artwork->status }}
                    </span>
                </div>

                <div class="relative h-72 overflow-hidden">
                    <img src="{{ $artwork->image_url }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/5 group-hover:bg-black/20 transition-colors"></div>
                </div>

                <div class="p-8 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tight truncate flex-1 pr-2" title="{{ $artwork->title }}">
                            {{ $artwork->title }}
                        </h3>
                    </div>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-6">Masterpiece Piece</p>

                    <div class="mt-auto flex items-center justify-between pt-6 border-t border-gray-50 dark:border-gray-700">
                        <p class="text-xl font-black text-[#1ABC9C]">LKR {{ number_format($artwork->price, 2) }}</p>

                        <div class="flex items-center gap-3" wire:ignore.self>
                            @if($artwork->status !== 'sold')
                            <a href="{{ route('artist.artworks.edit', $artwork->id) }}" wire:click.stop class="p-3 bg-teal-50 text-[#1ABC9C] rounded-2xl hover:bg-[#1ABC9C] hover:text-white transition-all shadow-sm" title="Edit Artwork">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <button wire:click.stop="deleteArtwork({{ $artwork->id }})" onclick="return confirm('Are you sure you want to delete this piece? This action is permanent.') || event.stopImmediatePropagation()" class="p-3 bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Delete Artwork">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            @else
                            <span class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-xl text-gray-400 text-[10px] font-black uppercase tracking-widest border-2 border-gray-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Sold Out
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-24 text-center bg-white dark:bg-gray-800 rounded-[50px] border-4 border-dashed border-gray-100 dark:border-gray-700">
                <div class="mx-auto h-24 w-24 bg-teal-50 rounded-full flex items-center justify-center mb-8 text-[#1ABC9C]">
                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter">{{ __('No artworks uploaded yet') }}</h3>
                <p class="text-gray-400 font-bold mt-4 uppercase tracking-widest text-sm">{{ __('Share your unique perspective with the gallery') }}</p>
                <a href="{{ route('artist.upload') }}" class="mt-10 inline-flex items-center px-12 py-5 bg-[#1ABC9C] text-white text-base font-black rounded-2xl hover:bg-teal-600 transition-all shadow-xl shadow-teal-100 uppercase tracking-widest">
                    {{ __('Begin Your Journey') }}
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-16">
            {{ $artworks->links('components.gallery-pagination') }}
        </div>
    </div>

    {{-- Detail Modal --}}
    @if($showModal && $selectedArtwork)
    <div class="fixed z-[60] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-[#2C3E50]/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border-4 border-teal-50">
                <div class="bg-white dark:bg-gray-800 px-8 py-10">
                    <div class="lg:flex lg:items-start lg:gap-10">
                        <div class="lg:w-1/2">
                            <div class="rounded-[30px] overflow-hidden shadow-inner bg-gray-50 border border-gray-100 relative">
<img src="{{ $selectedArtwork->image_url }}" class="w-full h-auto object-cover max-h-[500px]" alt="{{ $selectedArtwork->title }}">
                                {{-- Modal Status Badge --}}
                                <div class="absolute top-6 right-6">
                                    <span class="px-4 py-2 text-[10px] font-black rounded-full shadow-lg uppercase tracking-widest border-2 bg-white/90 text-[#2C3E50]">
                                        {{ $selectedArtwork->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 lg:mt-0 lg:w-1/2 flex flex-col h-full">
                            <div>
                                <h3 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter leading-tight">
                                    {{ $selectedArtwork->title }}
                                </h3>
                                <p class="text-2xl font-black text-[#1ABC9C] mt-4">LKR {{ number_format($selectedArtwork->price, 2) }}</p>

                                <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                                    <h4 class="text-xs font-black text-[#1ABC9C] uppercase tracking-[0.2em] mb-3">Masterpiece Story</h4>
                                    <p class="text-gray-600 dark:text-gray-300 text-base leading-relaxed font-medium">
                                        {{ $selectedArtwork->description }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-10 flex flex-col gap-4">
                                @if($selectedArtwork->status !== 'sold')
                                <div class="grid grid-cols-2 gap-4">
                                    <a href="{{ route('artist.artworks.edit', $selectedArtwork->id) }}" class="flex items-center justify-center px-6 py-4 rounded-2xl border-2 border-teal-50 text-base font-black text-[#1ABC9C] hover:bg-teal-50 transition-all uppercase tracking-wider">
                                        Edit Details
                                    </a>
                                    <button wire:click="deleteArtwork({{ $selectedArtwork->id }})" onclick="return confirm('Deeply delete this masterpiece?')" type="button" class="flex items-center justify-center px-6 py-4 rounded-2xl bg-red-50 text-red-600 border-2 border-red-100 text-base font-black hover:bg-red-600 hover:text-white transition-all uppercase tracking-wider">
                                        Delete Forever
                                    </button>
                                </div>
                                @else
                                <div class="p-6 bg-gray-50 rounded-2xl border-2 border-gray-100 text-center">
                                    <p class="text-gray-400 font-black uppercase tracking-widest text-xs">This piece has found its owner and is locked.</p>
                                </div>
                                @endif

                                <button wire:click="closeModal" type="button" class="w-full py-4 text-[#2C3E50] font-black uppercase tracking-widest hover:text-[#1ABC9C] transition-colors">
                                    Keep Browsing
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>