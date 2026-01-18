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
                    Verify <span class="text-[#1ABC9C]">Identity</span>
                </h1>
                <p class="mt-3 text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Guardian Protocol Active</p>
            </div>

            {{-- Main Verification Card --}}
            <div class="glass-card shadow-[0_32px_64px_-16px_rgba(26,188,156,0.15)] border border-white/40 rounded-[48px] overflow-hidden">
                <div class="p-10 sm:p-12 text-center text-gray-600">

                    <div class="flex justify-center mb-8">
                        <div class="h-20 w-20 bg-teal-50 rounded-full flex items-center justify-center text-[#1ABC9C] shadow-inner">
                            <svg class="h-10 w-10 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <p class="text-sm font-bold leading-relaxed mb-8">
                        {{ __('We\'ve sent a digital signature to your inbox. Please click the link within to authorize your access to the Unique Palette.') }}
                    </p>

                    @if (session('status') == 'verification-link-sent')
                    <div class="mb-8 p-5 bg-teal-50/50 backdrop-blur-sm border border-teal-100 rounded-3xl text-xs font-black text-[#1ABC9C] flex items-center gap-3 uppercase tracking-widest animate-pulse">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Fresh key dispatched successfully.') }}
                    </div>
                    @endif

                    <div class="space-y-6">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="group relative w-full flex justify-center py-5 px-6 border border-transparent rounded-[30px] shadow-2xl shadow-teal-500/20 text-xs font-black text-white bg-[#1ABC9C] hover:bg-[#2C3E50] focus:outline-none transition-all duration-500 uppercase tracking-[0.3em]">
                                <span class="relative z-10 transition-transform duration-300 group-hover:scale-110">Resend Link</span>
                                <div class="absolute inset-0 bg-[#2C3E50] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500 rounded-[30px]"></div>
                            </button>
                        </form>

                        <div class="flex flex-col gap-4 mt-8 pt-8 border-t border-teal-50/50">
                            <a href="{{ route('profile.show') }}" class="text-[10px] font-black text-[#1ABC9C] hover:text-[#2C3E50] uppercase tracking-[0.3em] transition-colors">
                                {{ __('Update Profile Info') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-[10px] font-black text-red-400 hover:text-red-600 uppercase tracking-[0.3em] transition-colors">
                                    {{ __('Terminate Session') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                    Need assistance?
                    <a href="mailto:support@uniquepalette.com" class="ml-2 text-[#1ABC9C] hover:text-[#2C3E50] border-b-2 border-teal-100 hover:border-[#2C3E50] transition-all duration-300 pb-1">
                        Contact Support
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>