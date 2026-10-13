<?php

use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('operations report filters booking metrics and exports the same period', function () {
    $restaurant = Restaurant::create(['name' => 'Report Branch', 'address' => 'Hanoi', 'status' => true]);
    Booking::create([
        'booking_code' => 'BK-REPORT-1', 'restaurant_id' => $restaurant->id, 'customer_name' => 'Report Guest',
        'phone' => '0900000000', 'email' => 'report@example.com', 'booking_date' => '2026-10-13',
        'booking_time' => '19:00', 'number_of_guests' => 4, 'status' => 'confirmed', 'table_codes' => ['B1'],
    ]);
    Booking::create([
        'booking_code' => 'BK-REPORT-2', 'restaurant_id' => $restaurant->id, 'customer_name' => 'Cancelled Guest',
        'phone' => '0900000001', 'email' => null, 'booking_date' => '2026-10-13',
        'booking_time' => '19:30', 'number_of_guests' => 2, 'status' => 'cancelled', 'table_codes' => ['B1'],
    ]);
    $admin = User::factory()->create();

    $this->actingAs($admin)->get(route('admin.reports.operations', ['from' => '2026-10-13', 'to' => '2026-10-13', 'restaurant_id' => $restaurant->id]))
        ->assertOk()->assertSee('Report Branch')->assertSee('Bàn được chọn nhiều');
    $this->actingAs($admin)->get(route('admin.reports.operations.export', ['from' => '2026-10-13', 'to' => '2026-10-13', 'restaurant_id' => $restaurant->id]))
        ->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
});
