<?php

namespace App\Livewire;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;

    protected $listeners = ['cartUpdated' => 'updateCart', 'cart-updated' => 'updateCart'];

    public function mount()
    {
        if (!Auth::user() || !Auth::user()->isBuyer()) {
            abort(403);
        }
        $this->updateCart();
    }

    public function updateCart()
    {
        $items = CartItem::where('user_id', Auth::id())->with('artwork')->get();
        $this->cartItems = $items;
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

    public function render()
    {
        return view('livewire.cart')->layout('layouts.app');
    }
}
