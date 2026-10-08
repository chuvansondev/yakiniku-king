<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác nhận đặt bàn {{ $booking->booking_code }}</title>
</head>
<body style="margin:0;background:#f5f5f3;color:#222;font-family:Arial,sans-serif;line-height:1.6">
    <main style="max-width:600px;margin:24px auto;padding:28px;background:#fff">
        <h1 style="margin-top:0;color:#a62020">{{ $isAdmin ? 'Có đặt bàn mới' : 'Yêu cầu đặt bàn đã được tiếp nhận' }}</h1>
        <p>{{ $isAdmin ? 'Thông tin khách hàng:' : 'Xin chào '.$booking->customer_name.',' }}</p>
        @unless ($isAdmin)
            <p>Nhà hàng đã nhận được yêu cầu đặt bàn của bạn. Nhân viên sẽ liên hệ để xác nhận.</p>
        @endunless
        <h2>Thông tin đặt bàn</h2>
        <ul>
            <li>Mã đặt bàn: <strong>{{ $booking->booking_code }}</strong></li>
            <li>Nhà hàng: {{ $booking->restaurant?->name ?? '—' }}</li>
            <li>Ngày: {{ $booking->booking_date?->format('d/m/Y') }}</li>
            <li>Giờ: {{ substr((string) $booking->booking_time, 0, 5) }}</li>
            <li>Số khách: {{ $booking->number_of_guests }}</li>
            <li>Điện thoại: {{ $booking->phone }}</li>
            @if ($booking->note)
                <li>Ghi chú: {{ $booking->note }}</li>
            @endif
        </ul>
        <p>Trân trọng,<br>{{ config('app.name') }}</p>
    </main>
</body>
</html>
