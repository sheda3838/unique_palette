<div>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter">Explore Our Collection</h2>
            <div class="h-1.5 w-20 bg-[#1ABC9C] mx-auto mt-4 rounded-full"></div>
        </div>

        {{-- Search Bar & Cart --}}
        <div class="mb-10 flex flex-col sm:flex-row items-center justify-center gap-6">
            <div class="relative rounded-2xl shadow-sm max-w-xl w-full group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-[#1ABC9C] group-focus-within:text-teal-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-14 pr-4 py-4 border-2 border-teal-50 focus:border-[#1ABC9C] focus:ring-0 text-base rounded-2xl font-bold text-[#2C3E50] placeholder-gray-400 dark:bg-gray-800 dark:text-white dark:border-gray-700 transition-all shadow-lg shadow-teal-50/50" placeholder="Search unique pieces...">
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-4 flex items-center">
                    <div class="paint-dots">
                        <div class="paint-dot"></div>
                        <div class="paint-dot"></div>
                        <div class="paint-dot"></div>
                    </div>
                </div>
            </div>

            <button wire:click="$set('showCartModal', true)" class="relative p-4 bg-white dark:bg-gray-800 rounded-2xl border-2 border-teal-50 hover:border-[#1ABC9C] transition-all shadow-lg shadow-teal-50/50 group">
                <span class="sr-only">View Cart</span>
                <svg class="h-7 w-7 text-[#2C3E50] dark:text-gray-300 group-hover:text-[#1ABC9C] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 118 0m-4 5v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm2-2V7a4 4 0 00-8 0v4h8z" />
                </svg>
                @if(count($this->cart) > 0)
                <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-3 py-1.5 text-xs font-black leading-none text-white transform bg-[#1ABC9C] rounded-full shadow-lg border-2 border-white">{{ $this->cart->sum('quantity') }}</span>
                @endif
            </button>
        </div>

        @if (session()->has('message'))
        <div class="mb-8 bg-teal-50 border-2 border-[#1ABC9C] text-[#1ABC9C] px-6 py-4 rounded-2xl font-black uppercase tracking-wider text-center" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
        @endif

        {{-- Artworks Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($artworks as $artwork)
            <div wire:key="artwork-{{ $artwork->id }}" class="group bg-white dark:bg-gray-800 rounded-[40px] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 border border-gray-100 dark:border-gray-700 flex flex-col h-full cursor-pointer relative" wire:click="viewArtwork({{ $artwork->id }})">
                <div class="relative h-72 overflow-hidden">
                    <img src="{{ $artwork->image_url }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/5 group-hover:bg-black/20 transition-colors"></div>

                    @if($artwork->status === 'sold')
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-[2px]">
                        <span class="px-6 py-2 bg-white/90 text-[#2C3E50] font-black uppercase tracking-widest text-sm rounded-full">Sold Out</span>
                    </div>
                    @endif
                </div>

                <div class="p-8 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tight truncate flex-1 pr-2" title="{{ $artwork->title }}">
                            {{ $artwork->title }}
                        </h3>
                    </div>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-6">by {{ $artwork->user->name }}</p>

                    <div class="mt-auto flex items-center justify-between pt-6 border-t border-gray-50 dark:border-gray-700">
                        <p class="text-xl font-black text-[#1ABC9C]">LKR {{ number_format($artwork->price, 2) }}</p>

                        <div class="flex items-center gap-2" wire:ignore.self>
                            @if(auth()->check() && auth()->user()->isArtist() && auth()->id() === $artwork->user_id)
                            <div class="flex space-x-2">
                                <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" wire:click.stop>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors" wire:click.stop="deleteArtwork({{ $artwork->id }})" onclick="return confirm('Are you sure?') || event.stopImmediatePropagation()">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            @elseif($artwork->status !== 'sold')
                            @php $inCart = auth()->check() && $this->cart->contains('artwork_id', $artwork->id); @endphp
                            @if($inCart)
                            <button wire:click.stop="removeFromCart({{ $artwork->id }})" class="p-3 bg-red-100 text-red-600 rounded-2xl hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Remove from Cart">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" />
                                </svg>
                            </button>
                            @else
                            <button wire:click.stop="addToCart({{ $artwork->id }})" class="p-3 bg-teal-50 text-[#1ABC9C] rounded-2xl hover:bg-[#1ABC9C] hover:text-white transition-all shadow-sm" title="Add to Cart">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-16">
            {{ $artworks->links('components.gallery-pagination') }}
        </div>
    </div>


    {{-- Detail Modal --}}
    @if($showModal && $selectedArtwork)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-navy-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border-4 border-teal-50">
                <div class="bg-white dark:bg-gray-800 px-8 py-10">
                    <div class="lg:flex lg:items-start lg:gap-10">
                        <div class="lg:w-1/2">
                            <div class="rounded-[30px] overflow-hidden shadow-inner bg-gray-50 border border-gray-100">
                                <img src="{{ $selectedArtwork->image_url }}" class="w-full h-auto object-cover max-h-[500px]" alt="{{ $selectedArtwork->title }}">
                            </div>
                        </div>
                        <div class="mt-6 lg:mt-0 lg:w-1/2 flex flex-col h-full">
                            <div>
                                <h3 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter leading-tight" id="modal-title">
                                    {{ $selectedArtwork->title }}
                                </h3>
                                <p class="text-2xl font-black text-[#1ABC9C] mt-4">LKR {{ number_format($selectedArtwork->price, 2) }}</p>
                                <div class="mt-4 flex items-center gap-3">
                                    <div class="h-10 w-10 bg-teal-50 rounded-full flex items-center justify-center text-[#1ABC9C] font-black">
                                        {{ substr($selectedArtwork->user->name, 0, 1) }}
                                    </div>
                                    <p class="text-base text-gray-500 font-bold uppercase tracking-wide">by <span class="text-[#2C3E50] dark:text-gray-200">{{ $selectedArtwork->user->name }}</span></p>
                                </div>

                                <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                                    <h4 class="text-xs font-black text-[#1ABC9C] uppercase tracking-[0.2em] mb-3">About this piece</h4>
                                    <p class="text-gray-600 dark:text-gray-300 text-base leading-relaxed font-medium">
                                        {{ $selectedArtwork->description }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-10 flex flex-col gap-4">
                                @if(auth()->check() && auth()->user()->isArtist() && auth()->id() === $selectedArtwork->user_id)
                                <div class="grid grid-cols-2 gap-4">
                                    <button type="button" class="flex items-center justify-center px-6 py-4 rounded-2xl border-2 border-gray-200 text-base font-black text-[#2C3E50] hover:bg-gray-50 transition-all uppercase tracking-wider">
                                        Edit
                                    </button>
                                    <button wire:click="deleteArtwork({{ $selectedArtwork->id }})" onclick="return confirm('Are you sure?')" type="button" class="flex items-center justify-center px-6 py-4 rounded-2xl bg-red-50 text-red-600 border-2 border-red-100 text-base font-black hover:bg-red-600 hover:text-white transition-all uppercase tracking-wider">
                                        Delete
                                    </button>
                                </div>
                                @else
                                @if($selectedArtwork->status !== 'sold')
                                <button wire:click="addToCart({{ $selectedArtwork->id }})" wire:loading.attr="disabled" type="button" class="w-full flex items-center justify-center px-10 py-5 bg-[#1ABC9C] text-white text-lg font-black rounded-2xl hover:bg-teal-600 transition-all shadow-xl shadow-teal-100 uppercase tracking-widest gap-4">
                                    <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
                                    <div wire:loading wire:target="addToCart" class="flex items-center gap-3">
                                        <div class="brush-spinner"></div>
                                        <span>Adding to Collection...</span>
                                    </div>
                                </button>
                                @else
                                <button disabled class="w-full flex items-center justify-center px-10 py-5 bg-gray-100 text-gray-400 text-lg font-black rounded-2xl cursor-not-allowed uppercase tracking-widest">
                                    Sold Out
                                </button>
                                @endif
                                @endif

                                <button wire:click="closeModal" type="button" class="w-full py-4 text-[#2C3E50] font-black uppercase tracking-widest hover:text-[#1ABC9C] transition-colors">
                                    Go Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Cart Modal --}}
    @if($showCartModal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-navy-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeCartModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border-4 border-teal-50">
                <div class="bg-white dark:bg-gray-800 px-8 py-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="h-14 w-14 bg-teal-50 rounded-2xl flex items-center justify-center text-[#1ABC9C]">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 118 0m-4 5v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm2-2V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter" id="modal-title">
                            Shopping Cart
                        </h3>
                    </div>

                    <div class="mt-4">
                        @if(count($this->cart) > 0)
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto pr-2">
                            @foreach($this->cart as $item)
                            <li class="py-6 flex items-center gap-6 group">
                                <div class="h-24 w-24 rounded-2xl overflow-hidden border border-gray-100 flex-shrink-0 shadow-sm">
                                    <img src="{{ $item->artwork->image_url }}" alt="{{ $item->artwork->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                </div>
                                <div class="flex-1 flex flex-col">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-black text-[#2C3E50] dark:text-white uppercase tracking-tight">{{ $item->artwork->title }}</h3>
                                            <p class="text-gray-500 font-bold text-xs uppercase tracking-wide">by {{ $item->artwork->user->name }}</p>
                                        </div>
                                        <p class="text-lg font-black text-[#1ABC9C]">LKR {{ number_format($item->artwork->price, 2) }}</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center bg-gray-50 rounded-lg px-3 py-1">
                                            <span class="text-xs font-bold text-gray-500">Qty: {{ $item->quantity }}</span>
                                        </div>
                                        <button type="button" wire:click="removeFromCart({{ $item->artwork_id }})" class="text-xs font-black text-red-500 hover:text-red-700 uppercase tracking-widest transition-colors">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>

                        <div class="mt-8 pt-8 border-t-2 border-dashed border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-8">
                                <p class="text-lg font-bold text-gray-500 uppercase tracking-widest">Total Amount</p>
                                <p class="text-3xl font-black text-[#1ABC9C]">LKR {{ number_format($this->cartTotal, 2) }}</p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('checkout') }}" class="flex-1 flex items-center justify-center px-8 py-5 bg-[#1ABC9C] text-white text-base font-black rounded-2xl hover:bg-teal-600 transition-all shadow-xl shadow-teal-100 uppercase tracking-widest">
                                    Checkout Now
                                </a>
                                <button wire:click="clearCart" type="button" class="px-8 py-5 border-2 border-red-100 text-red-500 font-black rounded-2xl hover:bg-red-50 transition-all uppercase tracking-widest">
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="py-12 text-center">
                            <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 118 0m-4 5v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm2-2V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-400">Your cart is empty</p>
                            <button wire:click="closeCartModal" class="mt-6 text-[#1ABC9C] font-black uppercase tracking-widest hover:text-teal-700 transition-colors">Start Shopping</button>
                        </div>
                        @endif
                    </div>

                    <button wire:click="closeCartModal" type="button" class="w-full mt-6 py-4 text-gray-400 font-bold uppercase tracking-widest hover:text-[#2C3E50] transition-colors">
                        Continue Browsing
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<p>{{ $artwork->image_url }}</p>


</div>