<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $filter = 'all';

    public function updateStatus($id, $status)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update(['status' => $status]);
            session()->flash('message', 'Order status updated to ' . ucfirst($status));
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Order::with('user', 'items.artwork')->latest();

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return view('livewire.admin.orders', [
            'orders' => $query->paginate(10),
        ]);
    }
}
