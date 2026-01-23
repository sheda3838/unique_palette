<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 font-heading">{{ __('My Orders') }}</h2>

        <div class="space-y-6">
            @if($orders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($orders as $order)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl transition duration-300 hover:shadow-2xl border border-gray-100 dark:border-gray-700 cursor-pointer group"
                    wire:click="viewOrder({{ $order->id }})">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">Order #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $order->status === 'processing' ? 'bg-green-100 text-green-800' : 
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                           ($order->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                Rs. {{ number_format($order->total_amount, 2) }}
                            </p>
                            <p class="text-sm text-gray-400">{{ $order->items->count() }} Item(s)</p>
                        </div>

                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach($order->items->take(3) as $item)
                            @if($item->artwork)
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 object-cover" src="{{ $item->artwork->image_url }}" alt="{{ $item->artwork->title }}">
                            @else
                            <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-200 flex items-center justify-center text-xs text-gray-500">?</div>
                            @endif
                            @endforeach
                            @if($order->items->count() > 3)
                            <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                                +{{ $order->items->count() - 3 }}
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 text-sm text-indigo-600 dark:text-indigo-400 font-medium group-hover:underline">
                            View Details &rarr;
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No orders yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by browsing our gallery.</p>
                <div class="mt-6">
                    <a href="{{ route('gallery') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Browse Artworks
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Details Modal -->
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Order Details') }}
            @if($selectedOrder)
            <span class="ml-2 text-sm text-gray-500">#{{ $selectedOrder['id'] }}</span>
            @endif
        </x-slot>

        <x-slot name="content">
            @if($selectedOrder)
            <div class="space-y-6">
                <div class="flex-between items-center border-b border-gray-200 dark:border-gray-700 pb-4">
                    <div>
                        <p class="text-sm text-gray-500">Placed on</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder['created_at'] }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $selectedOrder['status'] === 'processing' ? 'bg-green-100 text-green-800' : 
                                   ($selectedOrder['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   ($selectedOrder['status'] === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($selectedOrder['status']) }}
                        </span>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Items</h4>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 space-y-4">
                        @foreach($selectedOrder['items'] as $item)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16 rounded-md overflow-hidden bg-gray-200">
                                @if($item['artwork'])
                                <img class="h-full w-full object-cover" src="{{ $item['artwork']['image_url'] }}" alt="{{ $item['artwork']['title'] }}">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $item['artwork']['title'] ?? 'Unknown Artwork' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    by {{ $item['artwork']['user']['name'] ?? 'Unknown Artist' }}
                                </p>
                            </div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                Rs. {{ number_format($item['price'], 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                        <p>Total Amount</p>
                        <p>Rs. {{ number_format($selectedOrder['total_amount'], 2) }}</p>
                    </div>
                </div>
            </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>