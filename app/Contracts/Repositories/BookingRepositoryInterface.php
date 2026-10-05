<?php

namespace App\Contracts\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

interface BookingRepositoryInterface
{
    public function create(array $attributes): Booking;

    public function findWithRestaurant(int $id): ?Booking;

    public function findOrFail(int $id): Booking;

    public function bookingCodeExists(string $code): bool;

    public function activePreOrderItems(array $ids): Collection;

    public function activeRestaurants(): Collection;

    public function mustTryMenuItems(): Collection;

    public function updateStatus(Booking $booking, string $status): void;
}
