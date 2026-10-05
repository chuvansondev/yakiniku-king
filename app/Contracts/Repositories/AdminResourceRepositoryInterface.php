<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface AdminResourceRepositoryInterface
{
    public function all(string $modelClass, array $with = [], array $orderBy = [], array $filters = [], ?int $limit = null): Collection;

    public function count(string $modelClass, array $filters = []): int;

    public function countByDate(string $modelClass, string $column, mixed $date, array $filters = []): int;

    public function countsByDateRange(string $modelClass, string $column, string $from, string $to): \Illuminate\Support\Collection;

    public function firstWhere(string $modelClass, string $column, mixed $value): ?Model;

    public function create(string $modelClass, array $attributes): Model;

    public function update(Model $model, array $attributes): bool;

    public function delete(Model $model): bool;
}
