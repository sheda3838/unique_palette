<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {{ __('Art Gallery') }}
            </h2>
            <div class="w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search artworks..." class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            </div>
        </div>

        @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
        @endif
        @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($artworks as $artwork)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg transition hover:scale-105 duration-300">
                <div class="relative">
                    @if($artwork->image_path || $artwork->image_blob)
                    <img src="{{ $artwork->image_url }}" alt="{{ $artwork->title }}" class="w-full h-64 object-cover">
                    @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                    @endif
                    <div class="absolute top-0 right-0 bg-indigo-600 text-white px-2 py-1 m-2 rounded text-sm font-bold">
                        ${{ $artwork->price }}
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $artwork->title }}</h3>
                    <p class="text-sm text-gray-500 mb-2">by {{ $artwork->user->name }}</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">{{ $artwork->description }}</p>

                    <div class="mt-4">
                        @auth
                        @if(auth()->user()->isBuyer())
                        <button wire:click="addToCart({{ $artwork->id }})" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                            {{ __('Add to Cart') }}
                        </button>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="block text-center w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                            {{ __('Login to Buy') }}
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center">
                <h3 class="text-xl text-gray-500">{{ __('No artworks found matching your search.') }}</h3>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $artworks->links() }}
        </div>
    </div>
</div>