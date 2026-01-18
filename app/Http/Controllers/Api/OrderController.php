<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * View my orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.artwork:id,title,image_path'])
            ->latest()
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    }

    /**
     * Place order.
     */
    public function store(Request $request)
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('artwork')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->artwork->price * $item->quantity;
        });

        return DB::transaction(function () use ($cartItems, $totalAmount) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'artwork_id' => $cartItem->artwork_id,
                    'price' => $cartItem->artwork->price,
                ]);
            }

            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('items.artwork')
            ], 201);
        });
    }

    /**
     * View order details.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'order' => $order->load(['items.artwork', 'user:id,name,email'])
        ]);
    }
}
