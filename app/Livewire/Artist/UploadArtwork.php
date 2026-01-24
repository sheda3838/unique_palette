<?php

namespace App\Livewire\Artist;

use App\Models\Artwork;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadArtwork extends Component
{
    use WithFileUploads;

    public $title;
    public $price;
    public $description;
    public $image;
    public $successMessage = false;

    // TEMP debug message for UI (only shows when APP_DEBUG=true)
    public $debugError = null;

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
        $this->debugError = null; // reset when user picks a new file
    }

    public function save()
    {
        $this->validate();
        $this->debugError = null;

        try {
            // More reliable read (helps on some container environments)
            $realPath = $this->image->getRealPath();

            if (!$realPath || !is_readable($realPath)) {
                throw new \RuntimeException("Uploaded temp file not readable. realPath=" . ($realPath ?? 'null'));
            }

            $stream = fopen($realPath, 'rb');
            if ($stream === false) {
                throw new \RuntimeException("Failed to open uploaded temp file stream. realPath=" . $realPath);
            }

            $imageData = stream_get_contents($stream);
            fclose($stream);

            if ($imageData === false || $imageData === '') {
                throw new \RuntimeException("Failed to read uploaded image bytes. realPath=" . $realPath);
            }

            $imageMime = $this->image->getMimeType() ?? 'image/jpeg';

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
    // Log real cause (safe — no blob/base64 logged)
    logger()->error('UploadArtwork save failed', [
        'user_id' => auth()->id(),
        'tmp_path' => $this->image?->getRealPath(),
        'is_readable' => $this->image?->getRealPath()
            ? is_readable($this->image->getRealPath())
            : null,
        'size' => $this->image?->getSize(),
        'mime' => $this->image?->getMimeType(),
        'original_name' => $this->image?->getClientOriginalName(),
        'exception' => get_class($e),
        'message' => $e->getMessage(),
    ]);

    // TEMP: show safe message in UI only when APP_DEBUG=true
    if (config('app.debug')) {
        $this->debugError = get_class($e) . ': ' . $e->getMessage();
    }

    $this->addError('image', 'Upload failed on server. Please try again.');

    // 🔥 CRITICAL: DO NOT return null / nothing
    // Let Livewire finish normally
    return $this->render();
}
    }

    public function render()
    {
        return view('livewire.artist.upload-artwork')->layout('layouts.app');
    }
}