<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Artwork;
use App\Models\CartItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $userAddress;

    public function mount()
    {
        if (!Auth::user() || !Auth::user()->isBuyer()) {
            abort(403);
        }

        $items = CartItem::where('user_id', Auth::id())->with('artwork')->get();

        if ($items->isEmpty()) {
            return $this->redirectRoute('gallery', navigate: true);
        }

        $this->cartItems = $items;
        $this->total = $items->sum(function ($item) {
            return $item->artwork ? $item->artwork->price * $item->quantity : 0;
        });
        $this->userAddress = Auth::user()->address;
    }

    public function placeOrder()
    {
        if ($this->cartItems->isEmpty()) {
            return $this->redirectRoute('gallery', navigate: true);
        }

        DB::beginTransaction();

        try {
            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $this->total,
                'status' => 'pending',
            ]);

            // Create Order Items
            foreach ($this->cartItems as $cartItem) {
                // Check if artwork is still available
                $artwork = $cartItem->artwork;
                if (!$artwork || $artwork->status !== 'approved') {
                    throw new \Exception("Artwork '{$artwork->title}' is no longer available.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'artwork_id' => $artwork->id,
                    'price' => $artwork->price,
                ]);
            }

            DB::commit();

            // Clear Cart
            CartItem::where('user_id', Auth::id())->delete();

            // Redirect to Payment
            return redirect()->route('payment', ['order' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Order failed: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.checkout')->layout('layouts.app');
    }
}
