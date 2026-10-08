<?php

use App\Models\Booking;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Mail\BookingConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('the home booking buttons open the public booking form', function () {
    $response = $this->get(route('home'));

    $response->assertSee('href="'.route('booking.create').'"', false);

    expect(substr_count(
        $response->getContent(),
        'class="d-block" href="'.route('menu.index').'"',
    ))->toBe(6);
});

test('the booking form lists active restaurants', function () {
    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King - Hà Nội',
        'address' => 'Hà Nội',
        'status' => true,
    ]);
    $menuCategory = MenuCategory::create([
        'name' => 'Thịt bò',
        'slug' => 'thit-bo',
        'status' => true,
    ]);
    $menuItem = MenuItem::create([
        'category_id' => $menuCategory->id,
        'name' => 'Wagyu Must Try',
        'slug' => 'wagyu-must-try',
        'price' => 299000,
        'is_must_try' => true,
        'status' => true,
    ]);

    $this->get(route('booking.create'))
        ->assertOk()
        ->assertSee('Đặt bàn')
        ->assertSee($restaurant->name)
        ->assertSee($menuItem->name)
        ->assertSee('Gọi món trước')
        ->assertSee('name="pre_order_items[]"', false)
        ->assertSee('name="floor"', false)
        ->assertSee('Tầng 1')
        ->assertSee('name="table_codes[]"', false)
        ->assertSee('6 người')
        ->assertSee('Thời gian giữ bàn tối đa 10 phút')
        ->assertSee('Điều khoản và lưu ý đặt bàn');
});

test('a visitor can submit a booking request', function () {
    Mail::fake();
    Setting::create(['key' => 'email', 'value' => 'admin@example.com']);

    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King - Hà Nội',
        'address' => 'Hà Nội',
        'status' => true,
    ]);

    $menuCategory = MenuCategory::create([
        'name' => 'Thịt bò',
        'slug' => 'thit-bo',
        'status' => true,
    ]);
    $menuItem = MenuItem::create([
        'category_id' => $menuCategory->id,
        'name' => 'Wagyu đặc biệt',
        'slug' => 'wagyu-dac-biet',
        'price' => 299000,
        'is_must_try' => true,
        'status' => true,
    ]);

    $bookingDate = now()->addDay()->toDateString();

    $this->post(route('booking.store'), [
        'restaurant_id' => $restaurant->id,
        'customer_name' => 'Nguyen Van A',
        'phone' => '0901234567',
        'email' => 'customer@example.com',
        'floor' => 1,
        'booking_date' => $bookingDate,
        'booking_time' => '19:00',
        'number_of_guests' => 2,
        'note' => 'Bàn gần cửa sổ',
        'table_codes' => ['B4'],
        'pre_order_items' => [$menuItem->id],
    ])
        ->assertRedirect(route('booking.create'))
        ->assertSessionHas('booking_success')
        ->assertSessionHas('booking_code');

    $this->assertDatabaseHas('bookings', [
        'restaurant_id' => $restaurant->id,
        'customer_name' => 'Nguyen Van A',
        'phone' => '0901234567',
        'number_of_guests' => 2,
        'status' => 'pending',
        'note' => 'Bàn gần cửa sổ',
    ]);

    $booking = Booking::query()
        ->where('customer_name', 'Nguyen Van A')
        ->firstOrFail();

    Mail::assertSent(BookingConfirmation::class, fn (BookingConfirmation $mail) => $mail->hasTo('customer@example.com') && ! $mail->isAdmin);
    Mail::assertSent(BookingConfirmation::class, fn (BookingConfirmation $mail) => $mail->hasTo('admin@example.com') && $mail->isAdmin);

    expect($booking->booking_date->toDateString())->toBe($bookingDate);
    expect($booking->booking_time)->toBe('19:00');
    expect($booking->floor)->toBe(1);
    expect($booking->table_codes)->toBe(['B4']);
    expect($booking->qr_code)->toBe($booking->booking_code);
    expect($booking->pre_order_items)->toBe([
        ['id' => $menuItem->id, 'name' => 'Wagyu đặc biệt'],
    ]);

    $this->get(route('booking.create'))
        ->assertSee('id="bookingConfirmationModal"', false)
        ->assertSee('Chưa xác nhận')
        ->assertSee('Nguyen Van A')
        ->assertSee('Tầng 1')
        ->assertSee('Bàn')
        ->assertSee('B4')
        ->assertSee('Bàn gần cửa sổ')
        ->assertSee('Wagyu đặc biệt')
        ->assertSee('Ẩn thông tin')
        ->assertSee('Hủy đặt bàn')
        ->assertSee('Trở về trang chủ');

    $this->get(route('home'));

    $this->get(route('booking.create'))
        ->assertDontSee('id="bookingConfirmationModal"', false)
        ->assertDontSee('Nguyen Van A')
        ->assertSee('value="2"', false);
});

