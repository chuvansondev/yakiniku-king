<?php

namespace App\Services;

use App\Enums\AppLocale;
use App\Contracts\Repositories\SettingRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SettingService
{
    public function __construct(private readonly SettingRepositoryInterface $settings) {}

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings->value($key) ?? $default;
    }

    public function all(): Collection
    {
        return $this->settings->allKeyed();
    }

    public function updateMany(array $settings): void
    {
        $userId = Auth::id();

        foreach ($settings as $key => $value) {
            $this->settings->updateValue((string) $key, $value, $userId);
        }
    }

    public function localized(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        if (app()->getLocale() !== AppLocale::English->value) return $value;

        return $this->get($key.'_en') ?: __($value ?? '');
    }
}
