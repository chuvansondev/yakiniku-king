@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-page {
            --dashboard-ink: #202722;
            --dashboard-muted: #727a72;
            --dashboard-line: #e2e7e2;
            --dashboard-paper: #fff;
            --dashboard-canvas: #f3f5f2;
            --dashboard-red: #c84b3b;
            --dashboard-green: #3c765e;
            --dashboard-blue: #527895;
            --dashboard-amber: #a97622;
            color: var(--dashboard-ink);
            max-width: 1560px;
            margin: 0 auto;
            padding-bottom: 20px;
        }

        .dashboard-page a {
            color: inherit;
        }

        .dashboard-hero {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            padding: 30px 32px;
            margin-bottom: 20px;
            border: 1px solid #303b34;
            border-left: 5px solid var(--dashboard-red);
            border-radius: 14px;
            background: var(--dashboard-ink);
            color: #fff;
            box-shadow: 0 12px 30px rgb(32 39 34 / 10%);
        }

        .dashboard-eyebrow,
        .dashboard-stat-label,
        .dashboard-panel-kicker {
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .dashboard-hero .dashboard-eyebrow {
            color: #c4d0c7;
        }

        .dashboard-hero h1 {
            margin: 8px 0 6px;
            color: #fff;
            font-family: Arial, 'Segoe UI', sans-serif;
            font-size: 34px;
            font-weight: 700;
        }

        .dashboard-hero-copy {
            margin: 0;
            color: #d6ddd7;
            font-size: 14px;
        }

        .dashboard-hero-tools {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
        }

        .dashboard-date {
            padding: 11px 14px;
            border: 1px solid #56615a;
            color: #e8ede9;
            font-size: 13px;
        }

        .dashboard-button {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border: 1px solid transparent;
            border-radius: 7px;
            background: var(--dashboard-red);
            color: #fff !important;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color 150ms ease;
        }

        .dashboard-button:hover {
            background: #ad3c30;
        }

        .dashboard-button:focus-visible,
        .dashboard-resource-link:focus-visible,
        .dashboard-lead-link:focus-visible,
        .dashboard-panel-link:focus-visible {
            outline: 3px solid #e29a47;
            outline-offset: 2px;
        }

        .dashboard-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .dashboard-stat {
            min-height: 145px;
            padding: 19px 20px;
            border: 1px solid var(--dashboard-line);
            border-top: 3px solid var(--dashboard-red);
            border-radius: 12px;
            background: var(--dashboard-paper);
            box-shadow: 0 3px 12px rgb(32 39 34 / 3%);
            transition: transform 150ms ease, box-shadow 150ms ease;
        }

        .dashboard-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 9px 22px rgb(32 39 34 / 8%);
        }

        .dashboard-stat:nth-child(2) {
            border-top-color: var(--dashboard-amber);
        }

        .dashboard-stat:nth-child(3) {
            border-top-color: var(--dashboard-blue);
        }

        .dashboard-stat:nth-child(4) {
            border-top-color: var(--dashboard-green);
        }

        .dashboard-stat-label {
            display: block;
        }

        .dashboard-stat-value {
            display: block;
            margin: 12px 0 4px;
            font-size: 34px;
            font-weight: 700;
            line-height: 1;
        }

        .dashboard-stat-note {
            color: var(--dashboard-muted);
            font-size: 12px;
        }

        .dashboard-columns {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(280px, .8fr);
            align-items: start;
            gap: 18px;
        }

        .dashboard-main-column,
        .dashboard-side-column {
            display: grid;
            min-width: 0;
            gap: 18px;
        }

        .dashboard-panel {
            min-width: 0;
            border: 1px solid var(--dashboard-line);
            border-radius: 12px;
            background: var(--dashboard-paper);
            box-shadow: 0 3px 12px rgb(32 39 34 / 3%);
            overflow: hidden;
        }

        .dashboard-panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 17px 20px;
            border-bottom: 1px solid var(--dashboard-line);
        }

        .dashboard-panel-heading h2 {
            margin: 4px 0 0;
            font-size: 17px;
            font-weight: 700;
        }

        .dashboard-panel-link {
            color: var(--dashboard-red) !important;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .dashboard-panel-link:hover {
            text-decoration: underline;
        }

        .dashboard-trend-summary {
            display: flex;
            align-items: baseline;
            gap: 8px;
            padding: 16px 20px 0;
        }

        .dashboard-trend-summary strong {
            font-size: 24px;
        }

        .dashboard-trend-summary span {
            color: var(--dashboard-muted);
            font-size: 12px;
        }

        .dashboard-trend-chart {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 10px;
            min-height: 190px;
            padding: 14px 20px 18px;
        }

        .dashboard-trend-day {
            display: grid;
            grid-template-rows: 20px 1fr auto;
            min-width: 0;
            text-align: center;
        }

        .dashboard-trend-count {
            color: var(--dashboard-muted);
            font-size: 11px;
        }

        .dashboard-trend-track {
            display: flex;
            min-height: 112px;
            align-items: flex-end;
            justify-content: center;
            border-radius: 5px 5px 0 0;
            background: linear-gradient(to top, #f7f8f6, #fff);
            border-bottom: 1px solid var(--dashboard-line);
        }

        .dashboard-trend-bar {
            display: block;
            width: min(30px, 62%);
            min-height: 3px;
            background: var(--dashboard-green);
            border-radius: 5px 5px 0 0;
            transition: height 250ms ease;
        }

        .dashboard-trend-day:last-child .dashboard-trend-bar {
            background: var(--dashboard-red);
        }

        .dashboard-trend-day time {
            display: grid;
            gap: 3px;
            padding-top: 8px;
            color: var(--dashboard-ink);
            font-size: 11px;
            font-weight: 700;
        }

        .dashboard-trend-day time span {
            color: var(--dashboard-muted);
            font-size: 10px;
            font-weight: 400;
        }

        .dashboard-table-wrap {
            overflow-x: auto;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            white-space: nowrap;
        }

        .dashboard-table th {
            padding: 11px 16px;
            background: var(--dashboard-canvas);
            color: var(--dashboard-muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .dashboard-table td {
            padding: 13px 16px;
            border-top: 1px solid var(--dashboard-line);
            font-size: 12px;
        }

        .dashboard-table tbody tr:hover {
            background: #fafbf9;
        }

        .dashboard-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .dashboard-booking-code {
            color: var(--dashboard-red);
            font-weight: 700;
        }

        .dashboard-status {
            display: inline-flex;
            align-items: center;
            padding: 5px 8px;
            font-size: 10px;
            font-weight: 700;
        }

        .dashboard-status-pending {
            background: #fff3d9;
            color: #895c12;
        }

        .dashboard-status-confirmed,
        .dashboard-status-completed {
            background: #e9f4ed;
            color: #2e684e;
        }

        .dashboard-status-cancelled {
            background: #fcebea;
            color: #a83e37;
        }

        .dashboard-empty {
            padding: 24px 20px;
            color: var(--dashboard-muted);
            font-size: 13px;
            text-align: center;
        }

        .dashboard-lead-list,
        .dashboard-resource-list,
        .dashboard-action-list {
            display: grid;
        }

        .dashboard-lead-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--dashboard-line);
            text-decoration: none;
        }

        .dashboard-lead-link:last-child,
        .dashboard-resource-link:last-child,
        .dashboard-action-link:last-child {
            border-bottom: 0;
        }

        .dashboard-lead-link:hover,
        .dashboard-resource-link:hover,
        .dashboard-action-link:hover {
            background: var(--dashboard-canvas);
        }

        .dashboard-lead-link:focus-visible,
        .dashboard-action-link:focus-visible,
        .dashboard-resource-link:focus-visible {
            position: relative;
            z-index: 1;
            outline: 3px solid #e29a47;
            outline-offset: -3px;
        }

        .dashboard-priority {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 15px;
            margin-bottom: 20px;
            border: 1px solid #f0dfbd;
            border-left: 4px solid #c58a2c;
            border-radius: 12px;
            background: linear-gradient(100deg, #fffaf0, #fffdf8);
            color: #674b1e;
            font-size: 13px;
            box-shadow: 0 5px 16px rgb(103 75 30 / 5%);
        }

        .dashboard-priority__icon {
            display: grid;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            place-items: center;
            border-radius: 50%;
            background: #f7e8c9;
            color: #8b5c12;
            font-size: 17px;
            font-weight: 800;
        }

        .dashboard-priority__copy {
            display: grid;
            flex: 1;
            gap: 2px;
        }

        .dashboard-priority__copy strong {
            color: #513b18;
            font-size: 13px;
        }

        .dashboard-priority__copy span {
            color: #80683f;
            font-size: 11px;
        }

        .dashboard-priority a {
            display: inline-flex;
            min-height: 36px;
            align-items: center;
            justify-content: center;
            padding: 7px 12px;
            border: 1px solid #e4c68e;
            border-radius: 7px;
            background: #fffdf8;
            color: #80500e !important;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .dashboard-priority a:hover {
            border-color: #c58a2c;
            background: #fdf3df;
        }

        .dashboard-lead-name {
            display: block;
            font-size: 13px;
            font-weight: 700;
        }

        .dashboard-lead-contact {
            display: block;
            margin-top: 3px;
            color: var(--dashboard-muted);
            font-size: 11px;
        }

        .dashboard-lead-date {
            color: var(--dashboard-muted);
            font-size: 10px;
            white-space: nowrap;
        }

        .dashboard-resource-link,
        .dashboard-action-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 18px;
            border-bottom: 1px solid var(--dashboard-line);
            color: var(--dashboard-ink) !important;
            font-size: 12px;
            text-decoration: none;
        }

        .dashboard-resource-link strong {
            color: var(--dashboard-muted);
            font-size: 12px;
        }

        .dashboard-action-link span:last-child {
            display: grid;
            width: 26px;
            height: 26px;
            place-items: center;
            border-radius: 50%;
            background: #fbefed;
            color: var(--dashboard-red);
            font-weight: 700;
        }

        @media (max-width: 1180px) {
            .dashboard-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-columns {
                grid-template-columns: minmax(0, 1.4fr) minmax(250px, .8fr);
            }
        }

        @media (max-width: 880px) {
            .dashboard-columns {
                grid-template-columns: 1fr;
            }

            .dashboard-side-column {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                align-items: start;
            }
        }

        @media (max-width: 600px) {
            .dashboard-hero {
                align-items: flex-start;
                flex-direction: column;
                padding: 22px 18px;
                border-radius: 10px;
            }

            .dashboard-hero h1 {
                font-size: 29px;
            }

            .dashboard-hero-tools {
                width: 100%;
                justify-content: flex-start;
            }

            .dashboard-hero-tools .dashboard-button {
                flex: 1 1 auto;
            }

            .dashboard-priority {
                align-items: flex-start;
                flex-wrap: wrap;
                gap: 10px;
            }

            .dashboard-priority__copy {
                min-width: calc(100% - 50px);
            }

            .dashboard-priority a {
                margin-left: 44px;
            }

            .dashboard-stat-grid,
            .dashboard-side-column {
                grid-template-columns: 1fr;
            }

            .dashboard-stat {
                min-height: 126px;
            }

            .dashboard-stat-value {
                font-size: 30px;
            }

            .dashboard-panel-heading {
                align-items: flex-start;
            }

            .dashboard-trend-chart {
                gap: 5px;
                padding-inline: 12px;
            }
        }
    </style>

    <div class="dashboard-page">
        <header class="dashboard-hero">
            <div>
                <p class="dashboard-eyebrow">Yakiniku King / Trung tâm vận hành</p>
                <h1>Xin chào, {{ auth()->user()->name }}</h1>
                <p class="dashboard-hero-copy">Theo dõi hoạt động nhà hàng và xử lý các yêu cầu mới nhất.</p>
            </div>
            <div class="dashboard-hero-tools">
                <time class="dashboard-date" datetime="{{ now()->toDateString() }}">{{ now()->format('d/m/Y') }}</time>
                <a class="dashboard-button" href="{{ route('admin.menu.bookings.create') }}">+ Tạo đặt bàn</a>
                <a class="dashboard-button" href="{{ route('admin.menu.items.create') }}">+ Thêm món ăn</a>
            </div>
        </header>

        @if ($pendingBookingsCount > 0)
            <aside class="dashboard-priority" aria-label="Việc cần ưu tiên">
                <span class="dashboard-priority__icon" aria-hidden="true">!</span>
                <span class="dashboard-priority__copy">
                    <strong>Cần xác nhận đặt bàn</strong>
                    <span>{{ number_format($pendingBookingsCount) }} yêu cầu đang chờ xử lý.</span>
                </span>
                <a href="{{ route('admin.menu.bookings.index') }}">Xem và xử lý <span aria-hidden="true">&rarr;</span></a>
            </aside>
        @endif

        <section class="dashboard-stat-grid" aria-label="Tổng quan hoạt động">
            <article class="dashboard-stat">
                <span class="dashboard-stat-label">Đặt bàn hôm nay</span>
                <strong class="dashboard-stat-value">{{ number_format($todayBookingsCount) }}</strong>
                <span class="dashboard-stat-note">{{ number_format($bookingsCount) }} lượt đặt bàn toàn hệ thống</span>
            </article>
            <article class="dashboard-stat">
                <span class="dashboard-stat-label">Chờ xác nhận</span>
                <strong class="dashboard-stat-value">{{ number_format($pendingBookingsCount) }}</strong>
                <span class="dashboard-stat-note">Yêu cầu cần được xử lý</span>
            </article>
            <article class="dashboard-stat">
                <span class="dashboard-stat-label">Lead mới</span>
                <strong class="dashboard-stat-value">{{ number_format($newLeadsCount) }}</strong>
                <span class="dashboard-stat-note">Khách hàng đang chờ liên hệ</span>
            </article>
            <article class="dashboard-stat">
                <span class="dashboard-stat-label">Món ăn</span>
                <strong class="dashboard-stat-value">{{ number_format($menuItemsCount) }}</strong>
                <span class="dashboard-stat-note">{{ number_format($combosCount) }} combo trong thực đơn</span>
            </article>
        </section>

        <div class="dashboard-columns">
            <div class="dashboard-main-column">
                <section class="dashboard-panel" aria-labelledby="booking-trend-title">
                    <header class="dashboard-panel-heading">
                        <div>
                            <p class="dashboard-panel-kicker">Nhịp độ hoạt động</p>
                            <h2 id="booking-trend-title">Đặt bàn trong 7 ngày</h2>
                        </div>
                        <a class="dashboard-panel-link" href="{{ route('admin.menu.bookings.index') }}">Quản lý đặt bàn &rarr;</a>
                    </header>
                    <div class="dashboard-trend-summary">
                        <strong>{{ number_format($bookingTrend->sum('count')) }}</strong>
                        <span>lượt đặt bàn từ {{ $bookingTrend->first()['label'] }} đến hôm nay</span>
                    </div>
                    <div class="dashboard-trend-chart" role="img" aria-label="Số lượt đặt bàn theo ngày trong 7 ngày gần nhất">
                        @foreach ($bookingTrend as $day)
                            <div class="dashboard-trend-day" aria-label="{{ $day['weekday'] }} {{ $day['label'] }}: {{ $day['count'] }} lượt">
                                <span class="dashboard-trend-count">{{ $day['count'] }}</span>
                                <div class="dashboard-trend-track">
                                    <span class="dashboard-trend-bar" style="height: {{ $day['count'] > 0 ? max(10, (int) round($day['count'] / $bookingTrendMax * 100)) : 3 }}%"></span>
                                </div>
                                <time datetime="{{ $day['date'] }}">{{ $day['weekday'] }}<span>{{ $day['label'] }}</span></time>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="dashboard-panel" aria-labelledby="recent-bookings-title">
                    <header class="dashboard-panel-heading">
                        <div>
                            <p class="dashboard-panel-kicker">Cập nhật mới nhất</p>
                            <h2 id="recent-bookings-title">Đặt bàn gần đây</h2>
                        </div>
                        <a class="dashboard-panel-link" href="{{ route('admin.menu.bookings.index') }}">Xem tất cả &rarr;</a>
                    </header>
                    <div class="dashboard-table-wrap">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Mã đặt bàn</th>
                                    <th>Khách hàng</th>
                                    <th>Nhà hàng</th>
                                    <th>Lịch đặt</th>
                                    <th>Số khách</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentBookings as $booking)
                                    <tr>
                                        <td class="dashboard-booking-code">{{ $booking->booking_code }}</td>
                                        <td>{{ $booking->customer_name }}</td>
                                        <td>{{ $booking->restaurant->name ?? '—' }}</td>
                                        <td>{{ $booking->booking_date?->format('d/m') }} · {{ $booking->booking_time }}</td>
                                        <td>{{ $booking->number_of_guests }} khách</td>
                                        <td>
                                            @if ($booking->status === \App\Enums\BookingStatus::Pending->value)
                                                <span class="dashboard-status dashboard-status-pending">Chờ xác nhận</span>
                                            @elseif ($booking->status === \App\Enums\BookingStatus::Confirmed->value)
                                                <span class="dashboard-status dashboard-status-confirmed">Đã xác nhận</span>
                                            @elseif ($booking->status === \App\Enums\BookingStatus::Cancelled->value)
                                                <span class="dashboard-status dashboard-status-cancelled">Đã hủy</span>
                                            @elseif ($booking->status === \App\Enums\BookingStatus::Completed->value)
                                                <span class="dashboard-status dashboard-status-completed">Hoàn thành</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="dashboard-empty" colspan="6">Chưa có đặt bàn nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <aside class="dashboard-side-column">
                <section class="dashboard-panel" aria-labelledby="new-leads-title">
                    <header class="dashboard-panel-heading">
                        <div>
                            <p class="dashboard-panel-kicker">Cần liên hệ</p>
                            <h2 id="new-leads-title">Lead mới</h2>
                        </div>
                        <a class="dashboard-panel-link" href="{{ route('admin.menu.leads.index') }}">Tất cả &rarr;</a>
                    </header>
                    <div class="dashboard-lead-list">
                        @forelse ($recentLeads as $lead)
                            <a class="dashboard-lead-link" href="{{ route('admin.menu.leads.edit', $lead) }}">
                                <span>
                                    <span class="dashboard-lead-name">{{ $lead->name }}</span>
                                    <span class="dashboard-lead-contact">{{ $lead->phone ?: ($lead->email ?: 'Chưa có thông tin liên hệ') }}</span>
                                </span>
                                <time class="dashboard-lead-date" datetime="{{ $lead->created_at->toDateString() }}">{{ $lead->created_at->format('d/m') }}</time>
                            </a>
                        @empty
                            <p class="dashboard-empty">Không có lead mới cần xử lý.</p>
                        @endforelse
                    </div>
                </section>

                <section class="dashboard-panel" aria-labelledby="quick-actions-title">
                    <header class="dashboard-panel-heading">
                        <div>
                            <p class="dashboard-panel-kicker">Lối tắt</p>
                            <h2 id="quick-actions-title">Truy cập nhanh</h2>
                        </div>
                    </header>
                    <nav class="dashboard-action-list" aria-label="Truy cập nhanh">
                        <a class="dashboard-action-link" href="{{ route('admin.menu.bookings.create') }}"><span>Tạo đặt bàn</span><span>+</span></a>
                        <a class="dashboard-action-link" href="{{ route('admin.menu.items.create') }}"><span>Thêm món ăn</span><span>+</span></a>
                        <a class="dashboard-action-link" href="{{ route('admin.menu.combos.create') }}"><span>Thêm combo</span><span>+</span></a>
                        <a class="dashboard-action-link" href="{{ route('admin.menu.promotions.create') }}"><span>Tạo khuyến mãi</span><span>+</span></a>
                    </nav>
                </section>

                <section class="dashboard-panel" aria-labelledby="content-overview-title">
                    <header class="dashboard-panel-heading">
                        <div>
                            <p class="dashboard-panel-kicker">Quản lý nội dung</p>
                            <h2 id="content-overview-title">Danh mục hệ thống</h2>
                        </div>
                    </header>
                    <nav class="dashboard-resource-list" aria-label="Danh mục hệ thống">
                        <a class="dashboard-resource-link" href="{{ route('admin.menu.combos.index') }}"><span>Combo</span><strong>{{ number_format($combosCount) }}</strong></a>
                        <a class="dashboard-resource-link" href="{{ route('admin.menu.promotions.index') }}"><span>Khuyến mãi</span><strong>{{ number_format($promotionsCount) }}</strong></a>
                        <a class="dashboard-resource-link" href="{{ route('admin.menu.restaurants.index') }}"><span>Nhà hàng</span><strong>{{ number_format($restaurantsCount) }}</strong></a>
                    </nav>
                </section>
            </aside>
        </div>
    </div>
@endsection
