<?php

namespace App\Livewire\Gallery;

use App\Models\Artwork;
use App\Models\CartItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ArtGallery extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showCartModal = false;
    public $selectedArtwork;

    // Cart logic
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function getCartProperty()
    {
        if (!Auth::check()) return collect();
        return CartItem::where('user_id', Auth::id())
            ->with(['artwork' => function ($q) {
                $q->select('id', 'user_id', 'title', 'price', 'image_path')
                    ->selectRaw('image_blob IS NOT NULL as has_image_blob');
            }, 'artwork.user' => function ($q) {
                $q->select('id', 'name');
            }])->get();
    }

    public function getCartTotalProperty()
    {
        return $this->cart->sum(function ($item) {
            return ($item->artwork->price ?? 0) * $item->quantity;
        });
    }

    public function viewArtwork($id)
    {
        // Publicly viewable
        $artwork = Artwork::with(['user' => function ($q) {
            $q->select('id', 'name')->selectRaw('profile_image_blob IS NOT NULL as has_profile_image_blob');
        }])
            ->select('id', 'user_id', 'title', 'description', 'price', 'image_path', 'status')
            ->selectRaw('image_blob IS NOT NULL as has_image_blob')
            ->find($id);
        if ($artwork) {
            $this->selectedArtwork = [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'price' => $artwork->price,
                'description' => $artwork->description,
                'status' => $artwork->status,
                'user_id' => $artwork->user_id,
                'image_url' => $artwork->image_url,
                'user' => [
                    'id' => $artwork->user->id,
                    'name' => $artwork->user->name,
                    'profile_image_url' => $artwork->user->profile_image_url,
                ],
            ];
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedArtwork = null;
    }

    public function addToCart($id)
    {
        if (!Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        if (!Auth::user()->isBuyer()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Only buyers can add items to cart.']);
            return;
        }

        $artwork = Artwork::select('id', 'status')->find($id);

        if (!$artwork || $artwork->status !== 'approved') {
            return;
        }

        CartItem::updateOrCreate(
            ['user_id' => Auth::id(), 'artwork_id' => $id],
            ['quantity' => 1]
        );

        $this->dispatch('cartUpdated');
    }

    public function removeFromCart($id)
    {
        if (!Auth::check()) return;
        CartItem::where('user_id', Auth::id())->where('artwork_id', $id)->delete();
        $this->dispatch('cartUpdated');
    }

    public function clearCart()
    {
        if (!Auth::check()) return;
        CartItem::where('user_id', Auth::id())->delete();
        $this->dispatch('cartUpdated');
    }

    public function closeCartModal()
    {
        $this->showCartModal = false;
    }

    public function deleteArtwork($id)
    {
        if (!Auth::check() || !Auth::user()->isArtist()) {
            abort(403);
        }

        $artwork = Artwork::select('id', 'user_id', 'status')->where('id', $id)->where('user_id', Auth::id())->first();

        if ($artwork) {
            if ($artwork->status === 'sold') {
                session()->flash('error', 'Sold artworks cannot be deleted.');
                return;
            }

            $artwork->delete();
            session()->flash('message', 'Artwork deleted successfully.');

            if ($this->selectedArtwork && $this->selectedArtwork->id == $id) {
                $this->closeModal();
            }
        }
    }

    public function render()
    {
        $user = Auth::user();
        $query = Artwork::query();

        if ($user && $user->isArtist()) {
            // Artist sees their own artworks
            $query->where('user_id', $user->id);
            if ($this->search) {
                $query->where('title', 'like', '%' . $this->search . '%');
            }
        } else {
            // Buyers/Guests see approved artworks
            $query->where('status', 'approved');

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            }
        }

        return view('livewire.gallery.art-gallery', [
            'artworks' => $query->select('id', 'user_id', 'title', 'price', 'image_path', 'status', 'created_at')
                ->selectRaw('image_blob IS NOT NULL as has_image_blob')
                ->latest()
                ->paginate(12),
        ])->layout('layouts.public');
    }
}
