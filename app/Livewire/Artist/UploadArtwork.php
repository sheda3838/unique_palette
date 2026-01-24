<?php

namespace App\Livewire\Artist;

use App\Models\Artwork;   // ✅ MUST exist
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class UploadArtwork extends Component
{
    use WithFileUploads;

    public $title;
    public $price;
    public $description;
    public $image;
    public $debugError; // For displaying detailed errors in debug mode

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isArtist()) {
            abort(403);
        }
    }

    protected $rules = [
        'title' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,webp|max:5120',
    ];

    public function updatedImage()
    {
        $this->validateOnly('image');
    }

    public function save()
    {
        $this->validate();
        $this->debugError = null;

        try {
            $realPath = $this->image->getRealPath();

            if (!$realPath || !is_readable($realPath)) {
                throw new \RuntimeException("Uploaded temp file not readable. realPath=" . ($realPath ?? 'null'));
            }

            // Read bytes safely using a stream to avoid memory spikes
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
                'user_id' => Auth::id(),
                'title' => $this->title,
                'price' => $this->price,
                'description' => $this->description,
                'image_path' => null, // Explicitly null for blob storage
                'image_blob' => $imageData,
                'image_mime' => $imageMime,
                'status' => 'pending',
            ]);

            session()->flash('message', 'Artwork successfully uploaded and is pending approval.');

            return $this->redirectRoute('artist.artworks', navigate: true);
        } catch (\Throwable $e) {
            // Log safe details for debugging (never log the raw binary blob!)
            logger()->error('UploadArtwork failed', [
                'user_id' => Auth::id(),
                'tmp_path' => $this->image?->getRealPath(),
                'size' => $this->image?->getSize(),
                'mime' => $this->image?->getMimeType(),
                'original_name' => $this->image?->getClientOriginalName(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            $debugMessage = config('app.debug')
                ? (get_class($e) . ': ' . $e->getMessage())
                : 'Upload failed on server. Please try a smaller image or try again later.';

            // Store for the special debug box in Blade
            $this->debugError = $debugMessage;

            // Throwing ValidationException ensures Livewire returns a valid JSON 422 response
            // and the error message appears through the standard <x-input-error for="image" />
            throw ValidationException::withMessages([
                'image' => $debugMessage,
            ]);
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.artist.upload-artwork');
    }
}
