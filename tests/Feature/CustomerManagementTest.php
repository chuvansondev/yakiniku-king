<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('bookings and lead requests with the same contact share a customer profile', function () {
    Mail::fake();
    $restaurant = Restaurant::create(['name' => 'CRM Branch', 'address' => 'Hanoi', 'status' => true]);
    RestaurantTable::create(['restaurant_id' => $restaurant->id, 'code' => 'A1', 'floor' => 1, 'capacity' => 4, 'status' => true]);

    $this->post(route('booking.store'), [
        'restaurant_id' => $restaurant->id,
        'customer_name' => 'Nguyen Van A',
        'phone' => '+84 901-234-567',
        'email' => 'crm@example.com',
        'floor' => 1,
        'booking_date' => now()->addDay()->toDateString(),
        'booking_time' => '19:00',
        'number_of_guests' => 2,
        'table_codes' => ['A1'],
    ])->assertRedirect(route('booking.create'));

    app(LeadService::class)->register([
        'salutation' => 'Ông',
        'name' => 'Nguyen A',
        'phone' => '0901234567',
        'email' => 'CRM@example.com',
    ]);

    expect(Customer::query()->count())->toBe(1);
    expect(Booking::query()->firstOrFail()->customer_id)->toBe(Lead::query()->firstOrFail()->customer_id);
});

test('customer management lists profiles and shows editable interaction history', function () {
    $restaurant = Restaurant::create(['name' => 'History Branch', 'address' => 'Hanoi', 'status' => true]);
    $customer = Customer::create([
        'name' => 'Customer History', 'email' => 'history@example.com', 'email_normalized' => 'history@example.com',
        'phone' => '0900000000', 'phone_normalized' => '0900000000', 'source' => 'booking',
        'last_contact_at' => now(), 'status' => 'active',
    ]);
    Booking::create([
        'customer_id' => $customer->id, 'booking_code' => 'BK-CUSTOMER-HISTORY', 'restaurant_id' => $restaurant->id,
        'customer_name' => $customer->name, 'phone' => $customer->phone, 'email' => $customer->email,
        'booking_date' => now()->addDay()->toDateString(), 'booking_time' => '19:00', 'number_of_guests' => 2, 'status' => 'confirmed',
    ]);
    Lead::create(['customer_id' => $customer->id, 'name' => $customer->name, 'phone' => $customer->phone, 'email' => $customer->email, 'message' => 'Please call me', 'status' => 'contacted']);
    $admin = User::factory()->create();

    $this->actingAs($admin)->get(route('admin.customers.index'))
        ->assertOk()->assertSee('Customer History')->assertSee('history@example.com');
    $this->actingAs($admin)->get(route('admin.customers.show', $customer))
        ->assertOk()->assertSee('BK-CUSTOMER-HISTORY')->assertSee('Please call me');
    $this->actingAs($admin)->put(route('admin.customers.update', $customer), [
        'name' => 'Updated customer', 'phone' => '0900000000', 'email' => 'history@example.com',
        'notes' => 'Prefers window seating.', 'status' => 'active',
    ])->assertRedirect(route('admin.customers.show', $customer));

    expect($customer->fresh()->notes)->toBe('Prefers window seating.');
});
