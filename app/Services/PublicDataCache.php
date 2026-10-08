<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
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
        return Cache::remember(self::key($group, $key), now()->addMinutes((int) config('cache.public_data_ttl', 30)), $callback);
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

        return $group.':'.$version.':'.$key;
    }
}
