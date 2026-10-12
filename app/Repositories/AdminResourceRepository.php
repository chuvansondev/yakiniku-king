<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminResourceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function paginate(string $modelClass, array $criteria, array $options, array $with = [], array $defaultOrderBy = []): LengthAwarePaginator
    {
        $query = $modelClass::query()->with($with);
        $search = trim((string) ($criteria['search'] ?? ''));

        if ($search !== '' && ($searchColumns = $options['search'] ?? [])) {
            $query->where(function ($nested) use ($searchColumns, $search): void {
                foreach ($searchColumns as $column) {
                    $nested->orWhere($column, 'like', '%'.$search.'%');
                }
            });
        }

        foreach ($options['filters'] ?? [] as $name => $filter) {
            $value = $criteria[$name] ?? null;
            if ($value === null || $value === '') {
                continue;
            }

            if (isset($filter['allowed_values']) && ! in_array((int) $value, $filter['allowed_values'], true)) {
                continue;
            }

            if (isset($filter['options']) && ! array_key_exists((string) $value, $filter['options'])) {
                continue;
            }

            $column = $filter['column'] ?? $name;
            if (($filter['type'] ?? 'exact') === 'date') {
                $query->whereDate($column, $value);
            } else {
                $query->where($column, $value);
            }
        }

        $sortColumns = $options['sort'] ?? [];
        $sort = $criteria['sort'] ?? null;
        $direction = strtolower((string) ($criteria['direction'] ?? 'asc'));

        if (in_array($sort, $sortColumns, true)) {
            $query->orderBy($sort, in_array($direction, ['asc', 'desc'], true) ? $direction : 'asc');
        } else {
            foreach ($defaultOrderBy as [$column, $orderDirection]) {
                $query->orderBy($column, $orderDirection);
            }
        }

        $defaults = config('admin_lists_defaults', []);
        $perPage = (int) ($criteria['per_page'] ?? $options['per_page'] ?? $defaults['per_page'] ?? 20);
        $allowedPerPage = $options['per_page_options'] ?? $defaults['per_page_options'] ?? [10, 20, 50, 100];
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = (int) ($options['per_page'] ?? 20);
        }

        return $query->paginate($perPage)->appends($criteria);
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
