<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('Shopping Cart') }}</h2>

        @if(count($cartItems) > 0)
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($cartItems as $item)
                    <li class="py-4 flex">
                        <div class="shrink-0">
                            @if($item->artwork && ($item->artwork->image_path || $item->artwork->image_blob))
                            <img class="h-24 w-24 rounded-md object-cover" src="{{ $item->artwork->image_url }}" alt="{{ $item->artwork->title }}">
                            @else
                            <div class="h-24 w-24 rounded-md bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No Image</span>
                            </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                                    <h3>{{ $item->artwork->title ?? 'Unknown' }}</h3>
                                    <p class="ml-4">LKR {{ number_format($item->artwork->price ?? 0, 2) }}</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Artist: {{ $item->artwork->user->name ?? 'Unknown' }}</p>
                            </div>
                            <div class="flex items-end justify-between text-sm">
                                <div class="text-gray-500">Qty {{ $item->quantity }}</div>
                                <div class="flex">
                                    <button wire:click="removeFromCart({{ $item->artwork_id }})" type="button" class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                    <p>Subtotal</p>
                    <p>${{ number_format($total, 2) }}</p>
                </div>
                <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                <div class="mt-6">
                    <a href="{{ route('checkout') }}" class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Checkout
                    </a>
                </div>
                <div class="mt-6 flex justify-center text-sm text-center text-gray-500">
                    <p>
                        or <a href="{{ route('gallery') }}" class="text-indigo-600 font-medium hover:text-indigo-500">Continue Shopping<span aria-hidden="true"> &rarr;</span></a>
                    </p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Cart is empty</h3>
            <p class="mt-1 text-sm text-gray-500 mb-6">Start browsing our collection to find unique art.</p>
            <a href="{{ route('gallery') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                Browse Gallery
            </a>
        </div>
        @endif
    </div>
</div>