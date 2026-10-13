<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    private static array $statusBeforeUpdate = [];

    protected static function booted(): void
    {
        static::updating(function (Booking $booking): void {
            if ($booking->isDirty('status')) {
                self::$statusBeforeUpdate[$booking->getKey()] = (string) $booking->getRawOriginal('status');
            }
        });

        static::created(function (Booking $booking): void {
            $booking->recordStatusChange(null, (string) $booking->status, true);
        });

        static::updated(function (Booking $booking): void {
            $previousStatus = self::$statusBeforeUpdate[$booking->getKey()] ?? null;
            if ($previousStatus !== null && $previousStatus !== (string) $booking->status) {
                $booking->recordStatusChange($previousStatus, (string) $booking->status);
            }
            unset(self::$statusBeforeUpdate[$booking->getKey()]);
        });
    }

    protected $fillable = [
        'booking_code',
        'restaurant_id',
        'user_id',
        'customer_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class)->latest('changed_at')->latest('id');
    }

    private function recordStatusChange(?string $from, string $to, bool $initial = false): void
    {
        $actor = auth()->user();
        $isAdmin = $actor?->hasPermission('menu.bookings.manage') ?? false;

        $this->statusHistory()->create([
            'from_status' => $from,
            'to_status' => $to,
            'changed_by' => $actor?->id,
            'changed_by_name' => $actor?->name ?? __('System'),
            'source' => $initial && ! $actor ? 'public' : ($isAdmin ? 'admin' : ($actor ? 'customer' : 'system')),
            'change_type' => $initial ? 'created' : 'status_change',
            'changed_at' => now(),
        ]);
    }
}
