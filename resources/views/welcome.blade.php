<x-public-layout>
    <!-- Hero Section -->
    <div class="relative bg-white dark:bg-gray-800 overflow-hidden flex flex-col lg:flex-row min-h-[85vh] lg:items-center">
        <div class="max-w-7xl mx-auto w-full lg:flex-1 z-10">
            <div class="relative bg-white dark:bg-gray-800 lg:max-w-2xl lg:w-full">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28 py-10">
                    <div class="text-center lg:text-left">
                        <h1 class="text-3xl tracking-tight font-black text-[#2C3E50] dark:text-white sm:text-4xl md:text-6xl uppercase tracking-tighter leading-none">
                            <span class="block xl:inline">Discover unique art</span>
                            <span class="block text-[#1ABC9C] xl:inline">for your unique taste</span>
                        </h1>
                        <p class="mt-6 text-lg text-gray-500 dark:text-gray-400 sm:max-w-xl sm:mx-auto md:mt-8 lg:mx-0 font-medium leading-relaxed">
                            Connect with talented artists from around the world. Buy original artworks securely and easily. Explore our curated collection of masterpieces.
                        </p>
                        @guest
                        <div class="mt-10 sm:mt-12 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6">
                            <a href="{{ route('gallery') }}" class="w-full sm:w-auto flex items-center justify-center px-12 py-5 border border-transparent text-lg font-black rounded-full text-white bg-[#1ABC9C] hover:bg-teal-600 transition-all shadow-[0_10px_30px_-10px_rgba(26,188,156,0.5)] uppercase tracking-wider">
                                Browse Gallery
                            </a>
                            <a href="{{ route('register') }}" class="w-full sm:w-auto flex items-center justify-center px-12 py-5 border-2 border-[#1ABC9C] text-lg font-black rounded-full text-[#1ABC9C] bg-white hover:bg-teal-50 transition-all uppercase tracking-wider">
                                Join as Artist
                            </a>
                        </div>
                        @endguest
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 h-72 sm:h-96 lg:h-full relative overflow-hidden">
            <img class="h-full w-full object-cover lg:object-center" src="{{ asset('assets/hero.jpg') }}" alt="Hero Image">
            <div class="absolute inset-0 bg-gradient-to-t from-white lg:bg-gradient-to-r lg:from-white via-white/20 to-transparent"></div>
        </div>
    </div>

    <!-- Dynamic Content Section -->
    <div class="py-24 bg-gray-50 dark:bg-gray-900 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @auth
            @if(auth()->user()->role === 'artist')
            <!-- Artist Stats Section -->
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter">Your Artwork Statistics</h2>
                <div class="h-1.5 w-20 bg-[#1ABC9C] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-[40px] shadow-sm border-2 border-teal-50 flex flex-col items-center text-center group hover:border-[#1ABC9C] transition-all">
                    <span class="text-5xl font-black text-[#1ABC9C] mb-4 group-hover:scale-110 transition-transform">{{ $stats['uploaded'] }}</span>
                    <span class="text-lg font-bold text-[#2C3E50] uppercase tracking-widest">Total Uploaded</span>
                </div>
                <div class="bg-white p-10 rounded-[40px] shadow-sm border-2 border-teal-50 flex flex-col items-center text-center group hover:border-[#1ABC9C] transition-all">
                    <span class="text-5xl font-black text-amber-500 mb-4 group-hover:scale-110 transition-transform">{{ $stats['pending'] }}</span>
                    <span class="text-lg font-bold text-[#2C3E50] uppercase tracking-widest">Pending Approval</span>
                </div>
                <div class="bg-white p-10 rounded-[40px] shadow-sm border-2 border-teal-50 flex flex-col items-center text-center group hover:border-[#1ABC9C] transition-all">
                    <span class="text-5xl font-black text-emerald-500 mb-4 group-hover:scale-110 transition-transform">{{ $stats['sold'] }}</span>
                    <span class="text-lg font-bold text-[#2C3E50] uppercase tracking-widest">Artworks Sold</span>
                </div>
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('artist.upload') }}" class="btn-primary-teal inline-flex items-center px-12 py-4 text-sm">Upload New Piece</a>
            </div>

            @elseif(auth()->user()->role === 'buyer')
            <!-- Buyer Recently Viewed/Favorites Section -->
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tighter">Top Picks for You</h2>
                <div class="h-1.5 w-20 bg-[#1ABC9C] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($buyerArtworks as $artwork)
                <div class="group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 border border-gray-100">
                    <div class="relative h-72 overflow-hidden">
                        <img src="{{ $artwork->image_url }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-black text-[#2C3E50] mb-2 uppercase tracking-tight">{{ $artwork->title }}</h3>
                        <p class="text-[#1ABC9C] font-black text-lg mb-6">LKR {{ number_format($artwork->price, 2) }}</p>
                        <a href="{{ route('gallery') }}" class="w-full block text-center py-4 rounded-xl border-2 border-[#1ABC9C] text-[#1ABC9C] font-black uppercase tracking-widest hover:bg-[#1ABC9C] hover:text-white transition-all">View Detail</a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-xl font-bold text-gray-400">No favorite artworks yet. Start exploring!</p>
                    <a href="{{ route('gallery') }}" class="btn-primary-teal mt-6 inline-block">Explore Gallery</a>
                </div>
                @endforelse
            </div>
            @endif
            @else
            <!-- Guest View (Art Enthusiasts & Creative Minds) -->
            <div class="space-y-32">
                <!-- Art Enthusiasts -->
                <div class="relative flex flex-col md:flex-row items-center gap-16">
                    <div class="absolute -top-10 left-1/4 w-72 h-[450px] bg-gradient-to-b from-[#1ABC9C]/30 to-transparent rounded-[50px] -z-10 hidden md:block"></div>
                    <div class="flex-1 bg-white p-12 border-4 border-[#2C3E50] shadow-[20px_20px_0px_#1ABC9C] z-10 max-w-lg">
                        <h3 class="text-3xl font-black text-[#1ABC9C] mb-6 uppercase tracking-tight">Art Enthusiasts</h3>
                        <p class="text-lg text-[#2C3E50] font-bold leading-relaxed mb-10">
                            Discover and own unique artworks from talented creators worldwide. Build your collection today.
                        </p>
                        <a href="{{ route('gallery') }}" class="inline-block px-12 py-5 bg-[#1ABC9C] text-white font-black rounded-xl uppercase tracking-wider hover:bg-teal-600 transition-all text-base shadow-lg">
                            Start Exploring
                        </a>
                    </div>
                    <div class="flex-1 relative">
                        <img src="{{ asset('assets/guest1.jpg') }}" alt="Art" class="rounded-[40px] shadow-2xl w-full max-w-md mx-auto transform hover:-rotate-2 transition-transform duration-500 border-8 border-white">
                        <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-amber-400 rounded-full -z-10 animate-pulse"></div>
                    </div>
                </div>

                <!-- Creative Minds -->
                <div class="relative flex flex-col md:flex-row-reverse items-center gap-16">
                    <div class="absolute -bottom-10 right-1/4 w-72 h-[450px] bg-gradient-to-t from-[#1ABC9C]/30 to-transparent rounded-[50px] -z-10 hidden md:block"></div>
                    <div class="flex-1 bg-white p-12 border-4 border-[#2C3E50] shadow-[-20px_20px_0px_#1ABC9C] z-10 max-w-lg">
                        <h3 class="text-3xl font-black text-[#1ABC9C] mb-6 uppercase tracking-tight">Creative Minds</h3>
                        <p class="text-lg text-[#2C3E50] font-bold leading-relaxed mb-10">
                            Showcase your unique artworks and reach buyers who appreciate your talent. Join our community.
                        </p>
                        <a href="{{ route('register') }}" class="inline-block px-12 py-5 bg-[#1ABC9C] text-white font-black rounded-xl uppercase tracking-wider hover:bg-teal-600 transition-all text-base shadow-lg">
                            Start Selling
                        </a>
                    </div>
                    <div class="flex-1 relative">
                        <img src="{{ asset('assets/guest2.jpg') }}" alt="Artist" class="rounded-[40px] shadow-2xl w-full max-w-md mx-auto transform hover:rotate-2 transition-transform duration-500 border-8 border-white">
                        <div class="absolute -top-6 -left-6 w-32 h-32 bg-[#2C3E50] rounded-full -z-10"></div>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </div>
</x-public-layout>