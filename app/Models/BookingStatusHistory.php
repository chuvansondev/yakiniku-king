<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['from_status', 'to_status', 'changed_by', 'changed_by_name', 'source', 'change_type', 'changed_at'];

    protected $casts = ['changed_at' => 'datetime'];

    public function getFromStatusLabelAttribute(): ?string
    {
        return $this->from_status ? BookingStatus::from($this->from_status)->label() : null;
    }

    public function getToStatusLabelAttribute(): string
    {
        return BookingStatus::from($this->to_status)->label();
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
