<?php

namespace App\Repositories;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository implements BookingRepositoryInterface
{
    public function create(array $attributes): Booking
    {
        return Booking::query()->create($attributes);
    }

    public function findWithRestaurant(int $id): ?Booking
    {
        return Booking::query()->with('restaurant')->find($id);
    }

    public function findOrFail(int $id): Booking
    {
        return Booking::query()->findOrFail($id);
    }

    public function bookingCodeExists(string $code): bool
    {
        return Booking::query()->where('booking_code', $code)->exists();
    }

    public function activePreOrderItems(array $ids): Collection
    {
        return MenuItem::query()->whereIn('id', $ids)->where('status', true)->where('is_must_try', true)
            ->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'name_en']);
    }

    public function activeRestaurants(): Collection
    {
        return Restaurant::query()->where('status', true)->orderBy('name')->get(['id', 'name', 'name_en']);
    }

    public function mustTryMenuItems(): Collection
    {
        return MenuItem::query()->where('status', true)->where('is_must_try', true)
            ->orderBy('sort_order')->orderBy('name')
            ->get(['id', 'name', 'name_en', 'description', 'description_en', 'image', 'price']);
    }

    public function updateStatus(Booking $booking, string $status): void
    {
        $booking->update(['status' => $status]);
    }
}
