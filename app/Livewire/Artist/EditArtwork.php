<?php

namespace App\Livewire\Artist;

use App\Models\Artwork;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security check: ensure user is an artist, the artwork belongs to the user and is NOT sold
        if (!$user || $user->role !== 'artist' || $artwork->user_id !== $user->id || $artwork->status === 'sold') {
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

        // Increase memory for blob processing
        ini_set('memory_limit', '512M');

        $artwork = Artwork::select('id', 'updated_at')->findOrFail($this->artworkId);
        $imageData = null;

        if ($this->image) {
            $imageData = file_get_contents($this->image->getRealPath());
            $imageMime = $this->image->getClientMimeType();

            $data['image_path'] = null;
            $data['image_blob'] = $imageData; // Put it in data directly for consistency
            $data['image_mime'] = $imageMime;
        }

        $artwork->update($data);

        // If for some reason the above didn't update the blob (though it should), 
        // the timestamp is already refreshed by Eloquent update.
        // We also unset imageData to free memory.
        if ($imageData) {
            unset($imageData);
        }

        session()->flash('message', 'Artwork updated successfully and is pending approval.');

        // Clear file property to avoid serialization issues
        $this->image = null;

        return $this->redirectRoute('artist.artworks', navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.artist.edit-artwork');
    }
}
