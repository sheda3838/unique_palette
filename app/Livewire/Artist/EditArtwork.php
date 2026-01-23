<?php

namespace App\Livewire\Artist;

use App\Models\Artwork;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditArtwork extends Component
{
    use WithFileUploads;

    public $artworkId;
    public $title;
    public $price;
    public $description;
    public $image;
    public $existingImage;

    protected $rules = [
        'title' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string',
        'image' => 'nullable|image|max:10240', // 10MB Max
    ];

    public function mount(Artwork $artwork)
    {
        // Security check: ensure user is an artist, the artwork belongs to the user and is NOT sold
        if (!auth()->user()->isArtist() || $artwork->user_id !== auth()->id() || $artwork->status === 'sold') {
            abort(403, 'You are not authorized to edit this artwork or it has been sold.');
        }

        $this->artworkId = $artwork->id;
        $this->title = $artwork->title;
        $this->price = $artwork->price;
        $this->description = $artwork->description;
        $this->existingImage = $artwork->image_url; // Use URL instead of path for rendering
    }

    public function update()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'price' => $this->price,
            'description' => $this->description,
            'status' => 'pending', // Re-approval required
        ];

        $artwork = Artwork::select('id', 'image_blob', 'image_mime')->findOrFail($this->artworkId);

        if ($this->image) {
            $imageData = file_get_contents($this->image->getRealPath());
            $imageMime = $this->image->getClientMimeType();

            $data['image_path'] = null; // No longer using filesystem
            $data['image_blob'] = $imageData;
            $data['image_mime'] = $imageMime;
        }

        $artwork->update($data);

        session()->flash('message', 'Artwork updated successfully and is pending approval.');

        return $this->redirectRoute('artist.artworks', navigate: true);
    }

    public function render()
    {
        return view('livewire.artist.edit-artwork')->layout('layouts.app');
    }
}
