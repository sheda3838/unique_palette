<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Artwork;
use App\Models\CartItem;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $userAddress;

    public function mount()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user || !$user->isBuyer()) {
            abort(403);
        }

        $items = CartItem::where('user_id', Auth::id())
            ->with(['artwork' => function ($q) {
                $q->select('id', 'user_id', 'title', 'price', 'status', 'image_path')
                    ->selectRaw('image_blob IS NOT NULL as has_image_blob');
            }, 'artwork.user' => function ($q) {
                $q->select('id', 'name');
            }])->get();

        if ($items->isEmpty()) {
            return $this->redirectRoute('gallery', navigate: true);
        }

        $this->cartItems = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'artwork_id' => $item->artwork_id,
                'artwork' => $item->artwork ? [
                    'id' => $item->artwork->id,
                    'title' => $item->artwork->title,
                    'price' => $item->artwork->price,
                    'status' => $item->artwork->status,
                    'image_url' => $item->artwork->image_url,
                    'user' => [
                        'name' => $item->artwork->user->name,
                    ],
                ] : null,
            ];
        })->toArray();

        $this->total = $items->sum(function ($item) {
            return $item->artwork ? $item->artwork->price * $item->quantity : 0;
        });
        $this->userAddress = Auth::user()->address;
    }

    public function placeOrder()
    {
        if (empty($this->cartItems)) {
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
                // Re-fetch artwork from DB for security and safe data
                $artwork = Artwork::select('id', 'price', 'status')->find($cartItem['artwork_id']);

                if (!$artwork || $artwork->status !== 'approved') {
                    throw new \Exception("Artwork is no longer available.");
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

            // Redirect to Stripe Checkout
            return redirect()->route('checkout.stripe', ['order' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Order failed: ' . $e->getMessage());
            return;
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checkout');
    }
}
