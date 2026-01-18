<?php

namespace App\Livewire\Artist;

use App\Models\Artwork;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Artworks extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $showModal = false;
    public $selectedArtwork = null;

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->isArtist()) {
            abort(403);
        }
    }

    public function deleteArtwork($id)
    {
        $artwork = Artwork::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Security check: only unsold artworks can be deleted
        if ($artwork->status === 'sold') {
            session()->flash('error', 'Sold artworks cannot be deleted.');
            return;
        }

        // Delete image from storage
        if ($artwork->image_path) {
            Storage::disk('public')->delete($artwork->image_path);
        }

        $artwork->delete();

        session()->flash('message', 'Artwork deleted successfully.');
        $this->showModal = false;
        $this->selectedArtwork = null;
    }

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

    public function render()
    {
        return view('livewire.artist.artworks', [
            'artworks' => Artwork::where('user_id', Auth::id())->latest()->paginate(9),
        ])->layout('layouts.app');
    }
}
