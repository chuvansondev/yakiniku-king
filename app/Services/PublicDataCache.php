<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PublicDataCache
{
    public const MENU = 'public-data:menu';
    public const KIDS_ITEMS = 'public-data:kids-items';
    public const COMBOS = 'public-data:combos';
    public const PROMOTIONS = 'public-data:promotions';
    public const BANNERS = 'public-data:banners';
    public const SETTINGS = 'public-data:settings';

    private const TAGS = [
        self::MENU,
        self::KIDS_ITEMS,
        self::COMBOS,
        self::PROMOTIONS,
        self::BANNERS,
        self::SETTINGS,
    ];

    public static function remember(string $group, string $key, callable $callback): mixed
    {
        $isEloquentCollection = false;
        $value = Cache::remember(
            self::key($group, $key),
            now()->addMinutes((int) config('cache.public_data_ttl', 30)),
            function () use ($callback, &$isEloquentCollection): mixed {
                $result = $callback();
                $isEloquentCollection = $result instanceof \Illuminate\Database\Eloquent\Collection;

                return self::normalize($result);
            },
        );

        return self::restore($value, $isEloquentCollection);
    }

    public static function clear(?string $group = null): void
    {
        $groups = $group === null ? self::TAGS : [$group];

        foreach ($groups as $cacheGroup) {
            Cache::put(self::key($cacheGroup, 'version'), Str::uuid()->toString(), now()->addDays(2));
        }
    }

    public static function groups(): array
    {
        return array_map(fn (string $group): string => substr($group, strlen('public-data:')), self::TAGS);
    }

    public static function normalizeGroup(?string $group): ?string
    {
        if ($group === null) {
            return null;
        }

        $normalized = str_starts_with($group, 'public-data:') ? $group : 'public-data:'.$group;

        return in_array($normalized, self::TAGS, true) ? $normalized : null;
    }

    private static function key(string $group, string $key): string
    {
        if ($key === 'version') {
            return $group.':version';
        }

        $version = Cache::rememberForever($group.':version', fn (): string => Str::uuid()->toString());

        return $group.':'.$version.':v2:'.$key;
    }

    private static function normalize(mixed $value): mixed
    {
        if ($value instanceof \Illuminate\Database\Eloquent\Model) {
            return [
                '__cached_model' => $value::class,
                'attributes' => $value->getAttributes(),
                'relations' => self::normalize($value->getRelations()),
            ];
        }

        if ($value instanceof Collection || $value instanceof \Illuminate\Database\Eloquent\Collection) {
            return [
                '__cached_collection' => true,
                'eloquent' => $value instanceof \Illuminate\Database\Eloquent\Collection,
                'model_class' => $value instanceof \Illuminate\Database\Eloquent\Collection ? $value->getQueueableClass() : null,
                'items' => array_map(fn (mixed $item): mixed => self::normalize($item), $value->all()),
            ];
        }

        if (is_array($value)) {
            $normalized = [];
            foreach ($value as $key => $item) {
                $normalized[$key] = self::normalize($item);
            }

            return $normalized;
        }

        return $value;
    }

    private static function restore(mixed $value, bool $expectsEloquentCollection = false): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        if (($value['__cached_collection'] ?? false) === true) {
            $items = array_map(fn (mixed $item): mixed => self::restore($item), $value['items']);
            $modelClass = $value['model_class'] ?? null;

            return (is_string($modelClass) && is_a($modelClass, \Illuminate\Database\Eloquent\Model::class, true))
                || ($value['eloquent'] ?? false)
                || $expectsEloquentCollection
                ? new \Illuminate\Database\Eloquent\Collection($items)
                : collect($items);
        }

        if (isset($value['__cached_model'], $value['attributes'])) {
            $modelClass = $value['__cached_model'];
            $model = new $modelClass;
            $model->setRawAttributes($value['attributes'], true);

            $relations = [];
            foreach ($value['relations'] ?? [] as $name => $relation) {
                $relations[$name] = self::restore($relation);
            }
            $model->setRelations($relations);

            return $model;
        }

        $restored = [];
        foreach ($value as $key => $item) {
            $restored[$key] = self::restore($item);
        }

        return $restored;
    }
}
