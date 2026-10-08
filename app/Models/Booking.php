<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'restaurant_id',
        'create_by',
        'update_by',
        'floor',
        'table_codes',
        'customer_name',
        'phone',
        'email',
        'booking_date',
        'booking_time',
        'number_of_guests',
        'note',
        'pre_order_items',
        'status',
        'qr_code',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'floor' => 'integer',
        'table_codes' => 'array',
        'pre_order_items' => 'array',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
