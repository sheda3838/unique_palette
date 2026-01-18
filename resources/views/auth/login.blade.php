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
                    Creator <span class="text-[#1ABC9C]">Vault</span>
                </h1>
                <p class="mt-3 text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Accessing Masterpiece Terminal</p>
            </div>

            {{-- Main Login Card --}}
            <div class="glass-card shadow-[0_32px_64px_-16px_rgba(26,188,156,0.15)] border border-white/40 rounded-[48px] overflow-hidden">
                <div class="p-10 sm:p-12">
                    <x-validation-errors class="mb-6 animate-bounce" />

                    @session('status')
                    <div class="mb-6 p-5 bg-teal-50/50 backdrop-blur-sm border border-teal-100 rounded-3xl text-sm font-bold text-[#1ABC9C] flex items-center gap-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $value }}
                    </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-7">
                        @csrf

                        <div class="space-y-2 group">
                            <label for="email" class="inline-block text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.25em] ml-2">{{ __('Identity') }}</label>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                class="block w-full px-8 py-5 bg-white/50 border-2 border-transparent focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-[30px] font-bold text-[#2C3E50] placeholder-gray-300 transition-all duration-300 outline-none"
                                placeholder="Enter encrypted email..." />
                        </div>

                        <div class="space-y-2 group">
                            <div class="flex justify-between items-center px-2">
                                <label for="password" class="text-[10px] font-black text-[#1ABC9C] uppercase tracking-[0.25em]">{{ __('Access Key') }}</label>
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-black text-gray-400 hover:text-[#2C3E50] uppercase tracking-widest transition-colors">
                                    {{ __('Override?') }}
                                </a>
                                @endif
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="block w-full px-8 py-5 bg-white/50 border-2 border-transparent focus:border-[#1ABC9C] focus:bg-white focus:ring-4 focus:ring-teal-500/5 text-base rounded-[30px] font-bold text-[#2C3E50] placeholder-gray-300 transition-all duration-300 outline-none"
                                placeholder="••••••••" />
                        </div>

                        <div class="flex items-center px-2">
                            <label for="remember_me" class="relative flex items-center cursor-pointer group">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="peer sr-only">
                                <div class="w-6 min-w-6 h-6 bg-white/80 border-2 border-teal-50 rounded-lg peer-checked:bg-[#1ABC9C] peer-checked:border-[#1ABC9C] transition-all duration-300"></div>
                                <svg class="absolute w-4 h-4 text-white opacity-0 peer-checked:opacity-100 left-1 transition-opacity pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-4 text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-gray-600 transition-colors">
                                    {{ __('Keep session active') }}
                                </span>
                            </label>
                        </div>

                        <button type="submit" class="group relative w-full flex justify-center py-5 px-6 border border-transparent rounded-[30px] shadow-2xl shadow-teal-500/20 text-xs font-black text-white bg-[#1ABC9C] hover:bg-[#2C3E50] focus:outline-none transition-all duration-500 uppercase tracking-[0.3em]">
                            <span class="relative z-10 transition-transform duration-300 group-hover:scale-110">Unlock Portal</span>
                            <div class="absolute inset-0 bg-[#2C3E50] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500 rounded-[30px]"></div>
                        </button>
                    </form>

                    {{-- Social Login --}}
                    <div class="mt-12 pt-10 border-t border-teal-50/50">
                        <div class="text-center mb-8">
                            <span class="px-6 py-2 bg-gray-50/50 rounded-full text-[9px] font-black text-gray-400 uppercase tracking-[0.3em]">Rapid Credentials</span>
                        </div>

                        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-4 px-8 py-5 bg-white border-2 border-teal-50 rounded-[30px] text-[11px] font-black text-[#2C3E50] hover:bg-teal-50/30 hover:border-[#1ABC9C]/30 transition-all duration-500 uppercase tracking-widest group">
                            <svg class="h-5 w-5 transition-transform duration-500 group-hover:scale-125 group-hover:rotate-12" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z" />
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Connect Google
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                    New to Unique Palette?
                    <a href="{{ route('register') }}" class="ml-2 text-[#1ABC9C] hover:text-[#2C3E50] border-b-2 border-teal-100 hover:border-[#2C3E50] transition-all duration-300 pb-1">
                        Get Started
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>