<?php

namespace App\Livewire\Admin;

use App\Models\Artwork;
use Livewire\Component;
use Livewire\WithPagination;

class Artworks extends Component
{
    use WithPagination;

    public $filter = 'all';

    public $showModal = false;
    public $selectedArtwork;

    public function viewArtwork($id)
    {
        $this->selectedArtwork = Artwork::with('user')->find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedArtwork = null;
    }

    public function updateStatus($id, $status)
    {
        $artwork = Artwork::find($id);
        if ($artwork) {
            $artwork->update(['status' => $status]);

            // If the modal is open and shows this artwork, refresh it
            if ($this->selectedArtwork && $this->selectedArtwork->id === $id) {
                $this->selectedArtwork = $artwork->fresh(['user']);
            }

            session()->flash('message', 'Artwork status updated.');
        }
    }

    public function render()
    {
        $query = Artwork::with('user')->latest();

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return view('livewire.admin.artworks', [
            'artworks' => $query->paginate(10),
        ])->layout('layouts.app');
    }
}
