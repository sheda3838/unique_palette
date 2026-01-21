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
        'status',
    ];

    protected $appends = ['image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): string
{
    if (!$this->image_path) {
        return asset('assets/placeholder.png');
    }

    // If already a full URL (Cloudinary/S3/etc)
    if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
        return $this->image_path;
    }

    // ✅ If it's your seeded/static images folder (public/assets/...)
    // Example in DB: "Artworks/a1.jpg" or "artworks/a1.jpg"
    if (Str::startsWith(strtolower($this->image_path), ['artworks/'])) {
        return asset('assets/' . $this->image_path); 
        // public/assets/Artworks/a1.jpg (keep your DB case)
    }

    // ✅ Otherwise treat it as uploaded file stored in storage/app/public
    // Example in DB: "uploads/..." or "artworks/abc.png"
    return Storage::disk('public')->url($this->image_path); // -> /storage/...
}

}
