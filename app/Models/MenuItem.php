<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\TracksDeletion;

class MenuItem extends Model
{
    use SoftDeletes, TracksDeletion;

    public const DELETED_AT = 'delete_at';

    protected $fillable = [
        'category_id',
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'image',
        'price',
        'is_must_try',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_must_try' => 'boolean',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            MenuCategory::class,
            'category_id'
        );
    }

    public function combos(): BelongsToMany
    {
        return $this->belongsToMany(
            Combo::class,
            'combo_items'
        )->withPivot('quantity');
    }
}
