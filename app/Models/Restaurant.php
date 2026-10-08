<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\TracksDeletion;

class Restaurant extends Model
{
    use SoftDeletes, TracksDeletion;

    public const DELETED_AT = 'delete_at';

    protected $fillable = [
        'name',
        'name_en',
        'address',
        'address_en',
        'phone',
        'latitude',
        'longitude',
        'google_map_url',
        'opening_time',
        'closing_time',
        'image',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'status' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
