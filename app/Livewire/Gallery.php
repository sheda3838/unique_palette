<?php

namespace App\Livewire;

use App\Models\Artwork;
use Livewire\Component;
use Livewire\WithPagination;

class Gallery extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $query = Artwork::where('status', 'approved')->with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.gallery', [
            'artworks' => $query->latest()->paginate(12),
        ])->layout('layouts.public');
    }

    public function addToCart($artworkId)
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        if (\Illuminate\Support\Facades\Auth::user()->role !== 'buyer') {
            session()->flash('error', 'Only buyers can purchase artworks.');
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$artworkId])) {
            session()->flash('message', 'Artwork is already in your cart.');
            return;
        }

        $artwork = Artwork::find($artworkId);

        if (!$artwork) {
            return;
        }

        $cart[$artworkId] = [
            'id' => $artwork->id,
            'title' => $artwork->title,
            'price' => $artwork->price,
            'image_path' => $artwork->image_path,
            'artist_name' => $artwork->user->name,
        ];

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        session()->flash('message', 'Added to cart!');
    }
}
