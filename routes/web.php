<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KidsItemController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController as FrontendBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController as FrontendLeadController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SecretController;
use App\Http\Requests\LocaleRequest;
use Illuminate\Support\Facades\Route;

Route::post('/locale', function (LocaleRequest $request) {
    $validated = $request->validated();

    $request->session()->put('locale', $validated['locale']);

    return redirect()->back();
})->name('locale.update');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1')->name('register.submit');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:3,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/account/password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::put('/account/password', [AuthController::class, 'changePassword'])->middleware('throttle:5,1')->name('password.change.update');
});

Route::get('/', HomeController::class)->name('home');

Route::get('/book-now', [FrontendBookingController::class, 'create'])
    ->name('booking.create');

Route::post('/book-now', [FrontendBookingController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('booking.store');

Route::post('/book-now/cancel', [FrontendBookingController::class, 'cancel'])
    ->middleware('throttle:5,1')
    ->name('booking.cancel');

Route::post('/leads', [FrontendLeadController::class, 'store'])
    ->name('leads.store');

Route::view('/about', 'about.index')
    ->name('about');

Route::get('/menu', [MenuController::class, 'index'])
    ->name('menu.index');

Route::get('/menu/must-try', [MenuController::class, 'mustTry'])
    ->name('menu.must-try');

Route::get('/menu/combos', [MenuController::class, 'combos'])
    ->name('menu.combos');

Route::get('/menu/for-kids', [MenuController::class, 'forKids'])
    ->name('menu.for-kids');

Route::get('/menu/promotions', [MenuController::class, 'promotions'])
    ->name('menu.promotions');

Route::get('/menu/category/{category:slug}', [MenuController::class, 'index'])
    ->name('menu.category');

Route::get('/our-secret/recipes', [SecretController::class, 'recipes'])
    ->name('secret.recipes');

Route::get('/our-secret/recipes/{recipe:slug}', [SecretController::class, 'recipe'])
    ->name('secret.recipe');

Route::get('/our-secret/tips', [SecretController::class, 'tips'])
    ->name('secret.tips');

Route::get('/our-secret/tips/{tip:slug}', [SecretController::class, 'tip'])
    ->name('secret.tip');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('cache.headers:no_store')
    ->group(function () {

        // Login
        Route::get('/login', [AdminAuthController::class, 'showLogin'])
            ->name('login');

        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.submit');

        // Khu vực cần đăng nhập
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/', [DashboardController::class, 'index'])
                ->middleware('permission:admin.dashboard.view')
                ->name('dashboard');

            Route::prefix('menu')->name('menu.')->group(function () {
                Route::resource('categories', MenuCategoryController::class)
                    ->middleware('permission:menu.categories.manage')
                    ->except(['show']);

                Route::resource('items', MenuItemController::class)
                    ->middleware('permission:menu.items.manage')
                    ->except(['show']);

                Route::resource('combos', ComboController::class)
                    ->middleware('permission:menu.combos.manage')
                    ->except(['show']);

                Route::resource('banners', BannerController::class)
                    ->middleware('permission:menu.banners.manage')
                    ->except(['show']);

                Route::resource('promotions', PromotionController::class)
                    ->middleware('permission:menu.promotions.manage')
                    ->except(['show']);

                Route::resource('recipes', RecipeController::class)
                    ->middleware('permission:menu.recipes.manage')
                    ->except(['show']);

                Route::resource('tips', TipController::class)
                    ->middleware('permission:menu.tips.manage')
                    ->except(['show']);

                Route::resource('restaurants', RestaurantController::class)
                    ->middleware('permission:menu.restaurants.manage')
                    ->except(['show']);

                Route::resource('bookings', BookingController::class)
                    ->middleware('permission:menu.bookings.manage')
                    ->except(['show']);

                Route::resource('leads', LeadController::class)
                    ->middleware('permission:menu.leads.manage')
                    ->except(['show']);

                Route::get('settings', [SettingController::class, 'index'])
                    ->middleware('permission:menu.settings.manage')
                    ->name('settings.index');
                Route::match(['post', 'put', 'patch'], 'settings', [SettingController::class, 'update'])
                    ->middleware('permission:menu.settings.manage')
                    ->name('settings.update');
            });

            Route::resource('kids-items', KidsItemController::class)
                ->middleware('permission:kids-items.manage')
                ->except(['show']);

            Route::post('/logout', [AdminAuthController::class, 'logout'])
                ->name('logout');
        });
    });
