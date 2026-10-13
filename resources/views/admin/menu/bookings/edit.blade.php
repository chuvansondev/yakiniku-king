@extends('admin.layouts.app')

@section('title', __('Chỉnh sửa đặt bàn'))
@section('page-title', __('Đặt bàn / Chỉnh sửa'))

@section('content')
<h1>{{ __('Chỉnh sửa đặt bàn') }}</h1>

@if ($errors->any())
    <div class="restaurant-form-errors" role="alert">
        <strong>{{ __('Vui lòng kiểm tra lại thông tin đặt bàn.') }}</strong>
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form class="admin-entity-form admin-booking-form" action="{{ route('admin.menu.bookings.update', $booking) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.menu.bookings._form', ['buttonText' => __('Cập nhật đặt bàn')])
</form>

<section class="booking-history">
    <header><div><p>{{ __('NHẬT KÝ') }}</p><h2>{{ __('Lịch sử trạng thái') }}</h2></div><span>{{ $booking->booking_code }}</span></header>
    <ol>
        @forelse ($booking->statusHistory as $event)
            <li>
                <span class="booking-history-dot" aria-hidden="true"></span>
                <div class="booking-history-copy">
                    <strong>
                        @if ($event->change_type === 'baseline')
                            {{ __('Trạng thái hiện tại: :status', ['status' => $event->to_status_label]) }}
                        @elseif ($event->from_status)
                            {{ $event->from_status_label }} <span aria-hidden="true">→</span> {{ $event->to_status_label }}
                        @else
                            {{ __('Tạo đặt bàn · :status', ['status' => __(ucfirst($event->to_status))]) }}
                        @endif
                    </strong>
                    <span>{{ $event->changed_by_name ?: __('System') }} · {{ __(':source', ['source' => match ($event->source) { 'admin' => __('Quản trị'), 'customer' => __('Khách hàng'), 'public' => __('Công khai'), default => __('Hệ thống') }]) }}</span>
                </div>
                <time datetime="{{ $event->changed_at->toIso8601String() }}">{{ $event->changed_at->format('d/m/Y H:i') }}</time>
            </li>
        @empty
            <li class="booking-history-empty">{{ __('Chưa có lịch sử trạng thái.') }}</li>
        @endforelse
    </ol>
</section>

<style>
.booking-history{margin-top:22px;padding:20px;background:#fff;border:1px solid #e2e8e2;border-radius:10px}.booking-history header{display:flex;align-items:center;justify-content:space-between;padding-bottom:14px;border-bottom:1px solid #edf0ed}.booking-history header p{margin:0;color:#91765b;font-size:10px;font-weight:700;letter-spacing:.12em}.booking-history h2{margin:4px 0 0;font-size:18px}.booking-history header>span{color:#737d76;font-size:12px}.booking-history ol{list-style:none;margin:0;padding:6px 0 0}.booking-history li{position:relative;display:grid;grid-template-columns:14px 1fr auto;gap:12px;align-items:start;padding:13px 0}.booking-history li+li{border-top:1px solid #f0f2f0}.booking-history-dot{width:9px;height:9px;margin-top:5px;border:2px solid #c84b3b;border-radius:50%;background:white}.booking-history-copy{display:grid;gap:4px}.booking-history-copy strong{font-size:13px}.booking-history-copy span,.booking-history time{color:#737d76;font-size:11px}.booking-history-empty{display:block!important;color:#737d76;font-size:12px}@media(max-width:600px){.booking-history li{grid-template-columns:14px 1fr}.booking-history time{grid-column:2}}
</style>
@endsection
