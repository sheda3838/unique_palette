<x-guest-layout>
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden py-24 px-4 sm:px-6 lg:px-8">
        {{-- Animated Background Elements --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-teal-100/30 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#2C3E50]/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        </div>

        <div class="relative z-10 w-full max-w-md animate-entrance">
            {{-- Logo Section --}}
            <div class="text-center mb-10">
                <a href="/" class="inline-flex items-center gap-4 group">
                    <div class="p-4 bg-white/80 backdrop-blur-md rounded-3xl shadow-xl shadow-teal-500/10 border border-white transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                        <img src="{{ asset('assets/logo.png') }}" class="h-10 w-auto" alt="Logo">
                    </div>
                </a>
                <h1 class="mt-8 text-4xl font-black text-[#2C3E50] uppercase tracking-tighter">
                    Password <span class="text-[#1ABC9C]">Recovery</span>
                </h1>
                <p class="mt-3 text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Restoring Vault Access</p>
            </div>

            {{-- Main Recovery Card --}}
            <div class="glass-card shadow-[0_32px_64px_-16px_rgba(26,188,156,0.15)] border border-white/40 rounded-[48px] overflow-hidden">
                <div class="p-10 sm:p-12">

                    <div class="mb-8 text-center px-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                            {{ __('Forgot your security key? Provide your registered identity below, and we will dispatch a recovery link.') }}
                        </p>
                    </div>

                    @if (session('status'))
                    <div class="mb-8 p-5 bg-teal-50/50 backdrop-blur-sm border border-teal-100 rounded-3xl text-xs font-black text-[#1ABC9C] flex items-center gap-3 uppercase tracking-widest">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('status') }}
                    </div>
                    @endif

                    <x-validation-errors class="mb-6" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
                        @csrf

                        <div class="space-y-2 group">
                            <label for="email" class="inline-block text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.25em] ml-2">{{ __('Registered Email') }}</label>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                class="block w-full px-8 py-5 bg-white/50 border-2 border-transparent focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-[30px] font-bold text-[#2C3E50] placeholder-gray-300 transition-all duration-300 outline-none"
                                placeholder="Enter identity..." />
                        </div>

                        <button type="submit" class="group relative w-full flex justify-center py-5 px-6 border border-transparent rounded-[30px] shadow-2xl shadow-teal-500/20 text-xs font-black text-white bg-[#1ABC9C] hover:bg-[#2C3E50] focus:outline-none transition-all duration-500 uppercase tracking-[0.3em]">
                            <span class="relative z-10 transition-transform duration-300 group-hover:scale-110">Dispatch Link</span>
                            <div class="absolute inset-0 bg-[#2C3E50] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500 rounded-[30px]"></div>
                        </button>
                    </form>

                    <div class="mt-10 pt-8 border-t border-teal-50/50 text-center">
                        <a href="{{ route('login') }}" class="text-[10px] font-black text-[#1ABC9C] hover:text-[#2C3E50] uppercase tracking-[0.3em] transition-colors">
                            {{ __('Return to Vault Access') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>