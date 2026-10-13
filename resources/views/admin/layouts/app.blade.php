<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Admin')) - Yakiniku King</title>
    @vite('resources/scss/admin.scss')
    @stack('styles')
</head>

<body>
    <div class="admin-wrapper" data-admin-shell data-admin-labels="{{ json_encode([
        'image' => __('Image'),
        'currentImage' => __('Current image:'),
        'removeImage' => __('Remove current image'),
        'keepImage' => __('Keep current image'),
        'preview' => __('Preview:'),
        'selectedImage' => __('Selected image'),
        'title' => __('Title'),
        'titleEnglish' => __('Title (English)'),
        'bannerType' => __('Banner type'),
        'bannerLink' => __('Link when clicking the banner'),
        'displayOrder' => __('Display order'),
        'showBanner' => __('Show banner'),
    ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}">
        <aside class="admin-sidebar" id="admin-sidebar">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <span class="admin-brand-mark" aria-hidden="true">YK</span>
                <span class="admin-brand-copy">
                    <strong>YAKINIKU KING</strong>
                    <small>ADMIN CONSOLE</small>
                </span>
            </a>

            <nav class="admin-nav" aria-label="{{ __('Điều hướng quản trị') }}">
                <div class="admin-nav-group">
                    <p class="admin-nav-label">{{ __('Tổng quan') }}</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="admin-nav-mark" aria-hidden="true">01</span>{{ __('Dashboard') }}</a></li>
                    </ul>
                </div>



                <div class="admin-nav-group">
                    <p class="admin-nav-label">{{ __('Thực đơn') }}</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.banners.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.banners.index') }}"><span class="admin-nav-mark" aria-hidden="true">02</span>{{ __('Biển quảng cáo') }}</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.items.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.items.index') }}"><span class="admin-nav-mark" aria-hidden="true">03</span>{{ __('Món ăn') }}</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.categories.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.categories.index') }}"><span class="admin-nav-mark" aria-hidden="true">04</span>{{ __('Danh mục thực đơn') }}</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.kids-items.*') ? 'is-active' : '' }}" href="{{ route('admin.kids-items.index') }}"><span class="admin-nav-mark" aria-hidden="true">05</span>{{ __('Nội dung trẻ em') }}</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.combos.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.combos.index') }}"><span class="admin-nav-mark" aria-hidden="true">06</span>{{ __('Combos') }}</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.promotions.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.promotions.index') }}"><span class="admin-nav-mark" aria-hidden="true">07</span>{{ __('Khuyến mãi') }}</a></li>
                    </ul>
                </div>

                <div class="admin-nav-group">
                    <p class="admin-nav-label">{{ __('Nội dung') }}</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.recipes.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.recipes.index') }}"><span class="admin-nav-mark" aria-hidden="true">08</span>{{ __('Công thức nấu ăn') }}</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.tips.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.tips.index') }}"><span class="admin-nav-mark" aria-hidden="true">09</span>{{ __('Bí kíp ăn ngon') }}</a></li>
                    </ul>
                </div>

                <div class="admin-nav-group">
                    <p class="admin-nav-label">{{ __('Vận hành') }}</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.restaurants.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.restaurants.index') }}"><span class="admin-nav-mark" aria-hidden="true">10</span>{{ __('Nhà hàng') }}</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.bookings.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.bookings.index') }}"><span class="admin-nav-mark" aria-hidden="true">11</span>{{ __('Đặt bàn') }}</a></li>
                        @if (auth()->user()->hasPermission('menu.bookings.manage'))
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}" href="{{ route('admin.reports.operations') }}"><span class="admin-nav-mark" aria-hidden="true">12</span>{{ __('Báo cáo vận hành') }}</a></li>
                        @endif                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.leads.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.leads.index') }}"><span class="admin-nav-mark" aria-hidden="true">13</span>{{ __('Khách hàng tiềm năng') }}</a></li>
                        @if (auth()->user()->hasPermission('menu.customers.manage'))
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.customers.*') ? 'is-active' : '' }}" href="{{ route('admin.customers.index') }}"><span class="admin-nav-mark" aria-hidden="true">14</span>{{ __('Quản lý khách hàng') }}</a></li>
                        @endif
                        @if (auth()->user()->hasPermission('accounts.manage'))
                        <div class="admin-nav-group">
                            <ul class="admin-nav-list">
                                <li><a class="admin-nav-link {{ request()->routeIs('admin.accounts.*') ? 'is-active' : '' }}" href="{{ route('admin.accounts.index') }}"><span class="admin-nav-mark" aria-hidden="true">15</span>{{ __('Tài khoản') }}</a></li>
                            </ul>
                        </div>
                        @endif
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.settings.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.settings.index') }}"><span class="admin-nav-mark" aria-hidden="true">16</span>{{ __('Cài đặt') }}</a></li>
                    </ul>
                </div>
            </nav>

            <div class="admin-sidebar-footer">{{ __('Quản trị hệ thống · Yakiniku King') }}</div>
        </aside>

        <button class="admin-backdrop" type="button" aria-label="{{ __('Đóng menu') }}" data-admin-backdrop></button>

        <main class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar-left">
                    <button class="admin-menu-toggle" type="button" aria-label="{{ __('Mở menu') }}" aria-expanded="false" aria-controls="admin-sidebar" data-admin-menu-toggle>
                        <span aria-hidden="true"></span>
                    </button>
                    <div class="admin-topbar-title">@yield('page-title', trim($__env->yieldContent('title', __('Dashboard'))))</div>
                </div>

                <div class="admin-topbar-user">
                    <div class="admin-user-info">
                        <span class="admin-user-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="admin-user-name">{{ auth()->user()->name }}</span>
                    </div>
                    <a class="admin-account-link" href="{{ route('password.change') }}">{{ __('Đổi mật khẩu') }}</a>
                    <form class="admin-logout-form" action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="admin-logout-button" type="submit">{{ __('Đăng xuất') }}</button>
                    </form>
                </div>
            </header>

            <section class="admin-content">
                @yield('content')
            </section>
        </main>
    </div>

    @vite('resources/js/app.js')
</body>

</html>
