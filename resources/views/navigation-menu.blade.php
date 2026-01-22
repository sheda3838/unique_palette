<div>
    <nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md shadow-sm border-b border-teal-50 fixed w-full z-50 transition-all duration-300 top-0 left-0">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 group">
                            <div class="p-2 bg-teal-50 rounded-xl group-hover:bg-teal-100 transition-colors">
                                <img src="{{ asset('assets/logo.png') }}" class="h-8 w-auto transition-transform group-hover:scale-110" alt="Logo">
                            </div>
                            <span class="font-black text-[#2C3E50] text-xl tracking-tighter uppercase hidden sm:block group-hover:text-[#1ABC9C] transition-colors">Unique Palette</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('welcome') }}" :active="request()->routeIs('welcome')">
                            {{ __('Home') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('gallery') }}" :active="request()->routeIs('gallery')">
                            {{ __('Gallery') }}
                        </x-nav-link>

                        @if(!Auth::user()->isAdmin())
                        <x-nav-link href="{{ route('about-us') }}" :active="request()->routeIs('about-us')">
                            {{ __('About Us') }}
                        </x-nav-link>
                        @endif

                        @if(Auth::user()->isArtist())
                        <x-nav-link href="{{ route('artist.artworks') }}" :active="request()->routeIs('artist.artworks')">
                            {{ __('My Artworks') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('artist.upload') }}" :active="request()->routeIs('artist.upload')">
                            {{ __('Upload Artwork') }}
                        </x-nav-link>
                        @endif

                        @if(Auth::user()->isBuyer())
                        <x-nav-link href="{{ route('buyer.orders') }}" :active="request()->routeIs('buyer.orders')">
                            {{ __('My Orders') }}
                        </x-nav-link>
                        @endif

                        @if(Auth::user()->isAdmin())
                        <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Admin Dashboard') }}
                        </x-nav-link>
                        @endif
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                    @if(request()->routeIs('about-us'))
                    <livewire:language-switcher />
                    @endif
                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm border-2 border-[#1ABC9C] rounded-full focus:outline-none focus:border-teal-700 transition shadow-sm p-0.5 group relative">
                                    <img class="h-10 w-10 rounded-full object-cover border border-white" src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" />
                                    <div class="absolute -bottom-1 -right-1 bg-[#1ABC9C] rounded-full p-1 border-2 border-white shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-2 h-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400 font-bold uppercase tracking-wider">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}" wire:navigate>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden gap-2">
                    @if(request()->routeIs('about-us'))
                    <livewire:language-switcher />
                    @endif
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#1ABC9C] hover:text-teal-700 hover:bg-teal-50 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="md:hidden bg-white border-t border-gray-100 shadow-xl">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('welcome') }}" :active="request()->routeIs('welcome')">
                    {{ __('Home') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('gallery') }}" :active="request()->routeIs('gallery')">
                    {{ __('Gallery') }}
                </x-responsive-nav-link>

                @if(!Auth::user()->isAdmin())
                <x-responsive-nav-link href="{{ route('about-us') }}" :active="request()->routeIs('about-us')">
                    {{ __('About Us') }}
                </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isArtist())
                <x-responsive-nav-link href="{{ route('artist.artworks') }}" :active="request()->routeIs('artist.artworks')">
                    {{ __('My Artworks') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('artist.upload') }}" :active="request()->routeIs('artist.upload')">
                    {{ __('Upload Artwork') }}
                </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isBuyer())
                <x-responsive-nav-link href="{{ route('buyer.orders') }}" :active="request()->routeIs('buyer.orders')">
                    {{ __('My Orders') }}
                </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isAdmin())
                <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Admin Dashboard') }}
                </x-responsive-nav-link>
                @endif
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-6">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover border-2 border-[#1ABC9C]" src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                    @endif
                    <div>
                        <div class="font-bold text-base text-[#2C3E50]">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <div class="h-20"></div> {{-- Spacer for fixed navbar --}}
</div>