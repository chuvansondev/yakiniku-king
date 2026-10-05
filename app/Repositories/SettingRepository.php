<?php

namespace App\Repositories;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingRepository implements SettingRepositoryInterface
{
    public function value(string $key): mixed
    {
        return Setting::query()->where('key', $key)->value('value');
    }

    public function allKeyed(): Collection
    {
        return Setting::query()->pluck('value', 'key');
    }

    public function updateValue(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
