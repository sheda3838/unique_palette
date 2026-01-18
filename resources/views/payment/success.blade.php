<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg text-center">

            <svg class="mx-auto h-16 w-16 text-green-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>

            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Payment Successful!</h2>

            <p class="mb-4 text-gray-600 dark:text-gray-400">
                Thank you for your purchase. Your order #{{ $order_id }} has been placed successfully.
            </p>

            <div class="mt-6">
                <a href="{{ route('gallery') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Continue Shopping
                </a>
                <a href="{{ route('dashboard') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>