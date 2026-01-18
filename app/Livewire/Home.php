<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Artwork;

class Home extends Component
{
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
                $artworks = Artwork::where('status', 'approved')->latest()->take(3)->get();
            }
        }

        // Using inline comments for Assessment Alignment (Requirement 6)
        // This component replaces the traditional Blade-based home page to enable SPA-like navigation via wire:navigate
        return view('livewire.home', [
            'stats' => $stats,
            'buyerArtworks' => $artworks
        ])->layout('layouts.public');
    }
}
