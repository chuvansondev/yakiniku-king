<?php

namespace App\Services;

use App\Contracts\Repositories\AdminResourceRepositoryInterface;
use App\Jobs\DeletePublicFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AdminResourceService
{
    public function __construct(private readonly AdminResourceRepositoryInterface $resources) {}

    public function all(string $modelClass, array $with = [], array $orderBy = [], array $filters = [], ?int $limit = null): Collection
    {
        return $this->resources->all($modelClass, $with, $orderBy, $filters, $limit);
    }

    public function paginate(string $modelClass, Request $request, array $with = [], array $orderBy = []): LengthAwarePaginator
    {
        $key = 'admin_lists.'.class_basename($modelClass);
        $options = config($key);

        if (! is_array($options)) {
            throw new \InvalidArgumentException("Missing list configuration [{$key}].");
        }

        $rules = [
            'search' => ['nullable', 'string', 'max:200'],
            'sort' => ['nullable', 'string', 'max:64'],
            'direction' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];

        foreach ($options['filters'] ?? [] as $name => $filter) {
            $rules[$name] = ($filter['type'] ?? '') === 'date'
                ? ['nullable', 'date']
                : (isset($filter['options'])
                    ? ['nullable', 'string', 'in:'.implode(',', array_keys($filter['options']))]
                    : ['nullable', 'integer', 'min:1']);
            if (($filter['options_source'] ?? null) !== null) {
                $rules[$name] = ['nullable', 'integer', 'min:1', 'exists:'.match ($filter['options_source']) {
                    'restaurants' => 'restaurants,id',
                    'categories' => 'menu_categories,id',
                }];
            }
        }

        $criteria = $request->validate($rules);
        $perPageOptions = $options['per_page_options'] ?? config('admin_lists_defaults.per_page_options', []);
        if (isset($criteria['per_page']) && ! in_array((int) $criteria['per_page'], $perPageOptions, true)) {
            $criteria['per_page'] = $options['per_page'] ?? config('admin_lists_defaults.per_page', 20);
        }
        if (isset($criteria['sort']) && ! in_array($criteria['sort'], $options['sort'] ?? [], true)) {
            $criteria['sort'] = null;
        }

        return $this->resources->paginate($modelClass, $criteria, $options, $with, $orderBy);
    }

    public function listFilterOptions(string $modelClass): array
    {
        return match (class_basename($modelClass)) {
            'Booking' => ['restaurants' => $this->all(\App\Models\Restaurant::class, orderBy: [['name', 'asc']])],
            'MenuItem' => ['categories' => $this->all(\App\Models\MenuCategory::class, orderBy: [['sort_order', 'asc'], ['name', 'asc']])],
            default => [],
        };
    }

    public function count(string $modelClass, array $filters = []): int
    {
        return $this->resources->count($modelClass, $filters);
    }

    public function countByDate(string $modelClass, string $column, mixed $date, array $filters = []): int
    {
        return $this->resources->countByDate($modelClass, $column, $date, $filters);
    }

    public function countsByDateRange(string $modelClass, string $column, string $from, string $to): \Illuminate\Support\Collection
    {
        return $this->resources->countsByDateRange($modelClass, $column, $from, $to);
    }

    public function firstWhere(string $modelClass, string $column, mixed $value): ?Model
    {
        return $this->resources->firstWhere($modelClass, $column, $value);
    }

    public function create(string $modelClass, array $attributes): Model
    {
        return $this->resources->create($modelClass, $attributes);
    }

    public function update(Model $model, array $attributes): bool
    {
        return $this->resources->update($model, $attributes);
    }

    public function delete(Model $model): bool
    {
        return $this->resources->delete($model);
    }

    public function slug(string $value): string
    {
        return Str::slug($value);
    }

    public function storeImage(?UploadedFile $image, string $directory): ?string
    {
        return $image?->store($directory, 'public');
    }

    public function replaceImage(?UploadedFile $image, bool $remove, ?string $currentPath, string $directory): ?string
    {
        if ($image !== null) {
            return $this->storeImage($image, $directory);
        }

        if ($remove) {
            return null;
        }

        return $currentPath;
    }

    public function deleteImage(?string $path): void
    {
        if ($path) DeletePublicFile::dispatch($path)->afterCommit();
    }
}
