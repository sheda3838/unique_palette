<?php

namespace App\Livewire\Artist;

use App\Models\Artwork;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isArtist()) {
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
        $artwork = Artwork::select('id', 'title', 'price', 'description', 'status', 'image_path')
            ->selectRaw('image_blob IS NOT NULL as has_image_blob')
            ->find($id);
        if ($artwork) {
            $this->selectedArtwork = [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'price' => $artwork->price,
                'description' => $artwork->description,
                'status' => $artwork->status,
                'image_url' => $artwork->image_url,
            ];
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedArtwork = null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.artist.artworks', [
            'artworks' => Artwork::select('id', 'title', 'price', 'status', 'image_path')
                ->selectRaw('image_blob IS NOT NULL as has_image_blob')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(9),
        ]);
    }
}
