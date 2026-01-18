<footer class="bg-white dark:bg-gray-800 border-2 border-[#1ABC9C]/20 rounded-3xl mx-4 sm:mx-8 lg:mx-auto max-w-7xl mb-12 overflow-hidden shadow-2xl shadow-teal-50">
    <div class="px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-center">

            <!-- Left: Logo & Socials -->
            <div class="flex flex-col items-center lg:items-start space-y-8">
                <a href="{{ url('/') }}" class="flex flex-col items-center lg:items-start gap-2 group">
                    <img src="{{ asset('assets/logo.png') }}" alt="Unique Palette" class="h-24 w-auto drop-shadow-md">
                    <span class="font-black text-[#2C3E50] text-lg tracking-tighter uppercase">Unique Palette</span>
                </a>

                <div class="flex items-center gap-4">
                    <a href="#" class="hover:scale-110 transition-transform">
                        <img src="{{ asset('assets/whatsapp.png') }}" alt="WhatsApp" class="h-10 w-10">
                    </a>
                    <a href="#" class="hover:scale-110 transition-transform">
                        <img src="{{ asset('assets/instagram.png') }}" alt="Instagram" class="h-10 w-10">
                    </a>
                    <a href="#" class="hover:scale-110 transition-transform">
                        <img src="{{ asset('assets/communication.png') }}" alt="Facebook" class="h-10 w-10">
                    </a>
                    <a href="#" class="hover:scale-110 transition-transform">
                        <img src="{{ asset('assets/twitter.png') }}" alt="Twitter" class="h-10 w-10">
                    </a>
                </div>
            </div>

            <!-- Center: Copyright -->
            <div class="flex flex-col items-center justify-center text-center px-4">
                <div class="flex items-center justify-center gap-4 mb-2">
                    <div class="h-10 w-10 border-4 border-[#2C3E50] rounded-full flex items-center justify-center">
                        <span class="text-xl font-black text-[#2C3E50]">C</span>
                    </div>
                    <p class="text-base sm:text-lg font-bold text-[#2C3E50] tracking-tight">
                        {{ date('Y') }} UniquePalette. All rights reserved.
                    </p>
                </div>
            </div>

            <!-- Right: Reach Us -->
            <div class="flex flex-col items-center lg:items-end space-y-6">
                <h4 class="text-xl font-black text-[#1ABC9C] uppercase tracking-wider mb-2">Reach Us:</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-end gap-5 group">
                        <span class="text-base font-bold text-[#2C3E50] group-hover:text-[#1ABC9C] transition-colors">+94 77 123 4567</span>
                        <div class="bg-teal-50 p-2.5 rounded-xl group-hover:bg-[#1ABC9C] transition-colors">
                            <img src="{{ asset('assets/phone-call.png') }}" alt="Phone" class="h-6 w-6 group-hover:invert transition-all">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-5 group">
                        <span class="text-base font-bold text-[#2C3E50] group-hover:text-[#1ABC9C] transition-colors">Colombo, Sri Lanka</span>
                        <div class="bg-teal-50 p-2.5 rounded-xl group-hover:bg-[#1ABC9C] transition-colors">
                            <img src="{{ asset('assets/pin.png') }}" alt="Location" class="h-6 w-6 group-hover:invert transition-all">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-5 group">
                        <span class="text-base font-bold text-[#2C3E50] group-hover:text-[#1ABC9C] transition-colors lowercase">support@uniquepalette.com</span>
                        <div class="bg-teal-50 p-2.5 rounded-xl group-hover:bg-[#1ABC9C] transition-colors">
                            <img src="{{ asset('assets/email.png') }}" alt="Email" class="h-6 w-6 group-hover:invert transition-all">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>