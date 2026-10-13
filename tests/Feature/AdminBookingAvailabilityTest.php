<?php

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\User;
use App\Services\BookingService;
use App\Services\CustomerService;
use Illuminate\Validation\ValidationException;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function adminBookingFixture(): array
{
    $restaurant = Restaurant::create(['name' => 'Admin booking branch', 'address' => 'Hanoi', 'status' => true]);
    RestaurantTable::create(['restaurant_id' => $restaurant->id, 'code' => 'A1', 'floor' => 1, 'capacity' => 4, 'status' => true]);
    RestaurantTable::create(['restaurant_id' => $restaurant->id, 'code' => 'A2', 'floor' => 1, 'capacity' => 2, 'status' => true]);

    return [$restaurant, User::factory()->create()];
}

function adminBookingData(Restaurant $restaurant, array $overrides = []): array
{
    return array_merge([
        'restaurant_id' => $restaurant->id,
        'floor' => 1,
        'table_codes' => ['A1'],
        'customer_name' => 'Admin entered booking',
        'phone' => '0901234567',
        'email' => null,
        'booking_date' => now()->addDay()->toDateString(),
        'booking_time' => '19:00',
        'number_of_guests' => 4,
        'note' => null,
        'status' => BookingStatus::Pending->value,
    ], $overrides);
}

test('admin booking creation uses configured capacity and stores selected tables', function () {
    [$restaurant, $user] = adminBookingFixture();

    $this->actingAs($user);
    $booking = app(BookingService::class)->createAdminBooking(adminBookingData($restaurant), app(CustomerService::class));

    expect($booking->table_codes)->toBe(['A1']);
    expect($booking->qr_code)->toBe($booking->booking_code);
});

test('admin booking creation rejects overlapping reservations for the same table', function () {
    [$restaurant, $user] = adminBookingFixture();
    $this->actingAs($user);
    app(BookingService::class)->createAdminBooking(adminBookingData($restaurant), app(CustomerService::class));

    expect(fn () => app(BookingService::class)->createAdminBooking(adminBookingData($restaurant, [
        'booking_time' => '20:00',
    ]), app(CustomerService::class)))->toThrow(ValidationException::class);
});

test('admin booking updates exclude the current booking but reject conflicts with another booking', function () {
    [$restaurant, $user] = adminBookingFixture();
    $this->actingAs($user);
    $service = app(BookingService::class);
    $customers = app(CustomerService::class);
    $booking = $service->createAdminBooking(adminBookingData($restaurant), $customers);

    expect($service->updateAdminBooking($booking, adminBookingData($restaurant, ['customer_name' => 'Updated name']), $customers))->toBeTrue();

    Booking::create([
        ...adminBookingData($restaurant, ['table_codes' => ['A2'], 'number_of_guests' => 2, 'booking_time' => '23:00']),
        'booking_code' => 'BK-SECOND-ADMIN',
    ]);
    expect(fn () => $service->updateAdminBooking($booking, adminBookingData($restaurant, ['table_codes' => ['A2'], 'number_of_guests' => 2, 'booking_time' => '23:30']), $customers))->toThrow(ValidationException::class);
});
