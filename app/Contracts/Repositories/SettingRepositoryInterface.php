<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface SettingRepositoryInterface
{
    public function value(string $key): mixed;

    public function allKeyed(): Collection;

    public function updateValue(string $key, mixed $value, ?int $userId): void;
}
