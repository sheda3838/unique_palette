<div class="py-16 bg-gray-50/50 dark:bg-gray-900/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter">{{ __('Checkout') }}</h2>
            <div class="h-1.5 w-16 bg-[#1ABC9C] mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Order Summary -->
            <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-[40px] shadow-xl shadow-teal-50/50 border-2 border-teal-50 dark:border-gray-700 p-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-10 w-10 bg-teal-50 rounded-xl flex items-center justify-center text-[#1ABC9C]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 118 0m-4 5v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm2-2V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tight">Order Summary</h3>
                </div>

                <ul class="divide-y divide-gray-100 dark:divide-gray-700 mb-8">
                    @foreach($cartItems as $item)
                    <li class="py-6 flex justify-between items-center group">
                        <div class="flex items-center gap-6">
                            @if($item['artwork'])
                            <div class="h-20 w-20 rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex-shrink-0">
                                <img class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{ $item['artwork']['image_url'] }}" alt="{{ $item['artwork']['title'] }}">
                            </div>
                            @endif
                            <div>
                                <p class="text-[#2C3E50] dark:text-white font-black text-lg uppercase tracking-tight">{{ $item['artwork']['title'] ?? 'Unknown' }}</p>
                                <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mt-1">by {{ $item['artwork']['user']['name'] ?? 'Unknown Artist' }}</p>
                            </div>
                        </div>
                        <span class="text-lg font-black text-[#1ABC9C]">LKR {{ number_format($item['artwork']['price'] ?? 0, 2) }}</span>
                    </li>
                    @endforeach
                </ul>

                <div class="pt-8 border-t-2 border-dashed border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <span class="text-base font-bold text-gray-400 uppercase tracking-[0.2em]">Total Amount</span>
                    <span class="text-3xl font-black text-[#1ABC9C]">LKR {{ number_format($total, 2) }}</span>
                </div>
            </div>

            <!-- Shipping & Payment Info -->
            <div class="lg:col-span-5 space-y-8">
                {{-- Shipping Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-xl shadow-teal-50/50 border-2 border-teal-50 dark:border-gray-700 p-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-10 w-10 bg-teal-50 rounded-xl flex items-center justify-center text-[#1ABC9C]">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tight">Shipping To</h3>
                    </div>

                    @if($userAddress)
                    <div class="p-6 bg-teal-50/30 rounded-3xl border-2 border-teal-50 dark:bg-gray-700/50 dark:border-gray-600">
                        <p class="text-[#2C3E50] dark:text-white font-black uppercase tracking-tight text-lg mb-2">{{ Auth::user()->name }}</p>
                        <div class="text-gray-500 dark:text-gray-400 font-bold space-y-1">
                            <p>{{ $userAddress->street }}</p>
                            <p>{{ $userAddress->city }}, {{ $userAddress->province }}</p>
                            <p class="text-[#1ABC9C] tracking-widest">{{ $userAddress->postal_code }}</p>
                        </div>
                    </div>
                    @else
                    <div class="p-6 bg-amber-50 rounded-3xl border-2 border-amber-100 flex flex-col items-center text-center">
                        <p class="text-amber-800 font-bold mb-4">You haven't added a shipping address yet.</p>
                        <a href="{{ route('profile.show') }}" class="px-6 py-3 bg-white text-amber-600 font-black rounded-2xl border-2 border-amber-100 hover:bg-amber-100 transition-all uppercase text-xs tracking-widest">Setup Address</a>
                    </div>
                    @endif
                </div>

                {{-- Payment & Action Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-xl shadow-teal-50/50 border-2 border-teal-50 dark:border-gray-700 p-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-10 w-10 bg-teal-50 rounded-xl flex items-center justify-center text-[#1ABC9C]">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tight">Payment</h3>
                    </div>

                    <div class="flex items-center p-5 rounded-2xl border-2 border-[#1ABC9C] bg-teal-50/20 mb-8 transition-all">
                        <input id="payhere" name="payment_method" type="radio" checked class="h-5 w-5 text-[#1ABC9C] focus:ring-[#1ABC9C] border-gray-300">
                        <label for="payhere" class="ml-4 flex items-center gap-3 cursor-pointer">
                            <span class="font-black text-[#2C3E50] dark:text-white uppercase tracking-widest text-sm">PayHere Secure</span>
                            <span class="px-2 py-0.5 bg-[#1ABC9C] text-white text-[10px] font-black rounded-md uppercase">VISA/MASTER</span>
                        </label>
                    </div>

                    @if (session()->has('error'))
                    <div class="mb-6 bg-red-50 border-2 border-red-100 text-red-600 p-4 rounded-2xl font-bold text-sm text-center">
                        {{ session('error') }}
                    </div>
                    @endif

                    <button wire:click="placeOrder" wire:loading.attr="disabled" class="w-full flex items-center justify-center py-5 bg-[#1ABC9C] text-white text-base font-black rounded-2xl hover:bg-teal-600 transition-all shadow-xl shadow-teal-100 uppercase tracking-[0.2em] group disabled:opacity-50">
                        <span wire:loading.remove class="flex items-center gap-3">
                            Confirm & Pay
                            <svg class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                        <span wire:loading class="flex items-center gap-3">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                    <p class="mt-6 text-[11px] text-gray-400 font-bold text-center uppercase tracking-widest leading-relaxed">Secured by industry standard encryption. You will be redirected to PayHere.</p>
                </div>
            </div>
        </div>
    </div>
</div>