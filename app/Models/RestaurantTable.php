<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantTable extends Model
{
    protected $fillable = ['restaurant_id', 'code', 'floor', 'capacity', 'position', 'status'];

    protected $casts = ['floor' => 'integer', 'capacity' => 'integer', 'position' => 'integer', 'status' => 'boolean'];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
