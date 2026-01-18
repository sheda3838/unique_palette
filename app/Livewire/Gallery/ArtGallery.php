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
        return CartItem::where('user_id', Auth::id())->with(['artwork.user'])->get();
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
        $this->selectedArtwork = Artwork::with('user')->find($id);
        if ($this->selectedArtwork) {
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

        $artwork = Artwork::find($id);

        if (!$artwork || $artwork->status !== 'approved') {
            return;
        }

        CartItem::updateOrCreate(
            ['user_id' => Auth::id(), 'artwork_id' => $id],
            ['quantity' => DB::raw('quantity + 1')]
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

        $artwork = Artwork::where('id', $id)->where('user_id', Auth::id())->first();

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
            'artworks' => $query->latest()->paginate(12),
        ])->layout('layouts.public');
    }
}
