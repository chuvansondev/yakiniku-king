<?php

namespace App\Models;

use Database\Factories\KidsItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\TracksDeletion;

class KidsItem extends Model
{
    /** @use HasFactory<KidsItemFactory> */
    use HasFactory;
    use SoftDeletes, TracksDeletion;

    public const DELETED_AT = 'delete_at';

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'type',
        'food_category',
        'description',
        'description_en',
        'image',
        'price',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];
}
