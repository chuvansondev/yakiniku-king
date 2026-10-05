@extends('admin.layouts.app')

@section('content')

@if(session('success'))

    <div style="margin-bottom: 15px;">
        {{ session('success') }}
    </div>

@endif


<div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">
    <h1>Quản lý đặt bàn</h1>
    <a href="{{ route('admin.menu.bookings.create') }}"
    style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        + Thêm booking
    </a>

</div>


<table
    border="1"
    cellpadding="10"
    cellspacing="0"
    width="100%"
>

    <thead>

        <tr>
            <th>Mã booking</th>
            <th>Nhà hàng</th>
            <th>Tầng</th>
            <th>Bàn</th>
            <th>Khách hàng</th>
            <th>SĐT</th>
            <th>Ngày</th>
            <th>Giờ</th>
            <th>Số người</th>
            <th>Món gọi trước</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
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
                    {{ $booking->floor ? 'Tầng '.$booking->floor : '-' }}
                </td>

                <td>
                    {{ $booking->table_codes ? implode(', ', $booking->table_codes) : 'Nhà hàng bố trí' }}
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
                            Chờ xác nhận
                            @break

                        @case(\App\Enums\BookingStatus::Confirmed->value)
                            Đã xác nhận
                            @break

                        @case(\App\Enums\BookingStatus::Cancelled->value)
                            Đã hủy
                            @break

                        @case(\App\Enums\BookingStatus::Completed->value)
                            Đã hoàn thành
                            @break

                    @endswitch

                </td>


                <td>

                    <a href="{{ route('admin.menu.bookings.edit', $booking) }}">
                        Sửa
                    </a>


                    <form
                        action="{{ route('admin.menu.bookings.destroy', $booking) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Bạn có chắc muốn xóa booking này?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            Xóa
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="12">
                    Chưa có booking nào.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>

@endsection
