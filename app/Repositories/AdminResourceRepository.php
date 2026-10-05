<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminResourceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AdminResourceRepository implements AdminResourceRepositoryInterface
{
    public function all(string $modelClass, array $with = [], array $orderBy = [], array $filters = [], ?int $limit = null): Collection
    {
        $query = $modelClass::query()->with($with);

        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        foreach ($orderBy as [$column, $direction]) {
            $query->orderBy($column, $direction);
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function count(string $modelClass, array $filters = []): int
    {
        return $modelClass::query()->where($filters)->count();
    }

    public function countByDate(string $modelClass, string $column, mixed $date, array $filters = []): int
    {
        return $modelClass::query()->where($filters)->whereDate($column, $date)->count();
    }

    public function countsByDateRange(string $modelClass, string $column, string $from, string $to): \Illuminate\Support\Collection
    {
        return $modelClass::query()->whereDate($column, '>=', $from)->whereDate($column, '<=', $to)
            ->selectRaw('DATE('.$column.') as aggregate_day, COUNT(*) as aggregate_total')
            ->groupBy('aggregate_day')->pluck('aggregate_total', 'aggregate_day');
    }

    public function firstWhere(string $modelClass, string $column, mixed $value): ?Model
    {
        return $modelClass::query()->where($column, $value)->first();
    }

    public function create(string $modelClass, array $attributes): Model
    {
        return $modelClass::query()->create($attributes);
    }

    public function update(Model $model, array $attributes): bool
    {
        return $model->update($attributes);
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }
}
