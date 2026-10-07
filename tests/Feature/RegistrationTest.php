<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('a visitor can register and is signed in as a non-admin customer', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password_confirmation"', false);

    $response = $this->post(route('register.submit'), [
        'name' => 'Test Customer',
        'email' => 'customer@example.com',
        'password' => 'safe-password-123',
        'password_confirmation' => 'safe-password-123',
        'is_admin' => true,
    ]);

    $user = User::query()->where('email', 'customer@example.com')->firstOrFail();

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
    expect($user->is_admin)->toBeFalse();
    expect(Hash::check('safe-password-123', $user->getRawOriginal('password')))->toBeTrue();
    $this->get(route('admin.dashboard'))->assertForbidden();
});

test('registration rejects a duplicate email and mismatched passwords', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->from(route('register'))
        ->post(route('register.submit'), [
            'name' => 'Another Customer',
            'email' => 'taken@example.com',
            'password' => 'safe-password-123',
            'password_confirmation' => 'safe-password-123',
        ])
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors('email');

    $this->from(route('register'))
        ->post(route('register.submit'), [
            'name' => 'New Customer',
            'email' => 'new@example.com',
            'password' => 'safe-password-123',
            'password_confirmation' => 'different-password',
        ])
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors('password');

    expect(User::query()->where('email', 'new@example.com')->exists())->toBeFalse();
});
