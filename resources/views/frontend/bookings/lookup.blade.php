@extends('frontend.layouts.app')

@section('title', __('Tra cứu đặt bàn'))

@push('styles')
<style>
    .booking-management { max-width: 960px; margin: 3rem auto; padding: 0 1rem; }
    .booking-management-card { background: #f6f4ee; border-top: 4px solid #a83d32; padding: clamp(1.25rem, 4vw, 2.25rem); }
    .booking-management-card h1 { font-family: Georgia, serif; }
    .booking-management-table { width: 100%; border-collapse: collapse; }
    .booking-management-table th, .booking-management-table td { padding: .8rem; border-bottom: 1px solid #ddd6ca; text-align: left; }
    @media(max-width: 650px) { .booking-management-table th:nth-child(2), .booking-management-table td:nth-child(2) { display:none; } }
</style>
@endpush

@section('content')
<section class="booking-management">
    <div class="booking-management-card">
        @if (!$booking)
            <h1>{{ __('Tra cứu đặt bàn') }}</h1>
            <p>{{ __('Nhập mã đặt bàn cùng email hoặc số điện thoại đã dùng khi đặt.') }}</p>
            @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
            <form method="POST" action="{{ route('booking.lookup') }}" class="row g-3">
                @csrf
                <div class="col-md-6"><label class="form-label" for="booking_code">{{ __('Mã đặt bàn') }}</label><input class="form-control" id="booking_code" name="booking_code" value="{{ old('booking_code') }}" required autocomplete="off"></div>
                <div class="col-md-6"><label class="form-label" for="contact">{{ __('Email hoặc số điện thoại') }}</label><input class="form-control" id="contact" name="contact" value="{{ old('contact') }}" required autocomplete="email"></div>
                <div class="col-12"><button class="btn btn-danger" type="submit">{{ __('Tra cứu') }}</button></div>
            </form>
        @else
            <h1>{{ __('Thông tin đặt bàn') }}</h1>
            @if (session('booking_cancelled')) <div class="alert alert-info" role="status">{{ __('Đặt bàn đã được hủy.') }}</div> @endif
            <dl class="row mt-4">
                <dt class="col-sm-4">{{ __('Mã đặt bàn') }}</dt><dd class="col-sm-8">{{ $booking->booking_code }}</dd>
                <dt class="col-sm-4">{{ __('Nhà hàng') }}</dt><dd class="col-sm-8">{{ $booking->restaurant?->name ?? '-' }}</dd>
                <dt class="col-sm-4">{{ __('Ngày / giờ') }}</dt><dd class="col-sm-8">{{ $booking->booking_date->format('d/m/Y') }} · {{ substr($booking->booking_time, 0, 5) }}</dd>
                <dt class="col-sm-4">{{ __('Khách hàng') }}</dt><dd class="col-sm-8">{{ $booking->customer_name }} · {{ $booking->number_of_guests }} {{ __('người') }}</dd>
                <dt class="col-sm-4">{{ __('Bàn') }}</dt><dd class="col-sm-8">{{ $booking->table_codes ? implode(', ', $booking->table_codes) : __('Nhà hàng bố trí khi xác nhận') }}</dd>
                <dt class="col-sm-4">{{ __('Trạng thái') }}</dt><dd class="col-sm-8">{{ __(match ($booking->status) { 'pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy', 'completed' => 'Đã hoàn thành', default => $booking->status }) }}</dd>
            </dl>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-dark" href="{{ route('booking.lookup') }}">{{ __('Tra cứu đặt bàn khác') }}</a>
                @if (in_array($booking->status, ['pending', 'confirmed'], true))
                    <form method="POST" action="{{ route('booking.lookup.cancel', $booking) }}" onsubmit="return confirm(@json(__('Bạn chắc chắn muốn hủy đặt bàn này?')))">@csrf @method('DELETE')<button class="btn btn-outline-danger" type="submit">{{ __('Hủy đặt bàn') }}</button></form>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection
