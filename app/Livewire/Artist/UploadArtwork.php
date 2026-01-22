<?php

namespace App\Livewire\Artist;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Artwork;

class UploadArtwork extends Component
{
    use WithFileUploads;

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

    // Start smaller to avoid Railway/PHP/DB limits. Increase later.
    protected $rules = [
        'title' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
    ];

    public function updatedImage()
    {
        $this->validateOnly('image', [
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    public function save()
    {
        $this->validate();

        // IMPORTANT: keep $this->image as UploadedFile (don't overwrite it)
        $file = $this->image;

        $imageData = file_get_contents($file->getRealPath());
        $imageMime = $file->getClientMimeType(); // safer than getMimeType()

        Artwork::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'price' => $this->price,
            'description' => $this->description,
            'image_path' => null,
            'image_blob' => $imageData,
            'image_mime' => $imageMime,
            'status' => 'pending',
        ]);

        // reset inputs so Livewire doesn't keep old temp file
        $this->reset(['title', 'price', 'description', 'image']);

        session()->flash('message', 'Artwork successfully uploaded and is pending approval.');

        return $this->redirectRoute('artist.artworks', navigate: true);
    }

    public function render()
    {
        return view('livewire.artist.upload-artwork')->layout('layouts.app');
    }
}
