<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'create_by',
        'update_by',
    ];

    protected static function booted(): void
    {
        static::saving(function (Permission $permission): void {
            if (blank($permission->slug) && filled($permission->name)) {
                $permission->slug = Str::slug($permission->name, '.');
            }
        });
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')
            ->withPivot(['create_by', 'update_by'])
            ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
