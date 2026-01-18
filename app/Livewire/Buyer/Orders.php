<?php

namespace App\Livewire\Buyer;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Orders extends Component
{
    use WithPagination;

    public $selectedOrder;
    public $showModal = false;

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->isBuyer()) {
            abort(403);
        }
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = Order::with('items.artwork.artist')->find($orderId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedOrder = null;
    }

    public function render()
    {
        return view('livewire.buyer.orders', [
            'orders' => Order::where('user_id', Auth::id())
                ->with('items.artwork')
                ->latest()
                ->paginate(9)
        ])->layout('layouts.app');
    }
}
