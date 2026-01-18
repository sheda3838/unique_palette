<div class="min-h-screen bg-gray-50/50 flex flex-col justify-center py-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-teal-100/30 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#2C3E50]/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative z-10 w-full max-w-xl mx-auto animate-entrance">
        {{-- Header Section --}}
        <div class="text-center mb-8">
            <h2 class="text-xs font-black text-[#1ABC9C] uppercase tracking-[0.4em] mb-4">Onboarding Sequence</h2>
            <h1 class="text-4xl font-black text-[#2C3E50] uppercase tracking-tighter">
                Complete Your <span class="text-[#1ABC9C]">Profile</span>
            </h1>
        </div>

        {{-- Progress Bar (Requirement 5: UX Enhancements) --}}
        <div class="mb-12 px-4">
            <div class="flex justify-between mb-4">
                <span class="text-[10px] font-black uppercase tracking-widest {{ $step >= 0 ? 'text-[#1ABC9C]' : 'text-gray-300' }}">Role</span>
                <span class="text-[10px] font-black uppercase tracking-widest {{ $step >= 1 ? 'text-[#1ABC9C]' : 'text-gray-300' }}">Location</span>
                @if(auth()->user()->role === 'artist' || $step === 0)
                <span class="text-[10px] font-black uppercase tracking-widest {{ $step >= 2 ? 'text-[#1ABC9C]' : 'text-gray-300' }}">Financials</span>
                @endif
            </div>
            <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-[#1ABC9C] transition-all duration-700 ease-out" style="width: {{ auth()->user()->role === 'artist' ? ($step / 2 * 100) : ($step / 1 * 100) }}%"></div>
            </div>
        </div>

        <div class="glass-card shadow-[0_32px_64px_-16px_rgba(26,188,156,0.15)] border border-white/40 rounded-[48px] overflow-hidden">
            <div class="p-10 sm:p-14">

                {{-- Role Selection (Step 0) --}}
                @if($step === 0)
                <div class="space-y-10" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="text-center">
                        <p class="text-gray-400 font-bold text-sm uppercase tracking-widest leading-relaxed">
                            How do you intend to interact with the world's most unique masterpieces?
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <button wire:click="selectRole('buyer')" class="group relative flex items-center gap-6 p-8 bg-white border-2 border-teal-50 rounded-[35px] hover:border-[#1ABC9C] hover:bg-teal-50/20 transition-all duration-500 text-left">
                            <div class="p-4 bg-teal-50 rounded-2xl group-hover:bg-[#1ABC9C] group-hover:text-white transition-colors duration-500 text-[#1ABC9C]">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-[#2C3E50] uppercase tracking-tight">Art Enthusiast</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Collect and appreciate unique artworks</p>
                            </div>
                            <div class="absolute right-8 opacity-0 group-hover:opacity-100 transition-opacity translate-x-4 group-hover:translate-x-0 duration-500 text-[#1ABC9C]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </button>

                        <button wire:click="selectRole('artist')" class="group relative flex items-center gap-6 p-8 bg-white border-2 border-teal-50 rounded-[35px] hover:border-[#2C3E50] hover:bg-gray-50/50 transition-all duration-500 text-left">
                            <div class="p-4 bg-gray-50 rounded-2xl group-hover:bg-[#2C3E50] group-hover:text-white transition-colors duration-500 text-[#2C3E50]">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-[#2C3E50] uppercase tracking-tight">Master Creator</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Share and showcase your masterpieces</p>
                            </div>
                            <div class="absolute right-8 opacity-0 group-hover:opacity-100 transition-opacity translate-x-4 group-hover:translate-x-0 duration-500 text-[#2C3E50]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
                @endif

                {{-- Address Information (Step 1) --}}
                @if($step === 1)
                <div class="space-y-10" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-teal-50 rounded-xl flex items-center justify-center text-[#1ABC9C] font-black">1</div>
                        <h2 class="text-2xl font-black text-[#2C3E50] uppercase tracking-tighter">Location Identity</h2>
                    </div>

                    <form wire:submit.prevent="saveAddress" class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">Province</label>
                                <input type="text" wire:model.live="province" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="province" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">City</label>
                                <input type="text" wire:model.live="city" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="city" />
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">Street Address</label>
                                <input type="text" wire:model.live="street" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="street" />
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">Postal Code</label>
                                <input type="text" wire:model.live="postal_code" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="postal_code" />
                            </div>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center py-5 px-6 rounded-[30px] shadow-2xl shadow-teal-500/20 text-xs font-black text-white bg-[#1ABC9C] hover:bg-[#2C3E50] transition-all duration-500 uppercase tracking-[0.3em]">
                            <span wire:loading.remove>{{ auth()->user()->role === 'artist' ? 'Establish Banking' : 'Finish Initialization' }}</span>
                            <div wire:loading class="flex items-center gap-3">
                                <div class="brush-spinner"></div>
                                <span>Developing...</span>
                            </div>
                        </button>
                    </form>
                </div>
                @endif

                {{-- Bank Details (Step 2 - Artist Only) --}}
                @if($step === 2)
                <div class="space-y-10" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-[#2C3E50] rounded-xl flex items-center justify-center text-white font-black">2</div>
                        <h2 class="text-2xl font-black text-[#2C3E50] uppercase tracking-tighter">Financial Channel</h2>
                    </div>

                    <form wire:submit.prevent="saveBank" class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">Bank Institution</label>
                                <input type="text" wire:model.live="bank_name" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="bank_name" />
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">Branch Office</label>
                                <input type="text" wire:model.live="branch" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="branch" />
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">Account Holder Name</label>
                                <input type="text" wire:model.live="account_name" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="account_name" />
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.2em] ml-2">Account Sequence Number</label>
                                <input type="text" wire:model.live="account_number" class="block w-full px-6 py-4 bg-white/50 border-2 border-teal-50 focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-2xl font-bold text-[#2C3E50] transition-all outline-none" required />
                                <x-input-error for="account_number" />
                            </div>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center py-5 px-6 rounded-[30px] shadow-2xl shadow-teal-500/20 text-xs font-black text-white bg-[#1ABC9C] hover:bg-[#2C3E50] transition-all duration-500 uppercase tracking-[0.3em]">
                            <span wire:loading.remove>Finalize Initialization</span>
                            <div wire:loading class="flex items-center gap-3">
                                <div class="brush-spinner"></div>
                                <span>Varnishing...</span>
                            </div>
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>