<x-public-layout>
    <div class="bg-white py-16 sm:py-24" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-opacity duration-700" :class="loaded ? 'opacity-100' : 'opacity-0'">
            <!-- Hero Header -->
            <div class="text-center mb-20 transform transition-all duration-700 delay-100" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                <h1 class="text-4xl font-black text-[#2C3E50] sm:text-5xl md:text-6xl uppercase tracking-tighter">
                    {{ __('About Unique Palette') }}
                </h1>
                <div class="h-1.5 w-24 bg-[#1ABC9C] mx-auto mt-6 rounded-full"></div>
                <p class="mt-8 text-xl text-gray-500 max-w-3xl mx-auto font-medium">
                </p>
            </div>

            <!-- Section 1: Who We Are -->
            <div class="relative flex flex-col md:flex-row items-center gap-16 mb-32 transform transition-all duration-700 delay-200" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                <div class="flex-1 order-2 md:order-1">
                    <h2 class="text-3xl font-black text-[#2C3E50] mb-6 uppercase tracking-tight">{{ __('Who We Are') }}</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6 font-medium">
                        {{ __("Unique Palette is more than just a marketplace; it's a community born from a passion for authentic creativity. We provide a platform where artists from all walks of life can showcase their masterpieces to a global audience.") }}
                    </p>
                    <p class="text-lg text-gray-600 leading-relaxed font-medium">
                        {{ __("Based on the belief that everyone deserves to own a piece of art that speaks to them, we've built a space that celebrates diversity, craftsmanship, and the stories behind every brushstroke.") }}
                    </p>
                </div>
                <div class="flex-1 order-1 md:order-2 relative">
                    <div class="absolute -top-6 -right-6 w-full h-full border-4 border-[#1ABC9C] rounded-[40px] -z-10"></div>
                    <img src="{{ asset('assets/about/about1.jpg') }}" alt="Who We Are" class="rounded-[40px] shadow-2xl w-full h-[400px] object-cover border-8 border-white transform hover:rotate-1 transition-transform duration-500">
                </div>
            </div>

            <!-- Section 2: Our Mission -->
            <div class="relative flex flex-col md:flex-row items-center gap-16 mb-32 transform transition-all duration-700 delay-300" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                <div class="flex-1 relative">
                    <div class="absolute -bottom-6 -left-6 w-full h-full border-4 border-[#2C3E50] rounded-[40px] -z-10"></div>
                    <img src="{{ asset('assets/about/about2.jpg') }}" alt="Our Mission" class="rounded-[40px] shadow-2xl w-full h-[400px] object-cover border-8 border-white transform hover:-rotate-1 transition-transform duration-500">
                </div>
                <div class="flex-1">
                    <h2 class="text-3xl font-black text-[#2C3E50] mb-6 uppercase tracking-tight">{{ __('Our Mission') }}</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6 font-medium">
                        {{ __('Our mission is to empower artists by providing them with the tools and exposure they need to thrive in the modern art world. We strive to make art accessible, transparent, and rewarding for both creators and collectors.') }}
                    </p>
                    <div class="flex items-start gap-4 mb-4">
                        <div class="bg-teal-50 p-2 rounded-lg">
                            <svg class="h-6 w-6 text-[#1ABC9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-gray-600 font-bold uppercase tracking-wider text-sm">{{ __('Empowering Emerging Talent') }}</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="bg-teal-50 p-2 rounded-lg">
                            <svg class="h-6 w-6 text-[#1ABC9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-gray-600 font-bold uppercase tracking-wider text-sm">{{ __('Safe & Secure Transactions') }}</p>
                    </div>
                </div>
            </div>

            <!-- Section 3: Why Choose Us -->
            <div class="relative flex flex-col md:flex-row items-center gap-16 mb-32 transform transition-all duration-700 delay-400" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                <div class="flex-1 order-2 md:order-1">
                    <h2 class="text-3xl font-black text-[#2C3E50] mb-6 uppercase tracking-tight">{{ __('Why Choose Us') }}</h2>
                    <div class="space-y-8">
                        <div class="group p-6 bg-gray-50 rounded-2xl hover:bg-white hover:shadow-xl transition-all border border-transparent hover:border-teal-100 italic">
                            <h4 class="text-[#1ABC9C] font-black uppercase text-sm mb-2">{{ __('Curated Selection') }}</h4>
                            <p class="text-gray-600 font-medium">{{ __('Every piece on Unique Palette is vetted to ensure quality and authenticity, giving you peace of mind with every purchase.') }}</p>
                        </div>
                        <div class="group p-6 bg-gray-50 rounded-2xl hover:bg-white hover:shadow-xl transition-all border border-transparent hover:border-teal-100 italic">
                            <h4 class="text-[#1ABC9C] font-black uppercase text-sm mb-2">{{ __('Artist-First Approach') }}</h4>
                            <p class="text-gray-600 font-medium">{{ __('We offer fair commission structures and direct communication between artists and buyers, fostering a genuine connection.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 order-1 md:order-2 relative">
                    <img src="{{ asset('assets/about/about3.jpg') }}" alt="Why Choose Us" class="rounded-[40px] shadow-2xl w-full h-[400px] object-cover border-8 border-white transform hover:scale-105 transition-transform duration-700">
                </div>
            </div>

            <!-- Section 4: Our Vision -->
            <div class="relative flex flex-col md:flex-row items-center gap-16 transform transition-all duration-700 delay-500" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                <div class="flex-1 relative">
                    <div class="absolute inset-0 bg-teal-500/10 rounded-[40px] transform rotate-3 -z-10"></div>
                    <img src="{{ asset('assets/about/about4.jpg') }}" alt="Our Vision" class="relative rounded-[40px] shadow-2xl w-full h-[400px] object-cover border-8 border-white border-dashed border-teal-100">
                </div>
                <div class="flex-1">
                    <h2 class="text-3xl font-black text-[#2C3E50] mb-6 uppercase tracking-tight">{{ __('Our Vision') }}</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6 font-medium">
                        {{ __("To become the world's most trusted and vibrant digital home for art, where creativity has no boundaries and every artist has the opportunity to change the world, one masterpiece at a time.") }}
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-[#1ABC9C] rounded-full"></div>
                            <span class="text-gray-700 font-bold uppercase tracking-wider text-xs">{{ __('Globalizing Local Talent') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-[#1ABC9C] rounded-full"></div>
                            <span class="text-gray-700 font-bold uppercase tracking-wider text-xs">{{ __('Democratizing Art Ownership') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-[#1ABC9C] rounded-full"></div>
                            <span class="text-gray-700 font-bold uppercase tracking-wider text-xs">{{ __('Fostering Creative Innovation') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>