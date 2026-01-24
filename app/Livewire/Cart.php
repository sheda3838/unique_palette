<?php

namespace App\Livewire;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;

    protected $listeners = ['cartUpdated' => 'updateCart', 'cart-updated' => 'updateCart'];

    public function mount()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user || !$user->isBuyer()) {
            abort(403);
        }
        $this->updateCart();
    }

    public function updateCart()
    {
        $items = CartItem::where('user_id', Auth::id())
            ->with(['artwork' => function ($q) {
                $q->select('id', 'user_id', 'title', 'price', 'image_path')
                    ->selectRaw('image_blob IS NOT NULL as has_image_blob');
            }, 'artwork.user' => function ($q) {
                $q->select('id', 'name');
            }])->get();
        $this->cartItems = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'artwork_id' => $item->artwork_id,
                'artwork' => $item->artwork ? [
                    'id' => $item->artwork->id,
                    'title' => $item->artwork->title,
                    'price' => $item->artwork->price,
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
    }

    public function removeFromCart($id)
    {
        CartItem::where('user_id', Auth::id())->where('artwork_id', $id)->delete();
        $this->updateCart();
        $this->dispatch('cartUpdated');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.cart');
    }
}
