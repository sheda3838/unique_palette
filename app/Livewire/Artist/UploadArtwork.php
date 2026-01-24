<?php

namespace App\Livewire\Artist;

use App\Models\Artwork;
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
    public $debugError;

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
        'image' => 'required|image|mimes:jpeg,png,webp|max:10240', // Allowing up to 10MB
    ];

    public function updatedImage()
    {
        $this->validateOnly('image');
    }

    public function save()
    {
        $this->validate();
        $this->debugError = null;

        // Increase memory and execution time for large blob processing
        ini_set('memory_limit', '512M');
        set_time_limit(120);

        try {
            $realPath = $this->image->getRealPath();

            if (!$realPath || !is_readable($realPath)) {
                throw new \RuntimeException("Uploaded temporary file is not readable.");
            }

            // Using stream method for better memory efficiency as requested
            $stream = fopen($realPath, 'rb');
            if ($stream === false) {
                throw new \RuntimeException("Failed to open file stream.");
            }
            $imageData = stream_get_contents($stream);
            fclose($stream);

            if ($imageData === false || $imageData === '') {
                throw new \RuntimeException("Failed to read image bytes.");
            }

            $imageMime = $this->image->getMimeType() ?? 'image/jpeg';

            // Create Artwork record using Blob storage
            // Column 'image_path' is set to null as we now use 'image_blob'
            // NOTE: This requires 'image_path' to be nullable in the DB
            Artwork::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'price' => $this->price,
                'description' => $this->description,
                'image_path' => null,
                'image_blob' => $imageData,
                'image_mime' => $imageMime,
                'status' => 'pending',
            ]);

            // Free memory explicitly
            unset($imageData);

            session()->flash('message', 'Artwork successfully uploaded and is pending approval.');

            // Clear the temporary file to prevent Livewire from serializing it in the response
            $this->image = null;

            return $this->redirectRoute('artist.artworks', navigate: true);
        } catch (\Throwable $e) {
            // Log safe information (NO blob or base64 data)
            logger()->error('UploadArtwork failed', [
                'user_id' => Auth::id(),
                'exception' => get_class($e),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $errorMessage = config('app.debug')
                ? $e->getMessage()
                : 'The upload failed. Please try again with a smaller file.';

            $this->debugError = $errorMessage;

            // Throw ValidationException to ensure Livewire returns valid JSON
            throw ValidationException::withMessages([
                'image' => $errorMessage,
            ]);
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.artist.upload-artwork');
    }
}
