<?php

namespace App\Livewire\Admin;

use App\Models\Artwork;
use Livewire\Attributes\Layout;
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
        $artwork = Artwork::with('user:id,name')
            ->select('id', 'user_id', 'title', 'price', 'description', 'status', 'image_path')
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
                'user' => [
                    'name' => $artwork->user->name,
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

    public function updateStatus($id, $status)
    {
        $artwork = Artwork::find($id);
        if ($artwork) {
            $artwork->update(['status' => $status]);

            // If the modal is open and shows this artwork, refresh it
            if ($this->selectedArtwork && $this->selectedArtwork['id'] === $id) {
                $this->viewArtwork($id); // Re-fetch as array
            }

            session()->flash('message', 'Artwork status updated.');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Artwork::with('user:id,name,email')
            ->select('id', 'user_id', 'title', 'price', 'status', 'image_path')
            ->selectRaw('image_blob IS NOT NULL as has_image_blob')
            ->latest();

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return view('livewire.admin.artworks', [
            'artworks' => $query->paginate(10),
        ]);
    }
}
