<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg text-center">

            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Redirecting to Payment...</h2>

            <p class="mb-4 text-gray-600 dark:text-gray-400">Please wait while we transfer you to our secure payment gateway.</p>

            <div class="flex justify-center mb-6">
                <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <form id="payhere_form" method="post" action="https://sandbox.payhere.lk/pay/checkout">
                <input type="hidden" name="merchant_id" value="{{ $merchant_id }}">
                <input type="hidden" name="hash" value="{{ $hash }}">
                <input type="hidden" name="return_url" value="{{ route('payment.success', ['order_id' => $order->id]) }}">
                <input type="hidden" name="cancel_url" value="{{ route('payment.cancel', ['order_id' => $order->id]) }}">
                <input type="hidden" name="notify_url" value="{{ route('payment.notify') }}">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="items" value="Order #{{ $order->id }}">
                <input type="hidden" name="currency" value="LKR">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="first_name" value="{{ explode(' ', Auth::user()->name)[0] }}">
                <input type="hidden" name="last_name" value="{{ isset(explode(' ', Auth::user()->name)[1]) ? explode(' ', Auth::user()->name)[1] : '' }}">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                <input type="hidden" name="phone" value="0777123456">
                <input type="hidden" name="address" value="{{ Auth::user()->address->street }}">
                <input type="hidden" name="city" value="{{ Auth::user()->address->city }}">
                <input type="hidden" name="country" value="Sri Lanka">

                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-500 underline">
                    Click here if you are not redirected automatically
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('payhere_form').submit();
        });
    </script>
</x-guest-layout>