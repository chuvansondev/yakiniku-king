<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\PublicDataCache;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schedule;

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

Artisan::command('bookings:prune {--days=90 : Keep bookings from the last N days} {--force : Permanently delete matching bookings}', function (): int {
    $days = filter_var($this->option('days'), FILTER_VALIDATE_INT);

    if ($days === false || $days < 1) {
        $this->error('The --days option must be a positive integer.');

        return self::FAILURE;
    }

    $cutoff = Carbon::today()->subDays($days);
    $bookings = Booking::query()->whereDate('booking_date', '<', $cutoff);
    $count = (clone $bookings)->count();

    if (! $this->option('force')) {
        $this->info("{$count} booking(s) older than {$days} days are eligible for deletion (before {$cutoff->toDateString()}).");
        $this->comment('Run again with --force to permanently delete them.');

        return self::SUCCESS;
    }

    $deleted = $bookings->delete();
    $this->info("Deleted {$deleted} booking(s) older than {$days} days.");

    return self::SUCCESS;
})->purpose('Preview or permanently delete bookings older than the retention period');

Schedule::command('bookings:prune --force')->dailyAt('02:00')->withoutOverlapping();
