@extends('frontend.layouts.app')

@section('title', __('Đặt bàn'))

@push('styles')
    <style>
        .booking-page {
            min-height: calc(100svh - 80px);
            display: flex;
            align-items: center;
            padding-block: clamp(2.5rem, 6vw, 5rem);
            background: linear-gradient(105deg, rgb(17 24 20 / 88%), rgb(17 24 20 / 48%)), url('{{ asset('yakiniku-king/bg-about.jpg') }}') center / cover;
        }

        .booking-intro {
            max-width: 390px;
            color: #fff;
        }

        .booking-eyebrow {
            color: #ff0000;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .booking-title {
            margin: .75rem 0 1rem;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 3.8rem;
            font-weight: 500;
            line-height: 1;
        }

        .booking-form-panel {
            padding: clamp(1.4rem, 4vw, 2.5rem);
            border-top: 4px solid #ff0000;
            background: #f6f4ee;
            box-shadow: 0 24px 70px rgb(0 0 0 / 25%);
        }

        .booking-form-title {
            margin: 0;
            color: #29241f;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 2rem;
            font-weight: 500;
        }

        .booking-field label {
            display: block;
            margin-bottom: .45rem;
            color: #3d3934;
            font-size: .9rem;
            font-weight: 600;
        }

        .booking-required-mark {
            margin-left: .15rem;
            color: #c62828;
            font-weight: 700;
        }

        .booking-field .form-control,
        .booking-field .form-select {
            min-height: 48px;
            border-color: #d6d0c7;
            border-radius: 2px;
            background-color: #fff;
        }

        .booking-field .form-control:focus,
        .booking-field .form-select:focus {
            border-color: #a83d32;
            box-shadow: 0 0 0 .2rem rgb(168 61 50 / 14%);
        }

        .booking-time-note {
            margin-top: .45rem;
            color: #67635d;
            font-size: .8rem;
        }

        .booking-table-fieldset {
            min-width: 0;
            margin: 0;
            padding: 0;
            border: 0;
        }

        .booking-table-title {
            margin: 0 0 .35rem;
            color: #29241f;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .booking-table-help {
            margin-bottom: .8rem;
            color: #67635d;
            font-size: .85rem;
            line-height: 1.55;
        }

        .booking-table-grid {
            position: relative;
            display: grid;
            grid-template-columns: repeat(3, minmax(70px, 1fr));
            gap: 1rem 1.25rem;
            min-height: 190px;
            padding: 2.4rem 1.5rem 1.25rem;
            border: 1px solid #d8d0c4;
            border-radius: 5px;
            background: #fff;
        }

        .booking-table-option {
            position: relative;
            display: grid;
            min-width: 70px;
            min-height: 62px;
            place-items: center;
            padding: .2rem;
            border: 0;
            border-radius: 4px;
            background: transparent;
            cursor: pointer;
            transition: transform .16s ease;
        }

        .booking-table-option:has(input:checked) {
            background: rgb(168 61 50 / 8%);
        }

        .booking-table-option:hover:not(.is-unavailable) {
            transform: scale(1.06);
        }

        .booking-table-option.is-unavailable {
            color: #817770;
            cursor: not-allowed;
        }

        .booking-table-option.is-too-small {
            color: #8b806e;
            cursor: not-allowed;
        }

        .booking-table-option input {
            position: absolute;
            z-index: 2;
            inset: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            opacity: 0;
            cursor: inherit;
        }

        .booking-table-icon { position: absolute; z-index: 1; width: 58px; height: 58px; overflow: visible; pointer-events: none; }
        .booking-chair { fill: #322d29; transition: fill .16s ease; }
        .booking-table-surface { fill: #754632; transition: fill .16s ease; }
        .booking-table-option:has(input:checked) .booking-table-surface { fill: #a83d32; }
        .booking-table-option.is-unavailable .booking-chair,
        .booking-table-option.is-unavailable .booking-table-surface,
        .booking-table-option.is-too-small .booking-chair,
        .booking-table-option.is-too-small .booking-table-surface { fill: #b9b9b9; }

        .booking-table-code {
            position: relative;
            z-index: 1;
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            line-height: 1.1;
            pointer-events: none;
        }

        .booking-table-capacity {
            position: absolute;
            top: calc(100% - .2rem);
            color: #67635d;
            font-size: .8rem;
            white-space: nowrap;
            pointer-events: none;
        }

        .booking-floor-plan { overflow: hidden; border: 1px solid #e2ddd4; border-radius: 5px; }
        .booking-plan-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: .75rem; }
        .booking-floor-tabs { display: flex; justify-content: center; gap: clamp(1.5rem, 8vw, 5rem); margin: .5rem 0 0; border-bottom: 1px solid #d8d0c4; }
        .booking-floor-tab { min-width: 5rem; padding: .55rem .35rem; border: 0; border-bottom: 2px solid transparent; background: transparent; color: #625b54; font-size: .92rem; font-weight: 600; cursor: pointer; }
        .booking-floor-tab[aria-selected="true"] { border-bottom-color: #a83d32; color: #87332b; }
        .booking-floor-tab:focus-visible { outline: 2px solid #a83d32; outline-offset: 2px; }
        .booking-plan-caption { margin: 0; color: #5c5750; font-size: .82rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; }
        .booking-plan-legend { display: flex; flex-wrap: wrap; gap: .5rem 1rem; margin-top: .85rem; color: #5f5a53; font-size: .78rem; }
        .booking-plan-legend span { display: inline-flex; align-items: center; gap: .4rem; }
        .booking-legend-dot { width: .7rem; height: .7rem; border-radius: 2px; background: #754632; }
        .booking-legend-dot.selected { background: #a83d32; }
        .booking-legend-dot.unavailable,
        .booking-legend-dot.too-small { background: #b9b9b9; }
        .booking-plan-summary { display: flex; justify-content: space-between; gap: .75rem; margin-top: .85rem; padding: .8rem 1rem; border-radius: 4px; background: #f3f0e9; color: #4c4740; font-size: .88rem; }
        .booking-plan-summary strong { color: #87332b; }
        .booking-room-label { position: absolute; left: 50%; top: 0; padding: .3rem .8rem; border-radius: 0 0 5px 5px; background: #d8d1c6; color: #514b43; font-size: .72rem; font-weight: 700; letter-spacing: .08em; transform: translateX(-50%); }

        .booking-preorder {
            padding-top: .5rem;
        }

        .booking-preorder-title {
            margin: 0 0 .35rem;
            color: #29241f;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .booking-preorder-description {
            margin-bottom: .9rem;
            color: #67635d;
            font-size: .9rem;
        }

        .booking-preorder-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem;
        }

        .booking-preorder-option {
            display: flex;
            min-width: 0;
            min-height: 96px;
            align-items: center;
            gap: .8rem;
            padding: .7rem;
            border: 1px solid #d6d0c7;
            border-radius: 2px;
            background: #fff;
            cursor: pointer;
            transition: border-color .18s ease, background-color .18s ease;
        }

        .booking-preorder-option:has(.booking-preorder-checkbox:checked) {
            border-color: #a83d32;
            background: #fff9f7;
        }

        .booking-preorder-checkbox {
            width: 1.1rem;
            height: 1.1rem;
            flex: 0 0 auto;
            accent-color: #a83d32;
        }

        .booking-preorder-image {
            width: 64px;
            height: 64px;
            flex: 0 0 auto;
            object-fit: cover;
        }

        .booking-preorder-details {
            display: grid;
            min-width: 0;
            gap: .2rem;
        }

        .booking-preorder-name {
            overflow-wrap: anywhere;
            color: #29241f;
            font-size: .9rem;
            font-weight: 700;
        }

        .booking-preorder-copy {
            display: -webkit-box;
            overflow: hidden;
            color: #67635d;
            font-size: .78rem;
            line-height: 1.4;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .booking-preorder-price {
            color: #87332b;
            font-size: .82rem;
            font-weight: 700;
        }

        .booking-preorder-empty {
            margin: 0;
            color: #67635d;
            font-size: .9rem;
        }

        .booking-terms {
            margin: 0;
            padding: 1rem 1.1rem;
            border-left: 3px solid #d7b36a;
            background: #eeece5;
            color: #49453f;
            font-size: .86rem;
            line-height: 1.65;
        }

        .booking-submit {
            min-height: 52px;
            border: 0;
            border-radius: 2px;
            background: #a83d32;
            color: #fff;
            font-weight: 700;
        }

        .booking-submit:hover {
            background: #87332b;
            color: #fff;
        }

        .booking-actions {
            display: grid;
            grid-template-columns: minmax(100px, .4fr) minmax(0, 1fr);
            gap: .75rem;
        }

        .booking-cancel {
            min-height: 52px;
            border: 1px solid #8b8b84;
            border-radius: 2px;
            color: #3d3934;
            font-weight: 600;
        }

        .booking-cancel:hover,
        .booking-cancel:focus-visible {
            border-color: #5c5c56;
            background: #e8e6df;
            color: #29241f;
        }

        @media (max-width: 767.98px) {
            .booking-page {
                align-items: flex-start;
                padding-block: 1.5rem;
                background-position: center;
            }

            .booking-title {
                font-size: 2.7rem;
            }

            .booking-intro {
                max-width: none;
            }

            .booking-preorder-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .booking-table-grid { grid-template-columns: repeat(3, minmax(65px, 1fr)); gap: .8rem .35rem; padding-inline: .5rem; }
            .booking-table-option { min-width: 65px; }

            .booking-plan-header {
                align-items: flex-start;
                flex-direction: column;
                gap: .25rem;
            }

            .booking-plan-summary {
                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .booking-submit {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <section class="booking-page">
        <div class="container">
            <div class="row align-items-start justify-content-between g-4 g-lg-5">
                <div class="col-12 col-lg-4 order-2 order-lg-1">
                    <div class="booking-intro">
                        <p class="booking-eyebrow mb-0">Yakiniku King</p>
                        <h1 class="booking-title">{{ __('Đặt bàn của bạn') }}</h1>
                        <p class="mb-3">{{ __('Hẹn một bữa ăn ngon cùng gia đình và bạn bè. Nhà hàng sẽ liên hệ xác nhận yêu cầu đặt bàn.') }}</p>
                        <p class="mb-0">{{ __('12 Phố Hàng Gai, Quận Hoàn Kiếm, Hà Nội') }}</p>
                    </div>
                </div>

                <div class="col-12 col-lg-8 order-1 order-lg-2">
                    <div class="booking-form-panel">
                        <div class="mb-4">
                            <p class="booking-eyebrow mb-1">{{ __('Đặt bàn') }}</p>
                            <h2 class="booking-form-title">{{ __('Thông tin đặt bàn') }}</h2>
                        </div>

                        @if (session('booking_success'))
                            <div class="alert alert-success" role="status">{{ __('Đã gửi thông tin đặt bàn.') }}</div>
                        @endif

                        @if (session('booking_cancelled'))
                            <div class="alert alert-info" role="status">{{ __('Đặt bàn đã được hủy.') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                {{ __('Vui lòng kiểm tra lại thông tin đặt bàn.') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('booking.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12 booking-field">
                                    <label for="restaurant_id">{{ __('Nhà hàng') }}</label>
                                    <select id="restaurant_id" name="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror" required>
                                        <option value="">{{ __('Chọn nhà hàng') }}</option>
                                        @foreach ($restaurants as $restaurant)
                                            <option value="{{ $restaurant->id }}" @selected(old('restaurant_id') == $restaurant->id)>
                                                {{ localized_text($restaurant, 'name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('restaurant_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 booking-field">
                                    <label for="customer_name">{{ __('Họ và tên') }} <span class="booking-required-mark" aria-hidden="true">*</span><span class="visually-hidden">({{ __('Bắt buộc') }})</span></label>
                                    <input id="customer_name" name="customer_name" type="text" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" autocomplete="name" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 booking-field">
                                    <label for="phone">{{ __('Số điện thoại') }} <span class="booking-required-mark" aria-hidden="true">*</span><span class="visually-hidden">({{ __('Bắt buộc') }})</span></label>
                                    <input id="phone" name="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" autocomplete="tel" required>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 booking-field">
                                    <label for="email">{{ __('Email') }} <span class="text-muted">({{ __('không bắt buộc') }})</span></label>
                                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 booking-field">
                                    <label for="number_of_guests">{{ __('Số lượng người') }} <span class="booking-required-mark" aria-hidden="true">*</span><span class="visually-hidden">({{ __('Bắt buộc') }})</span></label>
                                    <input id="number_of_guests" name="number_of_guests" type="number" min="1" max="100" class="form-control @error('number_of_guests') is-invalid @enderror" value="{{ old('number_of_guests', 2) }}" required>
                                    @error('number_of_guests')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 booking-field booking-note">
                                    <label for="note">{{ __('Ghi chú') }}</label>
                                    <input id="note" name="note" type="text" maxlength="2000" class="form-control @error('note') is-invalid @enderror" value="{{ old('note') }}">
                                    @error('note')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 booking-field">
                                    <label for="booking_date">{{ __('Ngày đặt') }}</label>
                                    <input id="booking_date" name="booking_date" type="date" min="{{ now()->toDateString() }}" class="form-control @error('booking_date') is-invalid @enderror" value="{{ old('booking_date') }}" required>
                                    @error('booking_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 booking-field">
                                    <label for="booking_time">{{ __('Giờ đặt') }}</label>
                                    <input id="booking_time" name="booking_time" type="time" class="form-control @error('booking_time') is-invalid @enderror" value="{{ old('booking_time') }}" required>
                                    <div class="booking-time-note">{{ __('Thời gian giữ bàn tối đa 10 phút') }}</div>
                                    @error('booking_time')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <fieldset class="col-12 booking-table-fieldset" aria-describedby="bookingTableHelp">
                                    <legend class="booking-table-title">{{ __('Chọn bàn') }}</legend>
                                    <label class="visually-hidden" for="floor">{{ __('Chọn tầng') }}</label>
                                    <select id="floor" name="floor" class="visually-hidden @error('floor') is-invalid @enderror" required>
                                        @foreach ([1, 2] as $floor)
                                            <option value="{{ $floor }}" @selected(old('floor', 1) == $floor)>{{ __('Tầng') }} {{ $floor }}</option>
                                        @endforeach
                                    </select>
                                    @error('floor')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <p class="booking-table-help" id="bookingTableHelp">
                                        {{ __('Chọn một hoặc nhiều bàn ở tầng đã chọn để đủ chỗ cho số khách. Mỗi tầng có tổng sức chứa :capacity người; với đoàn đông hơn, nhà hàng sẽ bố trí bàn khi xác nhận.', ['capacity' => $maximumFloorCapacity]) }}
                                    </p>
                                    <div class="booking-plan-header">
                                        <p class="booking-plan-caption">{{ __('Sơ đồ nhà hàng') }}</p>
                                        <span class="small text-secondary" id="bookingAvailabilityMessage" aria-live="polite">{{ __('Chọn ngày và giờ để xem tình trạng bàn') }}</span>
                                    </div>
                                    <div class="booking-floor-tabs" role="tablist" aria-label="{{ __('Chọn tầng') }}">
                                        @foreach ([1, 2] as $floorTab)
                                            <button class="booking-floor-tab" id="floor-tab-{{ $floorTab }}" type="button" role="tab" aria-controls="bookingFloorPlan" aria-selected="{{ (int) old('floor') === $floorTab ? 'true' : 'false' }}" data-floor-tab="{{ $floorTab }}">{{ __('Tầng') }} {{ $floorTab }}</button>
                                        @endforeach
                                    </div>
                                    <div class="booking-table-grid" id="bookingFloorPlan" role="tabpanel" aria-labelledby="floor-tab-{{ old('floor', 1) }}">
                                        <span class="booking-room-label">{{ __('KHU VỰC ĂN UỐNG') }}</span>
                                        @foreach ($tableCapacities as $tableCode => $capacity)
                                            @php($unavailable = in_array($tableCode, $unavailableTables ?? [], true))
                                            <label class="booking-table-option {{ $unavailable ? 'is-unavailable' : '' }}" for="table_{{ $tableCode }}" data-floor="{{ $tableFloors[$tableCode] }}" data-capacity="{{ $capacity }}">
                                                <input
                                                    id="table_{{ $tableCode }}"
                                                    name="table_codes[]"
                                                    type="checkbox"
                                                    value="{{ $tableCode }}"
                                                    aria-label="{{ __('Bàn') }} {{ $tableCode }} — {{ $capacity }} {{ __('người') }}{{ $unavailable ? ', '.__('Đã được đặt') : '' }}"
                                                    @disabled($unavailable)
                                                    @checked(in_array($tableCode, (array) old('table_codes', [])))>
                                                <svg class="booking-table-icon" viewBox="0 0 64 64" aria-hidden="true">
                                                    @if ($capacity === 2)
                                                        <circle class="booking-chair" cx="12" cy="32" r="4.5"/><circle class="booking-chair" cx="52" cy="32" r="4.5"/>
                                                    @elseif ($capacity === 4)
                                                        <circle class="booking-chair" cx="12" cy="20" r="4.5"/><circle class="booking-chair" cx="12" cy="44" r="4.5"/><circle class="booking-chair" cx="52" cy="20" r="4.5"/><circle class="booking-chair" cx="52" cy="44" r="4.5"/>
                                                    @else
                                                        <circle class="booking-chair" cx="12" cy="12" r="4.5"/><circle class="booking-chair" cx="12" cy="32" r="4.5"/><circle class="booking-chair" cx="12" cy="52" r="4.5"/>
                                                        <circle class="booking-chair" cx="52" cy="12" r="4.5"/><circle class="booking-chair" cx="52" cy="32" r="4.5"/><circle class="booking-chair" cx="52" cy="52" r="4.5"/>
                                                    @endif
                                                    <rect class="booking-table-surface" x="19" y="16" width="26" height="32" rx="2"/>
                                                </svg>
                                                <span class="booking-table-code">{{ $tableCode }}</span>
                                                <span class="booking-table-capacity">{{ $capacity }} {{ __('người') }}</span>
                                                <span class="visually-hidden booking-table-status">{{ $unavailable ? __('Đã được đặt') : __('Còn trống') }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="booking-plan-legend" aria-label="{{ __('Chú thích trạng thái bàn') }}">
                                        <span><i class="booking-legend-dot"></i>{{ __('Còn trống') }}</span>
                                        <span><i class="booking-legend-dot selected"></i>{{ __('Bạn đang chọn') }}</span>
                                        <span><i class="booking-legend-dot unavailable"></i>{{ __('Đã được đặt') }}</span>
                                        <span><i class="booking-legend-dot too-small"></i>{{ __('Không đủ chỗ') }}</span>
                                    </div>
                                    <div class="booking-plan-summary" aria-live="polite">
                                        <span id="bookingSelectedSummary">{{ __('Chưa chọn bàn') }}</span>
                                        <span><strong id="bookingCapacitySummary">0 / {{ old('number_of_guests', 2) }}</strong> {{ __('chỗ ngồi') }}</span>
                                    </div>
                                    @error('table_codes')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </fieldset>

                                <section class="col-12 booking-preorder" aria-labelledby="preOrderTitle">
                                    <h3 class="booking-preorder-title" id="preOrderTitle">{{ __('Gọi món trước') }}</h3>
                                    <p class="booking-preorder-description">{{ __('Chọn trước món Must Try để nhà hàng chuẩn bị cho bàn của bạn.') }}</p>

                                    @if ($mustTryMenuItems->isNotEmpty())
                                        <div class="booking-preorder-grid">
                                            @foreach ($mustTryMenuItems as $menuItem)
                                                <label class="booking-preorder-option" for="pre_order_item_{{ $menuItem->id }}">
                                                    <input
                                                        class="booking-preorder-checkbox"
                                                        id="pre_order_item_{{ $menuItem->id }}"
                                                        name="pre_order_items[]"
                                                        type="checkbox"
                                                        value="{{ $menuItem->id }}"
                                                        @checked(in_array($menuItem->id, (array) old('pre_order_items', [])))>
                                                    @if ($menuItem->image)
                                                        <img
                                                            class="booking-preorder-image"
                                                            src="{{ asset(str_starts_with($menuItem->image, 'menu/') ? 'storage/' . $menuItem->image : $menuItem->image) }}"
                                                            alt="{{ localized_text($menuItem, 'name') }}"
                                                            loading="lazy">
                                                    @endif
                                                    <span class="booking-preorder-details">
                                                        <span class="booking-preorder-name">{{ localized_text($menuItem, 'name') }}</span>
                                                        @if (localized_text($menuItem, 'description'))
                                                            <span class="booking-preorder-copy">{{ localized_text($menuItem, 'description') }}</span>
                                                        @endif
                                                        <span class="booking-preorder-price">{{ localized_price($menuItem->price) }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="booking-preorder-empty">{{ __('Hiện chưa có món Must Try để chọn trước.') }}</p>
                                    @endif

                                    @error('pre_order_items')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </section>

                                <div class="col-12">
                                    <p class="booking-terms">
                                        <strong>{{ __('Điều khoản và lưu ý đặt bàn') }}</strong><br>
                                        {{ __('Nhà hàng giữ bàn tối đa 15 phút, sau thời gian này xin phép phục vụ khách tiếp theo. Với đoàn đông từ 15 khách trở lên hoặc có yêu cầu đặc biệt, vui lòng chờ xác nhận đặt bàn thành công từ nhà hàng.') }}
                                    </p>
                                </div>

                                <div class="col-12 pt-1">
                                    <div class="booking-actions">
                                        <a class="btn booking-cancel d-inline-flex align-items-center justify-content-center" href="{{ route('home') }}">
                                            {{ __('Hủy bỏ') }}
                                        </a>
                                        <button class="btn booking-submit w-100" type="submit" @disabled($restaurants->isEmpty())>
                                            {{ __('Gửi yêu cầu đặt bàn') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($booking)
        <div class="modal fade" id="bookingConfirmationModal" tabindex="-1" aria-labelledby="bookingConfirmationTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <p class="booking-eyebrow mb-1">Yakiniku King</p>
                            <h2 class="modal-title booking-form-title" id="bookingConfirmationTitle">{{ __('Thông tin đặt bàn') }}</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Đóng') }}"></button>
                    </div>

                    <div class="modal-body pt-3">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                            <div>
                                <div class="small text-secondary">{{ __('Mã đặt bàn') }}</div>
                                <strong class="fs-5">{{ $booking->booking_code }}</strong>
                            </div>
                            @if ($booking->status === \App\Enums\BookingStatus::Confirmed->value)
                                <span class="badge rounded-pill text-bg-success px-3 py-2">{{ __('Đã xác nhận') }}</span>
                            @elseif ($booking->status === \App\Enums\BookingStatus::Pending->value)
                                <span class="badge rounded-pill text-bg-warning px-3 py-2">{{ __('Chưa xác nhận') }}</span>
                            @elseif ($booking->status === \App\Enums\BookingStatus::Cancelled->value)
                                <span class="badge rounded-pill text-bg-secondary px-3 py-2">{{ __('Đã hủy') }}</span>
                            @else
                                <span class="badge rounded-pill text-bg-primary px-3 py-2">{{ __('Hoàn thành') }}</span>
                            @endif
                        </div>

                        <div class="row g-4">
                            <div class="col-12 col-md-8">
                                <div id="bookingCustomerDetails" class="booking-confirmation-details">
                                    <dl class="row g-0 mb-0">
                                        <dt class="col-sm-5 py-2">{{ __('Nhà hàng') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ localized_text($booking->restaurant, 'name') }}</dd>
                                        <dt class="col-sm-5 py-2">{{ __('Tầng') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ $booking->floor ? __('Tầng').' '.$booking->floor : __('Nhà hàng bố trí khi xác nhận') }}</dd>
                                        <dt class="col-sm-5 py-2">{{ __('Bàn') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ $booking->table_codes ? implode(', ', $booking->table_codes) : __('Nhà hàng bố trí khi xác nhận') }}</dd>
                                        <dt class="col-sm-5 py-2">{{ __('Họ và tên') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ $booking->customer_name }}</dd>
                                        <dt class="col-sm-5 py-2">{{ __('Số điện thoại') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ $booking->phone }}</dd>
                                        @if ($booking->email)
                                            <dt class="col-sm-5 py-2">Email</dt>
                                            <dd class="col-sm-7 py-2">{{ $booking->email }}</dd>
                                        @endif
                                        <dt class="col-sm-5 py-2">{{ __('Ngày đặt') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ $booking->booking_date->format('d/m/Y') }}</dd>
                                        <dt class="col-sm-5 py-2">{{ __('Giờ đặt') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ substr($booking->booking_time, 0, 5) }}</dd>
                                        <dt class="col-sm-5 py-2">{{ __('Số khách') }}</dt>
                                        <dd class="col-sm-7 py-2">{{ $booking->number_of_guests }}</dd>
                                        @if ($booking->pre_order_items)
                                            <dt class="col-sm-5 py-2">{{ __('Gọi món trước') }}</dt>
                                            <dd class="col-sm-7 py-2">
                                                <ul class="mb-0 ps-3">
                                                    @foreach ($booking->pre_order_items as $preOrderItem)
                                                        <li>{{ app()->getLocale() === 'en' ? (($preOrderItem['name_en'] ?? null) ?: __($preOrderItem['name'])) : $preOrderItem['name'] }}</li>
                                                    @endforeach
                                                </ul>
                                            </dd>
                                        @endif
                                        @if ($booking->note)
                                            <dt class="col-sm-5 py-2">{{ __('Ghi chú') }}</dt>
                                            <dd class="col-sm-7 py-2">{{ $booking->note }}</dd>
                                        @endif
                                    </dl>
                                </div>
                                <button class="btn btn-link btn-sm px-0 mt-2" id="toggleBookingDetails" type="button" aria-controls="bookingCustomerDetails" aria-expanded="true">
                                    {{ __('Ẩn thông tin') }}
                                </button>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="booking-qr-panel text-center">
                                    <div id="bookingQrCode" class="booking-qr-code mx-auto" data-qr-value="{{ $booking->qr_code }}" aria-label="{{ __('Mã QR đặt bàn') }}"></div>
                                    <p class="small text-secondary mb-0 mt-2">{{ __('Quét mã khi đến nhà hàng') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <a class="btn btn-outline-dark" href="{{ route('home') }}">{{ __('Trở về trang chủ') }}</a>
                        @if (in_array($booking->status, [\App\Enums\BookingStatus::Pending->value, \App\Enums\BookingStatus::Confirmed->value], true))
                            <form method="POST" action="{{ route('booking.cancel') }}" data-confirm="{{ __('Bạn chắc chắn muốn hủy đặt bàn này?') }}" onsubmit="return confirm(this.dataset.confirm)">
                                @csrf
                                <button class="btn btn-outline-danger" type="submit">{{ __('Hủy đặt bàn') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action="{{ route('booking.store') }}"]');
            if (!form) return;

            const restaurant = form.querySelector('[name="restaurant_id"]');
            const floor = form.querySelector('[name="floor"]');
            const date = form.querySelector('[name="booking_date"]');
            const time = form.querySelector('[name="booking_time"]');
            const guests = form.querySelector('[name="number_of_guests"]');
            const tables = [...form.querySelectorAll('.booking-table-option')];
            const floorTabs = [...form.querySelectorAll('[data-floor-tab]')];
            const floorPlan = document.getElementById('bookingFloorPlan');
            const summary = document.getElementById('bookingSelectedSummary');
            const capacitySummary = document.getElementById('bookingCapacitySummary');
            const availabilityMessage = document.getElementById('bookingAvailabilityMessage');
            const selectedText = @json(__('Đã chọn :tables'));
            const checkText = @json(__('Đang kiểm tra tình trạng bàn…'));
            const readyText = @json(__('Còn bàn cho khung giờ đã chọn'));
            const chooseText = @json(__('Chọn ngày và giờ để xem tình trạng bàn'));
            const noneText = @json(__('Chưa chọn bàn'));
            const tooSmallText = @json(__('Không đủ chỗ'));
            let availabilityRequest;

            const updateSummary = () => {
                const chosen = tables.filter(table => table.querySelector('input').checked);
                const total = chosen.reduce((sum, table) => sum + Number(table.dataset.capacity), 0);
                summary.textContent = chosen.length
                    ? selectedText.replace(':tables', chosen.map(table => table.querySelector('.booking-table-code').textContent).join(', '))
                    : noneText;
                capacitySummary.textContent = `${total} / ${guests.value || 0}`;
            };

            const updateFloor = () => {
                floorTabs.forEach(tab => {
                    const active = Number(tab.dataset.floorTab) === Number(floor.value);
                    tab.setAttribute('aria-selected', String(active));
                    tab.tabIndex = active ? 0 : -1;
                });
                floorPlan?.setAttribute('aria-labelledby', `floor-tab-${floor.value}`);
                tables.forEach(table => {
                    const matchesFloor = !floor.value || Number(table.dataset.floor) === Number(floor.value);
                    table.hidden = !matchesFloor;
                    if (!matchesFloor) table.querySelector('input').checked = false;
                });
                updateSummary();
            };

            const updateCapacityAvailability = () => {
                const partySize = Number(guests.value || 0);

                tables.forEach(table => {
                    const input = table.querySelector('input');
                    const isUnavailable = table.classList.contains('is-unavailable');
                    const isTooSmall = partySize > 0 && Number(table.dataset.capacity) < partySize;
                    table.classList.toggle('is-too-small', isTooSmall && !isUnavailable);
                    input.disabled = isUnavailable || isTooSmall;
                    if (input.disabled) input.checked = false;

                    const status = table.querySelector('.booking-table-status');
                    status.textContent = isUnavailable ? @json(__('Đã được đặt')) : (isTooSmall ? tooSmallText : @json(__('Còn trống')));
                    input.setAttribute('aria-label', `${@json(__('Bàn'))} ${input.value} — ${table.dataset.capacity} ${@json(__('người'))}${isUnavailable ? `, ${@json(__('Đã được đặt'))}` : (isTooSmall ? `, ${tooSmallText}` : '')}`);
                });

                updateSummary();
            };

            const refreshAvailability = async () => {
                if (!restaurant.value || !date.value || !time.value) {
                    availabilityMessage.textContent = chooseText;
                    return;
                }

                availabilityRequest?.abort();
                availabilityRequest = new AbortController();
                availabilityMessage.textContent = checkText;
                const params = new URLSearchParams({ restaurant_id: restaurant.value, booking_date: date.value, booking_time: time.value });

                try {
                    const response = await fetch(`{{ route('booking.availability') }}?${params}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        signal: availabilityRequest.signal,
                    });
                    if (!response.ok) throw new Error('availability');
                    const { unavailable } = await response.json();

                    tables.forEach(table => {
                        const input = table.querySelector('input');
                        const isUnavailable = unavailable.includes(input.value);
                        table.classList.toggle('is-unavailable', isUnavailable);
                        input.disabled = isUnavailable;
                        if (isUnavailable) input.checked = false;
                    });
                    availabilityMessage.textContent = readyText;
                    updateCapacityAvailability();
                } catch (error) {
                    if (error.name !== 'AbortError') availabilityMessage.textContent = @json(__('Không thể tải tình trạng bàn. Vui lòng thử lại.'));
                }
            };

            tables.forEach(table => table.querySelector('input').addEventListener('change', updateSummary));
            floorTabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    floor.value = tab.dataset.floorTab;
                    floor.dispatchEvent(new Event('change', { bubbles: true }));
                });
                tab.addEventListener('keydown', event => {
                    if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
                    event.preventDefault();
                    const nextIndex = (index + (event.key === 'ArrowRight' ? 1 : floorTabs.length - 1)) % floorTabs.length;
                    floorTabs[nextIndex].focus();
                    floorTabs[nextIndex].click();
                });
            });
            floor.addEventListener('change', updateFloor);
            guests.addEventListener('input', updateCapacityAvailability);
            [restaurant, date, time].forEach(input => input.addEventListener('change', refreshAvailability));
            updateFloor();
            updateCapacityAvailability();
            if (restaurant.value && date.value && time.value) refreshAvailability();
        });
    </script>
    @if ($booking)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modalElement = document.getElementById('bookingConfirmationModal');
                const qrElement = document.getElementById('bookingQrCode');
                const detailsElement = document.getElementById('bookingCustomerDetails');
                const toggleDetailsButton = document.getElementById('toggleBookingDetails');

                if (window.QRCode && qrElement) {
                    new QRCode(qrElement, {
                        text: qrElement.dataset.qrValue,
                        width: 160,
                        height: 160,
                        colorDark: '#201e1a',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.M,
                    });
                }

                toggleDetailsButton?.addEventListener('click', () => {
                    const isExpanded = toggleDetailsButton.getAttribute('aria-expanded') === 'true';
                    detailsElement.hidden = isExpanded;
                    toggleDetailsButton.setAttribute('aria-expanded', String(!isExpanded));
                    toggleDetailsButton.textContent = isExpanded ? @json(__('Hiện thông tin')) : @json(__('Ẩn thông tin'));
                });

                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            });
        </script>
    @endif
@endpush
