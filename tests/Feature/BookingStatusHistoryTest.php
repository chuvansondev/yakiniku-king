<?php

use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('booking status history records creation and actual status transitions with the actor', function () {
    $restaurant = Restaurant::create(['name' => 'History Branch', 'address' => 'Hanoi', 'status' => true]);
    $admin = User::factory()->create();
    $booking = Booking::create([
        'booking_code' => 'BK-STATUS-HISTORY', 'restaurant_id' => $restaurant->id, 'customer_name' => 'History Guest',
        'phone' => '0900000000', 'booking_date' => now()->addDay()->toDateString(), 'booking_time' => '19:00',
        'number_of_guests' => 2, 'status' => 'pending',
    ]);

    expect($booking->statusHistory()->count())->toBe(1);
    expect($booking->statusHistory()->first()->change_type)->toBe('created');

    $this->actingAs($admin);
    $booking->update(['status' => 'confirmed']);
    $booking->update(['status' => 'confirmed']);
    $booking->update(['status' => 'completed']);

    $history = BookingStatusHistory::query()->where('booking_id', $booking->id)->orderBy('id')->get();
    expect($history)->toHaveCount(3);
    expect($history[1]->from_status)->toBe('pending')
        ->and($history[1]->to_status)->toBe('confirmed')
        ->and($history[1]->changed_by)->toBe($admin->id)
        ->and($history[1]->source)->toBe('admin');
    expect($history[2]->from_status)->toBe('confirmed')->and($history[2]->to_status)->toBe('completed');

    $this->get(route('admin.menu.bookings.edit', $booking))
        ->assertOk()
        ->assertSee('Đã xác nhận')
        ->assertSee('Đã hoàn thành');
});

