<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Artwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'image_path',
        'image_blob',
        'image_mime',
        'status',
    ];

    protected $appends = ['image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_blob) {
            return route('artwork.image', ['id' => $this->id]);
        }

        if (!$this->image_path) {
            return asset('assets/placeholder.png');
        }

        // Full URL (Cloudinary/S3)
        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        // If it's just a filename like "a14.jpg" -> it's in public/assets/...
        if (!Str::contains($this->image_path, '/')) {
            return asset('assets/artworks/' . $this->image_path);
        }

        // If it's already an assets path
        if (Str::startsWith($this->image_path, 'assets/')) {
            return asset($this->image_path);
        }

        // Uploaded files stored using public disk
        $path = Str::replaceFirst('public/', '', $this->image_path);

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset('assets/placeholder.png');
    }
}
