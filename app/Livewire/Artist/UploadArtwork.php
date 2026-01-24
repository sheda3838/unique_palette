<?php

namespace App\Livewire\Artist;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Artwork;
use Illuminate\Support\Facades\Log;

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

    protected $rules = [
        'title' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,webp|max:5120', // 5MB
    ];

    public function updatedImage()
    {
        $this->validateOnly('image');
    }

    public function save()
    {
        $this->validate();

        try {
            $imageData = file_get_contents($this->image->getRealPath());
            $imageMime = $this->image->getMimeType();

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

            session()->flash('message', 'Artwork successfully uploaded and is pending approval.');

            return $this->redirectRoute('artist.artworks', navigate: true);
        } catch (\Throwable $e) {
            Log::error('UploadArtwork save failed', [
                'user_id' => auth()->id(),
                'tmp_path' => $this->image?->getRealPath(),
                'is_readable' => $this->image?->getRealPath()
                    ? is_readable($this->image->getRealPath())
                    : null,
                'size' => $this->image?->getSize(),
                'mime' => $this->image?->getMimeType(),
                'original_name' => $this->image?->getClientOriginalName(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->addError(
                'image',
                'Upload failed on server. Please try a smaller image or contact support if the problem persists.'
            );

            return;
        }
    }

    public function render()
    {
        return view('livewire.artist.upload-artwork')->layout('layouts.app');
    }
}
