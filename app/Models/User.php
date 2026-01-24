<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'profile_image_blob',
        'profile_image_mime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'profile_image_blob',
        'profile_image_mime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'profile_image_url',
        'has_profile_image_blob',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function artworks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Artwork::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function bankDetail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BankDetail::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isArtist(): bool
    {
        return $this->role === 'artist';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function getProfileImageUrlAttribute(): string
    {
        if ($this->has_profile_image_blob) {
            return route('user.profile-image', ['id' => $this->id]);
        }

        if ($this->profile_photo_path) {
            if (str_starts_with($this->profile_photo_path, 'http')) {
                return $this->profile_photo_path;
            }
            return asset('storage/' . $this->profile_photo_path);
        }

        return asset('assets/default-avatar.png');
    }

    public function getHasProfileImageBlobAttribute(): bool
    {
        if (array_key_exists('has_profile_image_blob', $this->attributes)) {
            return (bool) $this->attributes['has_profile_image_blob'];
        }
        return !empty($this->profile_image_blob);
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->getProfileImageUrlAttribute();
    }

    public function deleteProfilePhoto()
    {
        if ($this->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);
        }

        $this->forceFill([
            'profile_photo_path' => null,
            'profile_image_blob' => null,
            'profile_image_mime' => null,
        ])->save();
    }
}
