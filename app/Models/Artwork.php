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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'image_blob',
        'image_mime',
    ];

    protected $appends = ['image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function getImageUrlAttribute(): string
{
    // Prefer computed flag if query provided it
    $hasBlob = false;

    if (array_key_exists('has_image_blob', $this->attributes)) {
        $hasBlob = (bool) $this->attributes['has_image_blob'];
    } else {
        // fallback (only if blob was actually selected)
        $hasBlob = !empty($this->image_blob);
    }

    if ($hasBlob) {
        return route('artwork.image', ['id' => $this->id]);
    }

    if (!$this->image_path) {
        return asset('assets/placeholder.png');
    }

    if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
        return $this->image_path;
    }

    if (!Str::contains($this->image_path, '/')) {
        return asset('assets/artworks/' . $this->image_path);
    }

    if (Str::startsWith($this->image_path, 'assets/')) {
        return asset($this->image_path);
    }

    $path = Str::replaceFirst('public/', '', $this->image_path);

    if (Storage::disk('public')->exists($path)) {
        return Storage::url($path);
    }

    return asset('assets/placeholder.png');
}


}
