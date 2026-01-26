<?php

namespace App\Livewire\Buyer;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Orders extends Component
{
    use WithPagination;

    public $selectedOrder;
    public $showModal = false;

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'buyer') {
            abort(403);
        }
    }

    public function viewOrder($orderId)
    {
        $order = Order::with(['items.artwork.user' => function ($q) {
            $q->select('id', 'name');
        }, 'items.artwork' => function ($q) {
            $q->select('id', 'user_id', 'title', 'price', 'image_path')
                ->selectRaw('image_blob IS NOT NULL as has_image_blob');
        }])->find($orderId);

        if ($order) {
            $this->selectedOrder = [
                'id' => $order->id,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at->format('M d, Y'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'price' => $item->price,
                        'artwork' => [
                            'title' => $item->artwork->title,
                            'image_url' => $item->artwork->image_url,
                            'user' => [
                                'name' => $item->artwork->user->name,
                            ],
                        ],
                    ];
                })->toArray(),
            ];
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedOrder = null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.buyer.orders', [
            'orders' => Order::where('user_id', Auth::id())
                ->with(['items.artwork' => function ($q) {
                    $q->select('id', 'title', 'price', 'image_path')
                        ->selectRaw('image_blob IS NOT NULL as has_image_blob');
                }])
                ->latest()
                ->paginate(9)
        ]);
    }
}
