<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Yakiniku King</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-ink: #202923;
            --admin-muted: #737d76;
            --admin-line: #e1e7e2;
            --admin-canvas: #f1f4f1;
            --admin-paper: #fff;
            --admin-sidebar: #1e2a24;
            --admin-sidebar-muted: #a4b1a8;
            --admin-accent: #c84b3b;
            --admin-accent-dark: #aa3d31;
            --admin-green: #527761;
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            color: var(--admin-ink);
            background: var(--admin-canvas);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            min-width: 320px;
            min-height: 100vh;
            margin: 0;
            background: var(--admin-canvas);
            color: var(--admin-ink);
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            position: sticky;
            top: 0;
            display: flex;
            width: 258px;
            height: 100vh;
            flex: 0 0 258px;
            flex-direction: column;
            padding: 24px 14px 18px;
            overflow-y: auto;
            background: var(--admin-sidebar);
            color: #fff;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px 23px;
            color: #fff;
            text-decoration: none;
        }

        .admin-brand-mark {
            display: grid;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            place-items: center;
            border: 1px solid #68776c;
            color: #f4d0a0;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 18px;
        }

        .admin-brand-copy strong,
        .admin-brand-copy small {
            display: block;
        }

        .admin-brand-copy strong {
            font-size: 12px;
            letter-spacing: .08em;
        }

        .admin-brand-copy small {
            margin-top: 3px;
            color: var(--admin-sidebar-muted);
            font-size: 9px;
            letter-spacing: .12em;
        }

        .admin-nav {
            flex: 1;
        }

        .admin-nav-group + .admin-nav-group {
            margin-top: 23px;
        }

        .admin-nav-label {
            margin: 0 10px 8px;
            color: #86968b;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .admin-nav-list {
            display: grid;
            gap: 3px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .admin-nav-link {
            position: relative;
            display: flex;
            min-height: 40px;
            align-items: center;
            gap: 12px;
            padding: 9px 11px;
            border-radius: 4px;
            color: #d3dcd5;
            font-size: 12px;
            text-decoration: none;
            transition: background-color 140ms ease, color 140ms ease;
        }

        .admin-nav-link:hover {
            background: #2d3b33;
            color: #fff;
        }

        .admin-nav-link.is-active {
            background: #39483e;
            color: #fff;
            font-weight: 700;
        }

        .admin-nav-link.is-active::before {
            position: absolute;
            inset: 8px auto 8px 0;
            width: 3px;
            background: #e17857;
            content: '';
        }

        .admin-nav-mark {
            width: 20px;
            color: #aebcb1;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 14px;
            text-align: center;
        }

        .admin-sidebar-footer {
            padding: 16px 10px 0;
            border-top: 1px solid #3b4940;
            color: var(--admin-sidebar-muted);
            font-size: 10px;
        }

        .admin-main {
            display: flex;
            min-width: 0;
            flex: 1;
            flex-direction: column;
        }

        .admin-topbar {
            position: sticky;
            z-index: 10;
            top: 0;
            display: flex;
            min-height: 68px;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 10px clamp(18px, 3vw, 38px);
            border-bottom: 1px solid var(--admin-line);
            background: rgb(255 255 255 / 94%);
            backdrop-filter: blur(12px);
        }

        .admin-topbar-left,
        .admin-topbar-user,
        .admin-user-info {
            display: flex;
            align-items: center;
        }

        .admin-topbar-left {
            gap: 14px;
            min-width: 0;
        }

        .admin-topbar-title {
            overflow: hidden;
            color: var(--admin-ink);
            font-size: 13px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-topbar-user {
            gap: 13px;
        }

        .admin-user-info {
            gap: 9px;
        }

        .admin-user-avatar {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border: 1px solid #d9e2dc;
            border-radius: 50%;
            background: #edf2ee;
            color: var(--admin-green);
            font-size: 12px;
            font-weight: 700;
        }

        .admin-user-name {
            max-width: 180px;
            overflow: hidden;
            font-size: 12px;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-logout-form {
            margin: 0;
        }

        .admin-account-link {
            display: inline-flex;
            min-height: 36px;
            align-items: center;
            padding: 7px 11px;
            border: 1px solid var(--admin-line);
            border-radius: 4px;
            background: #fff;
            color: var(--admin-ink);
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
        }

        .admin-account-link:hover {
            border-color: #d8b4a8;
            color: var(--admin-accent-dark);
        }

        .admin-logout-button {
            min-height: 36px;
            padding: 7px 11px;
            border: 1px solid var(--admin-line);
            border-radius: 4px;
            background: #fff;
            color: var(--admin-ink);
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
        }

        .admin-logout-button:hover {
            border-color: #d8b4a8;
            color: var(--admin-accent-dark);
        }

        .admin-image-remove-button {
            margin-top: 10px;
            padding: 8px 12px;
            border: 1px solid var(--admin-accent);
            border-radius: 4px;
            background: var(--admin-paper);
            color: var(--admin-accent-dark);
            cursor: pointer;
        }

        .admin-image-remove-button:hover,
        .admin-image-remove-button[aria-pressed="true"] {
            background: var(--admin-accent);
            color: #fff;
        }

        .admin-content {
            width: 100%;
            max-width: 1720px;
            flex: 1;
            margin: 0 auto;
            padding: clamp(18px, 3vw, 38px);
            animation: admin-enter 220ms ease-out both;
        }

        @keyframes admin-enter {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .admin-content h1 {
            margin: 0 0 18px;
            color: var(--admin-ink);
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 29px;
            font-weight: 400;
            line-height: 1.2;
        }

        .admin-content h2,
        .admin-content h3,
        .admin-content h4 {
            color: var(--admin-ink);
        }

        .admin-content a {
            color: var(--admin-accent-dark);
            text-underline-offset: 3px;
        }

        .admin-content a:hover {
            color: #812f28;
        }

        .admin-content a[style*="background:#111"],
        .admin-content a[style*="background: #111"],
        .admin-content a[style*="background:#111;"] {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            justify-content: center;
            border-radius: 4px !important;
            font-size: 12px;
            font-weight: 700;
        }

        .admin-content div[style*="background:white"],
        .admin-content div[style*="background: white"] {
            border: 1px solid var(--admin-line);
            border-radius: 6px;
            box-shadow: 0 8px 24px rgb(32 41 35 / 4%);
        }

        .admin-content div[style*="background:#d1fae5"],
        .admin-content div[style*="background: #d1fae5"],
        .admin-content div[style*="background: #d4edda"] {
            border: 1px solid #c5dfce;
            border-radius: 4px;
        }

        .admin-content form:not([style*="display:inline"]) {
            border: 1px solid var(--admin-line);
            border-radius: 6px;
            background: var(--admin-paper);
            box-shadow: 0 8px 24px rgb(32 41 35 / 4%);
        }

        .admin-content label {
            display: inline-block;
            margin-bottom: 7px;
            color: #37443b;
            font-size: 12px;
            font-weight: 700;
        }

        .admin-content input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]),
        .admin-content select,
        .admin-content textarea {
            max-width: 100%;
            border: 1px solid #ccd5ce;
            border-radius: 4px;
            background: #fff;
            color: var(--admin-ink);
            font-size: 13px;
        }

        .admin-content input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):focus,
        .admin-content select:focus,
        .admin-content textarea:focus {
            border-color: #70917b;
            outline: 3px solid rgb(82 119 97 / 14%);
        }

        .admin-content input[type="file"] {
            width: 100%;
            padding: 9px !important;
            background: #f8faf8 !important;
        }

        .admin-content input[type="checkbox"],
        .admin-content input[type="radio"] {
            accent-color: var(--admin-green);
        }

        .admin-content textarea {
            min-height: 88px;
            resize: vertical;
        }

        .admin-content button[type="submit"],
        .admin-content input[type="submit"] {
            min-height: 38px;
            padding: 9px 15px;
            border: 1px solid var(--admin-accent-dark);
            border-radius: 4px;
            background: var(--admin-accent);
            color: #fff;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            transition: background-color 140ms ease;
        }

        .admin-content button[type="submit"]:hover,
        .admin-content input[type="submit"]:hover {
            background: var(--admin-accent-dark);
        }

        .admin-content table {
            width: 100%;
            border: 0 !important;
            border-collapse: collapse;
            background: var(--admin-paper);
            color: var(--admin-ink);
            font-size: 12px;
        }

        .admin-content th,
        .admin-content td {
            padding: 12px 14px !important;
            border: 0 !important;
            border-bottom: 1px solid var(--admin-line) !important;
            text-align: left;
            vertical-align: middle;
        }

        .admin-content th {
            background: #f5f7f5;
            color: #66736a;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .admin-content tbody tr:last-child td {
            border-bottom: 0 !important;
        }

        .admin-content tbody tr:hover {
            background: #f8faf8;
        }

        .admin-content table[border] {
            border: 1px solid var(--admin-line) !important;
        }

        .admin-content form[style*="display:inline"] {
            display: inline-flex !important;
            align-items: center;
            margin: 0 0 0 8px;
        }

        .admin-content form[style*="display:inline"] button[type="submit"] {
            min-height: 30px;
            padding: 5px 9px;
            border-color: #e2c7c2;
            background: #fff;
            color: #a83e37;
        }

        .admin-content form[style*="display:inline"] button[type="submit"]:hover {
            background: #fcebea;
        }

        .admin-content img {
            max-width: 100%;
            border-radius: 4px;
        }

        .admin-menu-toggle,
        .admin-backdrop {
            display: none;
        }

        @media (max-width: 960px) {
            .admin-sidebar {
                position: fixed;
                z-index: 31;
                inset: 0 auto 0 0;
                transform: translateX(-102%);
                transition: transform 180ms ease;
            }

            .admin-sidebar-open .admin-sidebar {
                transform: translateX(0);
            }

            .admin-backdrop {
                position: fixed;
                z-index: 30;
                inset: 0;
                display: block;
                visibility: hidden;
                border: 0;
                background: rgb(18 25 21 / 48%);
                opacity: 0;
                transition: opacity 180ms ease, visibility 180ms ease;
            }

            .admin-sidebar-open .admin-backdrop {
                visibility: visible;
                opacity: 1;
            }

            .admin-menu-toggle {
                display: inline-grid;
                width: 38px;
                height: 38px;
                place-items: center;
                border: 1px solid var(--admin-line);
                border-radius: 4px;
                background: #fff;
                color: var(--admin-ink);
                cursor: pointer;
            }

            .admin-menu-toggle span,
            .admin-menu-toggle span::before,
            .admin-menu-toggle span::after {
                display: block;
                width: 16px;
                height: 2px;
                background: currentColor;
                content: '';
            }

            .admin-menu-toggle span {
                position: relative;
            }

            .admin-menu-toggle span::before,
            .admin-menu-toggle span::after {
                position: absolute;
                left: 0;
            }

            .admin-menu-toggle span::before { top: -5px; }
            .admin-menu-toggle span::after { top: 5px; }
        }

        @media (max-width: 600px) {
            .admin-topbar {
                min-height: 60px;
                gap: 10px;
                padding-inline: 14px;
            }

            .admin-user-name {
                display: none;
            }

            .admin-topbar-user {
                gap: 8px;
            }

            .admin-content {
                padding: 18px 14px 28px;
            }

            .admin-content h1 {
                font-size: 25px;
            }

            .admin-content form:not([style*="display:inline"]) {
                padding: 18px !important;
            }

            .admin-content table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
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

    <script>
        const adminShell = document.querySelector('[data-admin-shell]');
        const adminMenuToggle = document.querySelector('[data-admin-menu-toggle]');
        const adminBackdrop = document.querySelector('[data-admin-backdrop]');

        const closeAdminMenu = () => {
            adminShell.classList.remove('admin-sidebar-open');
            adminMenuToggle.setAttribute('aria-expanded', 'false');
        };

        adminMenuToggle.addEventListener('click', () => {
            const isOpen = adminShell.classList.toggle('admin-sidebar-open');
            adminMenuToggle.setAttribute('aria-expanded', String(isOpen));
        });

        adminBackdrop.addEventListener('click', closeAdminMenu);

        document.querySelectorAll('.admin-nav-link').forEach((adminNavLink) => {
            adminNavLink.addEventListener('click', closeAdminMenu);
        });

        document.querySelectorAll('[data-image-remove-toggle]').forEach((button) => {
            const imageField = button.closest('[data-image-field]');
            const removeImageInput = imageField.querySelector('[data-image-remove-value]');
            const imagePreview = imageField.querySelector('[data-current-image-preview]');
            const imageInput = imageField.querySelector('input[type="file"][name="image"]');

            const setImageRemoval = (shouldRemove) => {
                removeImageInput.value = shouldRemove ? '1' : '0';
                imagePreview.hidden = shouldRemove;
                button.setAttribute('aria-pressed', String(shouldRemove));
                button.textContent = shouldRemove ? 'Giữ ảnh hiện tại' : 'Xóa ảnh hiện tại';
            };

            button.addEventListener('click', () => {
                setImageRemoval(removeImageInput.value !== '1');
            });

            imageInput.addEventListener('change', () => {
                if (imageInput.files.length > 0) {
                    setImageRemoval(false);
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAdminMenu();
            }
        });
    </script>
</body>

</html>
