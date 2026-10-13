<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerService
{
    public function resolve(string $name, ?string $email, ?string $phone, ?int $userId = null, string $source = 'booking'): Customer
    {
        $email = filled($email) ? trim($email) : null;
        $phone = filled($phone) ? trim($phone) : null;
        $emailNormalized = $email ? Str::lower($email) : null;
        $phoneNormalized = $phone ? preg_replace('/\D+/', '', $phone) : null;
        $phoneNormalized = $phoneNormalized !== '' ? $phoneNormalized : null;

        if (! $userId && ! $emailNormalized && ! $phoneNormalized) {
            return Customer::query()->create([
                'name' => $name,
                'source' => $source,
                'last_contact_at' => now(),
                'status' => 'active',
            ]);
        }

        $customer = $userId
            ? Customer::query()->where('user_id', $userId)->first()
            : null;

        $customer ??= Customer::query()
            ->when($emailNormalized || $phoneNormalized, function ($query) use ($emailNormalized, $phoneNormalized): void {
                $query->where(function ($identity) use ($emailNormalized, $phoneNormalized): void {
                    if ($emailNormalized) $identity->orWhere('email_normalized', $emailNormalized);
                    if ($phoneNormalized) $identity->orWhere('phone_normalized', $phoneNormalized);
                });
            })
            ->orderByDesc('id')
            ->first();

        if (! $customer) {
            $customer = new Customer(['source' => $source]);
        }

        $customer->fill([
            'user_id' => $customer->user_id ?: $userId,
            'name' => $name ?: $customer->name,
            'email' => $email ?: $customer->email,
            'email_normalized' => $emailNormalized ?: $customer->email_normalized,
            'phone' => $phone ?: $customer->phone,
            'phone_normalized' => $phoneNormalized ?: $customer->phone_normalized,
            'last_contact_at' => now(),
            'status' => 'active',
        ])->save();

        return $customer;
    }
}
