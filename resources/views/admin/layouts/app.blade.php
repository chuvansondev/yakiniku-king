<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Yakiniku King</title>
    @vite('resources/scss/admin.scss')
    @stack('styles')
</head>

<body>
    <div class="admin-wrapper" data-admin-shell>
        <aside class="admin-sidebar" id="admin-sidebar">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <span class="admin-brand-mark" aria-hidden="true">YK</span>
                <span class="admin-brand-copy">
                    <strong>YAKINIKU KING</strong>
                    <small>ADMIN CONSOLE</small>
                </span>
            </a>

            <nav class="admin-nav" aria-label="Điều hướng quản trị">
                <div class="admin-nav-group">
                    <p class="admin-nav-label">Tổng quan</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="admin-nav-mark" aria-hidden="true">01</span>Dashboard</a></li>
                    </ul>
                </div>

                <div class="admin-nav-group">
                    <p class="admin-nav-label">Thực đơn</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.banners.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.banners.index') }}"><span class="admin-nav-mark" aria-hidden="true">02</span>Biển quảng cáo</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.categories.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.categories.index') }}"><span class="admin-nav-mark" aria-hidden="true">03</span>Danh mục thực đơn</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.items.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.items.index') }}"><span class="admin-nav-mark" aria-hidden="true">04</span>Món ăn</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.combos.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.combos.index') }}"><span class="admin-nav-mark" aria-hidden="true">05</span>Combos</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.promotions.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.promotions.index') }}"><span class="admin-nav-mark" aria-hidden="true">06</span>Khuyến mãi</a></li>
                    </ul>
                </div>

                <div class="admin-nav-group">
                    <p class="admin-nav-label">Dành cho trẻ em</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.kids-items.*') ? 'is-active' : '' }}" href="{{ route('admin.kids-items.index') }}"><span class="admin-nav-mark" aria-hidden="true">07</span>Nội dung trẻ em</a></li>
                    </ul>
                </div>

                <div class="admin-nav-group">
                    <p class="admin-nav-label">Nội dung</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.recipes.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.recipes.index') }}"><span class="admin-nav-mark" aria-hidden="true">08</span>Công thức nấu ăn</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.tips.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.tips.index') }}"><span class="admin-nav-mark" aria-hidden="true">09</span>Bí kíp ăn ngon</a></li>
                    </ul>
                </div>

                <div class="admin-nav-group">
                    <p class="admin-nav-label">Vận hành</p>
                    <ul class="admin-nav-list">
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.restaurants.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.restaurants.index') }}"><span class="admin-nav-mark" aria-hidden="true">10</span>Nhà hàng</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.bookings.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.bookings.index') }}"><span class="admin-nav-mark" aria-hidden="true">11</span>Đặt bàn</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.leads.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.leads.index') }}"><span class="admin-nav-mark" aria-hidden="true">12</span>Khách hàng tiềm năng</a></li>
                        <li><a class="admin-nav-link {{ request()->routeIs('admin.menu.settings.*') ? 'is-active' : '' }}" href="{{ route('admin.menu.settings.index') }}"><span class="admin-nav-mark" aria-hidden="true">13</span>Cài đặt</a></li>
                    </ul>
                </div>
            </nav>

            <div class="admin-sidebar-footer">Quản trị hệ thống · Yakiniku King</div>
        </aside>

        <button class="admin-backdrop" type="button" aria-label="Đóng menu" data-admin-backdrop></button>

        <main class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar-left">
                    <button class="admin-menu-toggle" type="button" aria-label="Mở menu" aria-expanded="false" aria-controls="admin-sidebar" data-admin-menu-toggle>
                        <span aria-hidden="true"></span>
                    </button>
                    <div class="admin-topbar-title">@yield('page-title', trim($__env->yieldContent('title', 'Dashboard')))</div>
                </div>

                <div class="admin-topbar-user">
                    <div class="admin-user-info">
                        <span class="admin-user-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="admin-user-name">{{ auth()->user()->name }}</span>
                    </div>
                    <a class="admin-account-link" href="{{ route('password.change') }}">Đổi mật khẩu</a>
                    <form class="admin-logout-form" action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="admin-logout-button" type="submit">Đăng xuất</button>
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
