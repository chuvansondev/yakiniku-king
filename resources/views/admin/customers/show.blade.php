@extends('admin.layouts.app')

@section('title', __('Hồ sơ khách hàng'))
@section('page-title', __('Khách hàng / Hồ sơ'))

@section('content')
<div class="customer-admin-page">
    <div class="customer-detail-heading">
        <div>
            <a class="customer-back-link" href="{{ route('admin.customers.index') }}">← {{ __('Danh sách khách hàng') }}</a>
            <p class="customer-admin-eyebrow">CRM · {{ __('Hồ sơ') }} #{{ $customer->id }}</p>
            <h1>{{ $customer->name }}</h1>
            <p>{{ $customer->phone ?: '—' }} · {{ $customer->email ?: '—' }}</p>
        </div>
        <div class="customer-summary-stats"><span><strong>{{ $customer->bookings_count }}</strong>{{ __('đặt bàn') }}</span><span><strong>{{ $customer->leads_count }}</strong>{{ __('yêu cầu') }}</span></div>
    </div>

    @if (session('success'))<div class="admin-notice" role="status">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="restaurant-form-errors" role="alert">{{ $errors->first() }}</div>@endif

    <section class="customer-profile-card">
        <h2>{{ __('Thông tin khách hàng') }}</h2>
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="customer-profile-form">
            @csrf @method('PUT')
            <label>{{ __('Họ và tên') }}<input name="name" value="{{ old('name', $customer->name) }}" required maxlength="255"></label>
            <label>{{ __('Số điện thoại') }}<input name="phone" value="{{ old('phone', $customer->phone) }}" maxlength="30"></label>
            <label>{{ __('Email') }}<input type="email" name="email" value="{{ old('email', $customer->email) }}" maxlength="255"></label>
            <label>{{ __('Trạng thái') }}<select name="status"><option value="active" @selected(old('status', $customer->status) === 'active')>{{ __('Đang hoạt động') }}</option><option value="inactive" @selected(old('status', $customer->status) === 'inactive')>{{ __('Ngừng hoạt động') }}</option></select></label>
            <label class="customer-notes-field">{{ __('Ghi chú nội bộ') }}<textarea name="notes" rows="4" maxlength="5000">{{ old('notes', $customer->notes) }}</textarea></label>
            <button type="submit">{{ __('Lưu hồ sơ') }}</button>
        </form>
    </section>

    <section class="customer-history-section">
        <div class="customer-section-heading"><h2>{{ __('Lịch sử đặt bàn') }}</h2><span>{{ $customer->bookings_count }}</span></div>
        <div class="admin-table-wrap"><table class="customer-table"><thead><tr><th>{{ __('Mã đặt bàn') }}</th><th>{{ __('Nhà hàng') }}</th><th>{{ __('Ngày giờ') }}</th><th>{{ __('Số khách') }}</th><th>{{ __('Trạng thái') }}</th></tr></thead><tbody>
            @forelse ($bookings as $booking)
                <tr><td>{{ $booking->booking_code }}</td><td>{{ $booking->restaurant?->name ?? '—' }}</td><td>{{ $booking->booking_date->format('d/m/Y') }} · {{ substr($booking->booking_time, 0, 5) }}</td><td>{{ $booking->number_of_guests }}</td><td>{{ $booking->status }}</td></tr>
            @empty
                <tr><td colspan="5" class="customer-empty">{{ __('Chưa có lịch sử đặt bàn.') }}</td></tr>
            @endforelse
        </tbody></table></div>
        {{ $bookings->links() }}
    </section>

    <section class="customer-history-section">
        <div class="customer-section-heading"><h2>{{ __('Lịch sử liên hệ') }}</h2><span>{{ $customer->leads_count }}</span></div>
        <div class="admin-table-wrap"><table class="customer-table"><thead><tr><th>{{ __('Ngày gửi') }}</th><th>{{ __('Nội dung') }}</th><th>{{ __('Trạng thái') }}</th></tr></thead><tbody>
            @forelse ($leads as $lead)
                <tr><td>{{ $lead->created_at->format('d/m/Y H:i') }}</td><td>{{ $lead->message ?: '—' }}</td><td>{{ $lead->status }}</td></tr>
            @empty
                <tr><td colspan="3" class="customer-empty">{{ __('Chưa có yêu cầu liên hệ.') }}</td></tr>
            @endforelse
        </tbody></table></div>
        {{ $leads->links() }}
    </section>
</div>
@endsection
