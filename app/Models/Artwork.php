use Illuminate\Support\Facades\Storage;

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

        // If already a full URL (Cloudinary / S3)
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return Storage::url($this->image_path);
    }
}
