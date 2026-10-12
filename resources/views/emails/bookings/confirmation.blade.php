<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Xác nhận đặt bàn :code', ['code' => $booking->booking_code]) }}</title>
</head>
<body style="margin:0;background:#f5f5f3;color:#222;font-family:Arial,sans-serif;line-height:1.6">
    <main style="max-width:600px;margin:24px auto;padding:28px;background:#fff">
        <h1 style="margin-top:0;color:#a62020">{{ $isAdmin ? __('Có đặt bàn mới') : __('Yêu cầu đặt bàn đã được tiếp nhận') }}</h1>
        <p>{{ $isAdmin ? __('Thông tin khách hàng:') : __('Xin chào :name,', ['name' => $booking->customer_name]) }}</p>
        @unless ($isAdmin)
            <p>{{ __('Nhà hàng đã nhận được yêu cầu đặt bàn của bạn. Nhân viên sẽ liên hệ để xác nhận.') }}</p>
        @endunless
        <h2>{{ __('Thông tin đặt bàn') }}</h2>
        <ul>
            <li>{{ __('Mã đặt bàn') }}: <strong>{{ $booking->booking_code }}</strong></li>
            <li>{{ __('Nhà hàng') }}: {{ $booking->restaurant ? localized_text($booking->restaurant, 'name') : '—' }}</li>
            <li>{{ __('Ngày') }}: {{ $booking->booking_date?->format('d/m/Y') }}</li>
            <li>{{ __('Giờ') }}: {{ substr((string) $booking->booking_time, 0, 5) }}</li>
            <li>{{ __('Số khách') }}: {{ $booking->number_of_guests }}</li>
            <li>{{ __('Điện thoại') }}: {{ $booking->phone }}</li>
            @if ($booking->note)
                <li>{{ __('Ghi chú') }}: {{ $booking->note }}</li>
            @endif
        </ul>
        <p>{{ __('Trân trọng,') }}<br>{{ config('app.name') }}</p>
    </main>
</body>
</html>
