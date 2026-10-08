<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Contracts\Repositories\BookingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingService
{
    private const TABLE_CAPACITIES = ['B1' => 4, 'B2' => 4, 'B3' => 6, 'B4' => 2, 'B5' => 2, 'B6' => 4];

    public function __construct(private readonly BookingRepositoryInterface $bookings) {}

    public function tableCapacities(): array
    {
        return self::TABLE_CAPACITIES;
    }

    public function findWithRestaurant(int $id): ?Booking
    {
        return $this->bookings->findWithRestaurant($id);
    }

    public function find(int $id): Booking
    {
        return $this->bookings->findOrFail($id);
    }

    public function formRestaurants(): Collection
    {
        return $this->bookings->activeRestaurants();
    }

    public function mustTryMenuItems(): Collection
    {
        return $this->bookings->mustTryMenuItems();
    }

    public function createAdminBooking(array $attributes): Booking
    {
        $attributes['booking_code'] = $this->generateAdminBookingCode();
        $attributes['create_by'] = Auth::id();
        return $this->bookings->create($attributes);
    }

    private function generateAdminBookingCode(): string
    {
        do {
            $code = 'BK'.now()->format('YmdHis').random_int(10, 99);
        } while ($this->bookings->bookingCodeExists($code));

        return $code;
    }

    public function tableSelectionError(int $partySize, array $tableCodes): ?string
    {
        $capacities = self::TABLE_CAPACITIES;
        $maximumFloorCapacity = array_sum($capacities);

        if ($partySize <= $maximumFloorCapacity && $tableCodes === []) {
            return __('Please select enough tables for your party size.');
        }

        $tableCapacity = array_sum(array_map(fn (string $code): int => $capacities[$code], $tableCodes));

        if ($tableCodes !== [] && $tableCapacity < $partySize) {
            return __('The selected tables do not have enough seats for your party.');
        }

        return null;
    }

    public function create(array $validated): Booking
    {
        $tableCodes = $validated['table_codes'] ?? [];
        $preOrderItems = $this->bookings->activePreOrderItems($validated['pre_order_items'] ?? [])
            ->map(function ($menuItem): array {
                $item = ['id' => $menuItem->id, 'name' => $menuItem->name];
                if (filled($menuItem->name_en)) $item['name_en'] = $menuItem->name_en;
                return $item;
            })->all();

        unset($validated['pre_order_items'], $validated['table_codes']);
        $bookingCode = $this->generateBookingCode();

        return $this->bookings->create([
            ...$validated,
            'table_codes' => $tableCodes ?: null,
            'pre_order_items' => $preOrderItems ?: null,
            'booking_code' => $bookingCode,
            'qr_code' => $bookingCode,
            'status' => BookingStatus::Pending->value,
            'create_by' => Auth::id(),
        ]);
    }

    public function cancelIfAllowed(Booking $booking): void
    {
        if (in_array($booking->status, [BookingStatus::Pending->value, BookingStatus::Confirmed->value], true)) {
            $this->bookings->updateStatus($booking, BookingStatus::Cancelled->value);
        }
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BK'.now()->format('YmdHis').Str::upper(Str::random(4));
        } while ($this->bookings->bookingCodeExists($code));

        return $code;
    }
}
