@extends('admin.layouts.app')

@section('title', __('Đặt bàn'))
@section('page-title', __('Đặt bàn'))

@section('content')

@if(session('success'))
    <div class="admin-notice" role="status">{{ session('success') }}</div>
@endif

<div class="admin-page-heading">
    <h1>{{ __('Quản lý đặt bàn') }}</h1>
    <a href="{{ route('admin.menu.bookings.create') }}"
    style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        + {{ __('Thêm booking') }}
    </a>

</div>


<div class="admin-table-wrap"><table
    border="1"
    cellpadding="10"
    cellspacing="0"
    width="100%"
>

    <thead>

        <tr>
            <th>{{ __('Mã booking') }}</th>
            <th>{{ __('Nhà hàng') }}</th>
            <th>{{ __('Tầng') }}</th>
            <th>{{ __('Bàn') }}</th>
            <th>{{ __('Khách hàng') }}</th>
            <th>{{ __('SĐT') }}</th>
            <th>{{ __('Ngày') }}</th>
            <th>{{ __('Giờ') }}</th>
            <th>{{ __('Số người') }}</th>
            <th>{{ __('Món gọi trước') }}</th>
            <th>{{ __('Trạng thái') }}</th>
            <th>{{ __('Thao tác') }}</th>
        </tr>

    </thead>


    <tbody>

        @forelse($bookings as $booking)

            <tr>

                <td>
                    {{ $booking->booking_code }}
                </td>


                <td>
                    {{ $booking->restaurant->name ?? '-' }}
                </td>

                <td>
                    {{ $booking->floor ? __('Tầng').' '.$booking->floor : '-' }}
                </td>

                <td>
                    {{ $booking->table_codes ? implode(', ', $booking->table_codes) : __('Nhà hàng bố trí') }}
                </td>


                <td>
                    {{ $booking->customer_name }}
                </td>


                <td>
                    {{ $booking->phone }}
                </td>


                <td>
                    {{ $booking->booking_date->format('d/m/Y') }}
                </td>


                <td>
                    {{ substr($booking->booking_time, 0, 5) }}
                </td>


                <td>
                    {{ $booking->number_of_guests }}
                </td>

                <td>
                    {{ collect($booking->pre_order_items ?? [])->pluck('name')->join(', ') ?: '-' }}
                </td>


                <td>

                    @switch($booking->status)

                        @case(\App\Enums\BookingStatus::Pending->value)
                            {{ __('Chờ xác nhận') }}
                            @break

                        @case(\App\Enums\BookingStatus::Confirmed->value)
                            {{ __('Đã xác nhận') }}
                            @break

                        @case(\App\Enums\BookingStatus::Cancelled->value)
                            {{ __('Đã hủy') }}
                            @break

                        @case(\App\Enums\BookingStatus::Completed->value)
                            {{ __('Đã hoàn thành') }}
                            @break

                    @endswitch

                </td>


                <td>

                    <a href="{{ route('admin.menu.bookings.edit', $booking) }}">
                        {{ __('Sửa') }}
                    </a>


                    <form
                        action="{{ route('admin.menu.bookings.destroy', $booking) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm(@json(__('Bạn có chắc muốn xóa booking này?')))"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            {{ __('Xóa') }}
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="12">
                    {{ __('Chưa có booking nào.') }}
                </td>

            </tr>

        @endforelse

    </tbody>

</table></div>

@endsection
