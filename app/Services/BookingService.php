<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\Customer;
use App\Contracts\Repositories\BookingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(private readonly BookingRepositoryInterface $bookings) {}

    public function tablesForRestaurant(?int $restaurantId, bool $activeOnly = true): Collection
    {
        if (! $restaurantId) return new Collection;

        return Restaurant::query()->find($restaurantId)?->tables()
            ->when($activeOnly, fn ($query) => $query->where('status', true))
            ->get() ?? new Collection;
    }

    public function tableCapacities(?int $restaurantId): array
    {
        return $this->tablesForRestaurant($restaurantId)->pluck('capacity', 'code')->all();
    }

    public function tableFloors(?int $restaurantId): array
    {
        return $this->tablesForRestaurant($restaurantId)->pluck('floor', 'code')->all();
    }

    public function unavailableTables(?int $restaurantId, ?string $date, ?string $time, ?int $excludeBookingId = null): array
    {
        if (! $restaurantId || ! $date || ! $time) {
            return [];
        }

        $start = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$time)->subMinutes(90);

        return Booking::query()
            ->where('restaurant_id', $restaurantId)
            ->whereNotIn('status', [BookingStatus::Cancelled->value])
            ->when($excludeBookingId, fn ($query) => $query->whereKeyNot($excludeBookingId))
            ->get(['booking_date', 'booking_time', 'table_codes'])
            ->filter(function (Booking $booking) use ($start): bool {
                $bookingDate = Carbon::parse($booking->getRawOriginal('booking_date'))->toDateString();
                $bookingTime = substr((string) $booking->getRawOriginal('booking_time'), 0, 8);
                $existingStart = Carbon::parse($bookingDate.' '.$bookingTime);
                $existingEnd = $existingStart->copy()->addMinutes(180);

                return $existingStart->lt($start->copy()->addMinutes(180)) && $existingEnd->gt($start);
            })
            ->flatMap(fn (Booking $booking) => $booking->table_codes ?? [])
            ->unique()
            ->values()
            ->all();
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

    public function createAdminBooking(array $attributes, CustomerService $customers): Booking
    {
        $this->validateTableAssignment($attributes);
        $attributes['booking_code'] = $this->generateAdminBookingCode();
        $attributes['create_by'] = Auth::id();
        $attributes['qr_code'] = $attributes['booking_code'];
        $customer = $customers->resolve($attributes['customer_name'], $attributes['email'] ?? null, $attributes['phone'] ?? null, null, 'booking');
        $attributes['customer_id'] = $customer->id;
        return $this->bookings->create($attributes);
    }

    public function updateAdminBooking(Booking $booking, array $attributes, CustomerService $customers): bool
    {
        $this->validateTableAssignment($attributes, $booking->id);
        $attributes['update_by'] = Auth::id();
        $customer = $customers->resolve($attributes['customer_name'], $attributes['email'] ?? null, $attributes['phone'] ?? null, null, 'booking');
        $attributes['customer_id'] = $customer->id;

        return $booking->update($attributes);
    }

    public function validateTableAssignment(array $attributes, ?int $excludeBookingId = null): void
    {
        $restaurantId = (int) $attributes['restaurant_id'];
        $tableCodes = array_values($attributes['table_codes'] ?? []);
        $floor = (int) $attributes['floor'];
        $isCancelled = ($attributes['status'] ?? null) === BookingStatus::Cancelled->value;

        if ($isCancelled) return;

        if ($error = $this->tableSelectionError((int) $attributes['number_of_guests'], $tableCodes, $floor, $restaurantId)) {
            throw ValidationException::withMessages(['table_codes' => $error]);
        }

        if (array_intersect($tableCodes, $this->unavailableTables(
            $restaurantId,
            $attributes['booking_date'],
            $attributes['booking_time'],
            $excludeBookingId,
        ))) {
            throw ValidationException::withMessages([
                'table_codes' => __('One or more selected tables are already booked for this time. Please choose another table.'),
            ]);
        }
    }

    private function generateAdminBookingCode(): string
    {
        do {
            $code = 'BK'.now()->format('YmdHis').random_int(10, 99);
        } while ($this->bookings->bookingCodeExists($code));

        return $code;
    }

    public function tableSelectionError(int $partySize, array $tableCodes, ?int $floor = null, ?int $restaurantId = null): ?string
    {
        $tables = $this->tablesForRestaurant($restaurantId);
        $capacities = $tables->pluck('capacity', 'code')->all();
        $floors = $tables->pluck('floor', 'code')->all();
        $floorCapacities = $tables->groupBy('floor')->map(fn ($floorTables) => $floorTables->sum('capacity'));
        $maximumFloorCapacity = (int) ($floorCapacities->max() ?? 0);

        if ($partySize <= $maximumFloorCapacity && $tableCodes === []) {
            return __('Please select enough tables for your party size.');
        }

        if ($floor !== null && collect($tableCodes)->contains(fn (string $code): bool => ! isset($floors[$code]) || (int) $floors[$code] !== (int) $floor)) {
            return __('Please select tables on the chosen floor.');
        }

        $tableCapacity = array_sum(array_map(fn (string $code): int => (int) ($capacities[$code] ?? 0), $tableCodes));

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
        $customer = app(CustomerService::class)->resolve(
            $validated['customer_name'], $validated['email'] ?? null, $validated['phone'] ?? null, Auth::id(), 'booking',
        );

        return $this->bookings->create([
            ...$validated,
            'table_codes' => $tableCodes ?: null,
            'pre_order_items' => $preOrderItems ?: null,
            'booking_code' => $bookingCode,
            'qr_code' => $bookingCode,
            'status' => BookingStatus::Pending->value,
            'create_by' => Auth::id(),
            'user_id' => Auth::id(),
            'customer_id' => $customer->id,
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
