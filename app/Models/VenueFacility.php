<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueFacility extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'name', 'icon', 'description'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
