<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    /**
     * Create a Stripe Checkout Session and redirect the user.
     */
    public function checkout(Order $order)
    {
        $order->load('items.artwork');

        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'lkr',
                    'product_data' => [
                        'name' => $item->artwork->title,
                        'description' => "Artwork ID: {$item->artwork->id}",
                    ],
                    // Stripe expects amount in the smallest currency unit (cents for USD, whole units for some others).
                    // For LKR, it's cents (100 cents = 1 LKR).
                    'unit_amount' => (int)($item->price * 100),
                ],
                'quantity' => 1,
            ];
        }

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                // Using the specific URLs for UniquePalette
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
                'cancel_url' => route('payment.cancel') . '?order_id=' . $order->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'Could not initiate Stripe checkout.');
        }
    }

    /**
     * Handle Stripe Webhook calls.
     */
    public function webhook(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        if (!$endpoint_secret) {
            Log::error('Stripe Webhook Secret not configured.');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook: Invalid payload');
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id;

            Log::info("Stripe Webhook: Session completed for Order #{$orderId}");
            $this->processOrder($orderId, $session->id);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Marks the order as paid and updates artwork status.
     * Logic extracted for reuse and idempotency.
     */
    private function processOrder($orderId, $paymentId)
    {
        $order = Order::find($orderId);

        if ($order && $order->status === 'pending') {
            $order->update([
                'status' => 'processing',
                'payment_id' => $paymentId,
            ]);

            foreach ($order->items as $item) {
                if ($item->artwork) {
                    $item->artwork->update(['status' => 'sold']);
                }
            }
            Log::info("Order #{$orderId} successfully processed via Stripe.");
        } else {
            Log::info("Order #{$orderId} was already processed or not found.");
        }
    }
}
