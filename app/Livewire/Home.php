<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Artwork;

class Home extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        $stats = null;
        $artworks = null;

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'artist') {
                $stats = [
                    'uploaded' => Artwork::where('user_id', $user->id)->count(),
                    'pending' => Artwork::where('user_id', $user->id)->where('status', 'pending')->count(),
                    'sold' => Artwork::where('user_id', $user->id)->where('status', 'sold')->count(),
                ];
            } elseif ($user->role === 'buyer') {
                $artworks = Artwork::select('id', 'title', 'price', 'image_path')
                    ->selectRaw('image_blob IS NOT NULL as has_image_blob')
                    ->where('status', 'approved')
                    ->latest()
                    ->take(3)
                    ->get();
            }
        }

        return view('livewire.home', [
            'stats' => $stats,
            'buyerArtworks' => $artworks
        ]);
    }
}
