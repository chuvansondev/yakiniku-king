<?php

namespace App\Providers;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Contracts\Repositories\BannerRepositoryInterface;
use App\Contracts\Repositories\AdminResourceRepositoryInterface;
use App\Contracts\Repositories\ComboRepositoryInterface;
use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Contracts\Repositories\MenuRepositoryInterface;
use App\Contracts\Repositories\SecretRepositoryInterface;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Repositories\BannerRepository;
use App\Repositories\AdminResourceRepository;
use App\Repositories\BookingRepository;
use App\Repositories\ComboRepository;
use App\Repositories\LeadRepository;
use App\Repositories\MenuRepository;
use App\Repositories\SecretRepository;
use App\Repositories\SettingRepository;
use App\Services\MenuService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(AdminResourceRepositoryInterface::class, AdminResourceRepository::class);
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
        $this->app->bind(ComboRepositoryInterface::class, ComboRepository::class);
        $this->app->bind(LeadRepositoryInterface::class, LeadRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(SecretRepositoryInterface::class, SecretRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('fontend.partials.header', function (ViewInstance $view): void {
            $view->with('menuCategories', app(MenuService::class)->headerCategories());
        });
    }
}
