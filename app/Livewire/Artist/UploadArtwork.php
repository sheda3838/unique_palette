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

        // Increase memory and execution time for large blob processing
        ini_set('memory_limit', '512M');
        set_time_limit(60);

        try {
            $realPath = $this->image->getRealPath();

            if (!$realPath || !is_readable($realPath)) {
                throw new \RuntimeException("Uploaded temp file not readable.");
            }

            $imageMime = $this->image->getMimeType() ?? 'image/jpeg';

            // Read bytes safely
            $imageData = file_get_contents($realPath);

            if ($imageData === false || $imageData === '') {
                throw new \RuntimeException("Failed to read image bytes.");
            }

            // Perform creation without the blob first to keep the Eloquent model light
            $artwork = Artwork::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'price' => $this->price,
                'description' => $this->description,
                'image_path' => null,
                'image_blob' => null, // Placeholder
                'image_mime' => $imageMime,
                'status' => 'pending',
            ]);

            // Now update the blob using a raw DB query to avoid loading it into memory via Eloquent
            \Illuminate\Support\Facades\DB::table('artworks')
                ->where('id', $artwork->id)
                ->update(['image_blob' => $imageData]);

            // CRITICAL: Unset large binary data immediately to free memory
            unset($imageData);

            session()->flash('message', 'Artwork successfully uploaded and is pending approval.');

            // Clear the temporary file property before redirecting to avoid serialization issues
            $this->image = null;

            return $this->redirectRoute('artist.artworks', navigate: true);
        } catch (\Throwable $e) {
            // Log generic error for security but keep specific message for debug
            logger()->error('UploadArtwork failed: ' . $e->getMessage());

            $debugMessage = config('app.debug')
                ? ($e->getMessage())
                : 'Upload failed. Please try a smaller image.';

            $this->debugError = $debugMessage;

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
