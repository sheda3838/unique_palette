<?php

namespace App\Livewire\Artist;

use Livewire\Component;

class UploadArtwork extends Component
{
    use \Livewire\WithFileUploads;

    public $title;
    public $price;
    public $description;
    public $image;
    public $successMessage = false;

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->isArtist()) {
            abort(403);
        }
    }

    protected $rules = [
        'title' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,webp|max:5120', // 5MB Max, JPEG/PNG/WebP
    ];

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|mimes:jpeg,png,webp|max:5120',
        ]);
    }

    public function save()
    {
        $this->validate();

        $imageData = file_get_contents($this->image->getRealPath());
        $imageMime = $this->image->getMimeType();

        \App\Models\Artwork::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'price' => $this->price,
            'description' => $this->description,
            'image_path' => null, // Explicitly set to null as per requirements
            'image_blob' => $imageData,
            'image_mime' => $imageMime,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Artwork successfully uploaded and is pending approval.');

        return $this->redirectRoute('artist.artworks', navigate: true);
    }

    public function render()
    {
        return view('livewire.artist.upload-artwork')->layout('layouts.app');
    }
}
