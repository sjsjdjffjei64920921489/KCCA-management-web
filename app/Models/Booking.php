<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number', 'venue_id', 'user_id', 'event_type',
        'event_date', 'start_time', 'end_time', 'expected_guests',
        'special_requirements', 'contact_name', 'contact_phone',
        'contact_email', 'organization', 'total_amount', 'status',
        'admin_notes', 'confirmed_at', 'cancelled_at', 'cancellation_reason',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
                     ->where('event_date', '>=', now()->toDateString());
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'badge-warning',
            'confirmed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'completed' => 'badge-secondary',
            default     => 'badge-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'UGX ' . number_format($this->total_amount);
    }

    public function getDurationHoursAttribute(): float
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);
        return round($end->diffInMinutes($start) / 60, 1);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'])
            && $this->event_date->isFuture();
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'KCCA-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (self::where('reference_number', $ref)->exists());

        return $ref;
    }

    public function calculateTotal(): float
    {
        $venue = $this->venue;
        $hours = $this->duration_hours;

        if ($venue->hourly_rate) {
            return round($venue->hourly_rate * $hours, 2);
        }

        if ($hours >= 8 && $venue->full_day_rate) {
            return (float) $venue->full_day_rate;
        }

        return (float) $venue->booking_fee;
    }
}
