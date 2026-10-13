<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'email_normalized', 'phone', 'phone_normalized',
        'notes', 'source', 'last_contact_at', 'status',
    ];

    protected $casts = ['last_contact_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
