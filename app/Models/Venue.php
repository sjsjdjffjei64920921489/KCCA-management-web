<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'description', 'location', 'address',
        'latitude', 'longitude', 'capacity', 'booking_fee', 'hourly_rate',
        'full_day_rate', 'contact_phone', 'contact_email', 'image', 'images',
        'event_types', 'is_active', 'requires_approval', 'open_time', 'close_time',
    ];

    protected $casts = [
        'images'       => 'array',
        'event_types'  => 'array',
        'is_active'    => 'boolean',
        'requires_approval' => 'boolean',
        'booking_fee'  => 'decimal:2',
        'hourly_rate'  => 'decimal:2',
        'full_day_rate' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function facilities()
    {
        return $this->hasMany(VenueFacility::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('location', 'like', "%{$term}%")
              ->orWhere('address', 'like', "%{$term}%");
        });
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function isAvailableOn(string $date, string $startTime, string $endTime): bool
    {
        return !$this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('event_date', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($inner) use ($startTime, $endTime) {
                    $inner->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
    }

    public function getFormattedBookingFeeAttribute(): string
    {
        return 'UGX ' . number_format($this->booking_fee);
    }

    public function getFormattedHourlyRateAttribute(): string
    {
        return $this->hourly_rate ? 'UGX ' . number_format($this->hourly_rate) . '/hr' : 'N/A';
    }

    public function getFormattedFullDayRateAttribute(): string
    {
        return $this->full_day_rate ? 'UGX ' . number_format($this->full_day_rate) : 'N/A';
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'community_hall'   => 'Community Hall',
            'sports_venue'     => 'Sports Venue',
            'public_venue'     => 'Public Venue',
            'conference_center' => 'Conference Center',
            'outdoor_space'    => 'Outdoor Space',
            default            => ucfirst($this->type),
        };
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('images/venues/' . $this->image))) {
            return asset('images/venues/' . $this->image);
        }
        return '';
    }

    public function hasImage(): bool
    {
        return !empty($this->image) && file_exists(public_path('images/venues/' . $this->image));
    }

    public function getGalleryUrlsAttribute(): array
    {
        $gallery = [];
        $all = $this->images ?? [];

        // Always include the main image first
        if ($this->hasImage()) {
            $gallery[] = asset('images/venues/' . $this->image);
        }

        foreach ($all as $img) {
            $url = asset('images/venues/' . $img);
            if (!in_array($url, $gallery) && file_exists(public_path('images/venues/' . $img))) {
                $gallery[] = $url;
            }
        }

        return $gallery;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($venue) {
            if (empty($venue->slug)) {
                $venue->slug = Str::slug($venue->name);
            }
        });
    }
}
