<?php

namespace App\Services;

use App\Contracts\Repositories\AdminResourceRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminResourceService
{
    public function __construct(private readonly AdminResourceRepositoryInterface $resources) {}

    public function all(string $modelClass, array $with = [], array $orderBy = [], array $filters = [], ?int $limit = null): Collection
    {
        return $this->resources->all($modelClass, $with, $orderBy, $filters, $limit);
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
            $newPath = $this->storeImage($image, $directory);
            $this->deleteImage($currentPath);
            return $newPath;
        }

        if ($remove) {
            $this->deleteImage($currentPath);
            return null;
        }

        return $currentPath;
    }

    public function deleteImage(?string $path): void
    {
        if ($path) Storage::disk('public')->delete($path);
    }
}
