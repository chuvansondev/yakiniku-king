@extends('admin.layouts.app')

@section('title', __('Quản lý khách hàng'))
@section('page-title', __('Quản lý khách hàng'))

@section('content')
<div class="customer-admin-page">
    <div class="admin-page-heading">
        <div>
            <p class="customer-admin-eyebrow">YAKINIKU KING · CRM</p>
            <h1>{{ __('Khách hàng') }}</h1>
            <p>{{ __('Hồ sơ và lịch sử tương tác từ đặt bàn và yêu cầu liên hệ.') }}</p>
        </div>
        <strong class="customer-total">{{ number_format($customers->total()) }} {{ __('hồ sơ') }}</strong>
    </div>

    <form class="customer-search" method="GET" action="{{ route('admin.customers.index') }}">
        <label for="customer-search">{{ __('Tìm theo tên, email hoặc số điện thoại') }}</label>
        <input id="customer-search" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Nhập thông tin khách hàng') }}">
        <select name="status" aria-label="{{ __('Trạng thái hồ sơ') }}">
            <option value="">{{ __('Tất cả trạng thái') }}</option>
            <option value="active" @selected(request('status') === 'active')>{{ __('Đang hoạt động') }}</option>
            <option value="inactive" @selected(request('status') === 'inactive')>{{ __('Ngừng hoạt động') }}</option>
        </select>
        <button type="submit">{{ __('Tìm kiếm') }}</button>
    </form>

    <div class="admin-table-wrap customer-table-wrap">
        <table class="customer-table">
            <thead><tr><th>{{ __('Khách hàng') }}</th><th>{{ __('Liên hệ') }}</th><th>{{ __('Đặt bàn') }}</th><th>{{ __('Yêu cầu') }}</th><th>{{ __('Tương tác gần nhất') }}</th><th>{{ __('Trạng thái') }}</th></tr></thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td><a class="customer-name-link" href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a>@if($customer->user_id)<small>{{ __('Có tài khoản') }}</small>@endif</td>
                        <td><span>{{ $customer->phone ?: '—' }}</span><small>{{ $customer->email ?: '—' }}</small></td>
                        <td>{{ number_format($customer->bookings_count) }}</td>
                        <td>{{ number_format($customer->leads_count) }}</td>
                        <td>{{ $customer->last_contact_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td><span class="customer-status {{ $customer->status === 'active' ? 'is-active' : 'is-inactive' }}">{{ $customer->status === 'active' ? __('Đang hoạt động') : __('Ngừng hoạt động') }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="customer-empty">{{ __('Chưa có hồ sơ khách hàng phù hợp.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $customers->links() }}
</div>
@endsection