test('a booking sends both customer and admin emails when their addresses match', function () {
    Mail::fake();
    Setting::create(['key' => 'email', 'value' => 'same@example.com']);

    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King',
        'address' => 'Ho Chi Minh City',
        'status' => true,
    ]);

    $this->post(route('booking.store'), [
        'restaurant_id' => $restaurant->id,
        'customer_name' => 'Nguyen Van A',
        'phone' => '0901234567',
        'email' => 'same@example.com',
        'floor' => 1,
        'booking_date' => now()->addDay()->toDateString(),
        'booking_time' => '19:00',
        'number_of_guests' => 2,
        'table_codes' => ['B4'],
    ])->assertSessionHas('booking_success');

    Mail::assertSent(BookingConfirmation::class, 2);
    Mail::assertSent(BookingConfirmation::class, fn (BookingConfirmation $mail) => $mail->hasTo('same@example.com') && ! $mail->isAdmin);
    Mail::assertSent(BookingConfirmation::class, fn (BookingConfirmation $mail) => $mail->hasTo('same@example.com') && $mail->isAdmin);
});

test('a visitor can cancel the booking in their session', function () {
    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King - Hà Nội',
        'address' => 'Hà Nội',
        'status' => true,
    ]);

    $this->post(route('booking.store'), [
        'restaurant_id' => $restaurant->id,
        'floor' => 1,
        'customer_name' => 'Nguyen Van A',
        'phone' => '0901234567',
        'booking_date' => now()->addDay()->toDateString(),
        'booking_time' => '19:00',
        'number_of_guests' => 2,
        'table_codes' => ['B4'],
    ]);

    $booking = Booking::query()->where('customer_name', 'Nguyen Van A')->firstOrFail();

    $this->post(route('booking.cancel'))
        ->assertRedirect(route('booking.create'))
        ->assertSessionHas('booking_cancelled');

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'cancelled',
    ]);

    $this->get(route('booking.create'))
        ->assertDontSee('id="bookingConfirmationModal"', false);
});

test('a visitor cannot cancel a booking from another session', function () {
    $this->post(route('booking.cancel'))
        ->assertNotFound();
});

test('invalid booking details are rejected without creating a booking', function () {
    $this->from(route('booking.create'))
        ->post(route('booking.store'), [])
        ->assertRedirect(route('booking.create'))
        ->assertSessionHasErrors([
            'restaurant_id',
            'floor',
            'customer_name',
            'phone',
            'booking_date',
            'booking_time',
            'number_of_guests',
        ]);

    $this->assertDatabaseCount('bookings', 0);
});

test('inactive restaurants cannot receive public bookings', function () {
    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King - Ngừng hoạt động',
        'address' => 'Hà Nội',
        'status' => false,
    ]);

    $this->from(route('booking.create'))
        ->post(route('booking.store'), [
            'restaurant_id' => $restaurant->id,
            'floor' => 1,
            'customer_name' => 'Nguyen Van A',
            'phone' => '0901234567',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '19:00',
            'number_of_guests' => 2,
            'table_codes' => ['B4'],
        ])
        ->assertRedirect(route('booking.create'))
        ->assertSessionHasErrors(['restaurant_id']);

    $this->assertDatabaseCount('bookings', 0);
});

test('visitors cannot pre-order menu items outside the active must-try menu', function () {
    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King - Hà Nội',
        'address' => 'Hà Nội',
        'status' => true,
    ]);
    $menuCategory = MenuCategory::create([
        'name' => 'Thịt bò',
        'slug' => 'thit-bo',
        'status' => true,
    ]);
    $menuItem = MenuItem::create([
        'category_id' => $menuCategory->id,
        'name' => 'Món không phải Must Try',
        'slug' => 'mon-khong-must-try',
        'price' => 120000,
        'is_must_try' => false,
        'status' => true,
    ]);

    $this->from(route('booking.create'))
        ->post(route('booking.store'), [
            'restaurant_id' => $restaurant->id,
            'customer_name' => 'Nguyen Van A',
            'phone' => '0901234567',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '19:00',
            'number_of_guests' => 2,
            'floor' => 1,
            'table_codes' => ['B4'],
            'pre_order_items' => [$menuItem->id],
        ])
        ->assertRedirect(route('booking.create'))
        ->assertSessionHasErrors(['pre_order_items.0']);

    $this->assertDatabaseCount('bookings', 0);
});

test('a visitor can select multiple tables to fit a larger party', function () {
    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King - Hà Nội',
        'address' => 'Hà Nội',
        'status' => true,
    ]);

    $this->post(route('booking.store'), [
        'restaurant_id' => $restaurant->id,
        'floor' => 2,
        'customer_name' => 'Nguyen Van A',
        'phone' => '0901234567',
        'booking_date' => now()->addDay()->toDateString(),
        'booking_time' => '19:00',
        'number_of_guests' => 7,
        'table_codes' => ['B1', 'B3'],
    ])
        ->assertRedirect(route('booking.create'))
        ->assertSessionHas('booking_success');

    $booking = Booking::query()->where('customer_name', 'Nguyen Van A')->firstOrFail();

    expect($booking->floor)->toBe(2);
    expect($booking->table_codes)->toBe(['B1', 'B3']);
});

test('a visitor cannot select tables with insufficient capacity', function () {
    $restaurant = Restaurant::create([
        'name' => 'Yakiniku King - Hà Nội',
        'address' => 'Hà Nội',
        'status' => true,
    ]);

    $this->from(route('booking.create'))
        ->post(route('booking.store'), [
            'restaurant_id' => $restaurant->id,
            'floor' => 1,
            'customer_name' => 'Nguyen Van A',
            'phone' => '0901234567',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '19:00',
            'number_of_guests' => 3,
            'table_codes' => ['B4'],
        ])
        ->assertRedirect(route('booking.create'))
        ->assertSessionHasErrors(['table_codes']);

    $this->assertDatabaseCount('bookings', 0);
});
