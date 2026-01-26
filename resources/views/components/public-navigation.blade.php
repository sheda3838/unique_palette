@if(!auth()->check() || (auth()->check() && auth()->user()->role !== 'admin'))
<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md shadow-sm border-b border-teal-50 fixed w-full z-50 transition-all duration-300 top-0 left-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ url('/') }}" wire:navigate class="flex items-center gap-3 group">
                        <div class="p-2 bg-teal-50 rounded-xl group-hover:bg-teal-100 transition-colors">
                            <img src="{{ asset('assets/logo.png') }}" class="h-8 w-auto transition-transform group-hover:scale-110" alt="Logo">
                        </div>
                        <span class="font-black text-[#2C3E50] text-xl tracking-tighter uppercase hidden sm:block group-hover:text-[#1ABC9C] transition-colors">Unique Palette</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ url('/') }}" :active="request()->is('/')">
                        {{ __('Home') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('about-us') }}" :active="request()->routeIs('about-us')">
                        {{ __('About Us') }}
                    </x-nav-link>

                    @guest
                    <x-nav-link href="{{ route('gallery') }}" :active="request()->routeIs('gallery')">
                        {{ __('Gallery') }}
                    </x-nav-link>
                    @endguest

                    @auth
                    @if(auth()->user()->role === 'buyer')
                    <x-nav-link href="{{ route('gallery') }}" :active="request()->routeIs('gallery')">
                        {{ __('Gallery') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('buyer.orders') }}" :active="request()->routeIs('buyer.orders')">
                        {{ __('My Orders') }}
                    </x-nav-link>
                    @endif

                    @if(auth()->user()->role === 'artist')
                    <x-nav-link href="{{ route('artist.artworks') }}" :active="request()->routeIs('artist.artworks')">
                        {{ __('My Artworks') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('artist.upload') }}" :active="request()->routeIs('artist.upload')">
                        {{ __('Upload Artwork') }}
                    </x-nav-link>
                    @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                @if(request()->routeIs('about-us'))
                <livewire:language-switcher />
                @endif

                @auth
                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button class="flex text-sm border-2 border-[#1ABC9C] rounded-full focus:outline-none focus:border-teal-700 transition shadow-sm p-0.5">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                            @else
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-[#1ABC9C]/20 text-sm leading-4 font-bold rounded-xl text-[#1ABC9C] bg-white hover:bg-teal-50 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400 font-bold uppercase tracking-wider">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if(Auth::user()->role === 'buyer')
                            <x-dropdown-link href="{{ route('buyer.orders') }}" wire:navigate>
                                {{ __('My Orders') }}
                            </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @else
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" wire:navigate class="font-bold text-[#2C3E50] hover:text-[#1ABC9C] uppercase tracking-wider text-sm transition-colors">Log in</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" wire:navigate class="btn-primary-teal">Register</a>
                    @endif
                </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden gap-2">
                @if(request()->routeIs('about-us'))
                <livewire:language-switcher />
                @endif
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-teal-50 focus:outline-none transition-colors duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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
        x-cloak
        class="sm:hidden bg-white border-t border-gray-100 shadow-xl">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ url('/') }}" :active="request()->is('/')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('about-us') }}" :active="request()->routeIs('about-us')">
                {{ __('About Us') }}
            </x-responsive-nav-link>

            @guest
            <x-responsive-nav-link href="{{ route('gallery') }}" :active="request()->routeIs('gallery')">
                {{ __('Gallery') }}
            </x-responsive-nav-link>
            <div class="border-t border-gray-100 my-2"></div>
            <x-responsive-nav-link href="{{ route('login') }}">
                {{ __('Log in') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('register') }}">
                {{ __('Register') }}
            </x-responsive-nav-link>
            @endguest

            @auth
            @if(auth()->user()->role === 'buyer')
            <x-responsive-nav-link href="{{ route('gallery') }}" :active="request()->routeIs('gallery')">
                {{ __('Gallery') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('buyer.orders') }}" :active="request()->routeIs('buyer.orders')">
                {{ __('My Orders') }}
            </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'artist')
            <x-responsive-nav-link href="{{ route('artist.artworks') }}" :active="request()->routeIs('artist.artworks')">
                {{ __('My Artworks') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('artist.upload') }}" :active="request()->routeIs('artist.upload')">
                {{ __('Upload Artwork') }}
            </x-responsive-nav-link>
            @endif
            @endauth
        </div>

        @auth
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div class="shrink-0 mr-3">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-[#1ABC9C]" src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" />
                </div>
                @endif

                <div>
                    <div class="font-bold text-base text-[#2C3E50]">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                        @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
<div class="h-20"></div> {{-- Spacer for fixed navbar --}}
@endif