<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\PublicDataCache;
use Illuminate\Support\Facades\Cache;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:cache-clear {group? : Cache group to clear (menu, kids-items, combos, promotions, banners, settings)}', function (): int {
    $requestedGroup = $this->argument('group');
    $group = PublicDataCache::normalizeGroup($requestedGroup);

    if ($requestedGroup !== null && $group === null) {
        $this->error('Invalid cache group. Available groups: '.implode(', ', PublicDataCache::groups()));

        return self::FAILURE;
    }

    PublicDataCache::clear($group);

    $this->info($group === null ? 'Public data cache cleared.' : "Cache group [{$requestedGroup}] cleared.");

    return self::SUCCESS;
})->purpose('Clear application cache, optionally for one public data group');

Artisan::command('app:cache-clear-all', function (): int {
    Cache::flush();
    PublicDataCache::clear();

    $this->info('All application cache cleared.');

    return self::SUCCESS;
})->purpose('Flush the configured cache store');
