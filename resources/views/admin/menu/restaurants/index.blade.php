@extends('admin.layouts.app')

@section('title', 'Nhà hàng')
@section('page-title', 'Nhà hàng')

@section('content')

@if(session('success'))
    <div class="admin-notice" role="status">{{ session('success') }}</div>
@endif

<div class="admin-page-heading">
    <h1>Quản lý nhà hàng</h1>
    <a href="{{ route('admin.menu.restaurants.create') }}"
    style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        + Thêm nhà hàng
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
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Địa chỉ</th>
            <th>SĐT</th>
            <th>Giờ mở cửa</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>

    <tbody>

        @forelse($restaurants as $restaurant)

            <tr>

                <td>
                    @if($restaurant->image)

                        <img
                            src="{{ asset('storage/' . $restaurant->image) }}"
                            width="100"
                            alt="{{ $restaurant->name }}"
                        >

                    @else

                        Không có ảnh

                    @endif
                </td>


                <td>
                    {{ $restaurant->name }}
                </td>


                <td>
                    {{ $restaurant->address }}
                </td>


                <td>
                    {{ $restaurant->phone ?? '-' }}
                </td>


                <td>
                    @if($restaurant->opening_time && $restaurant->closing_time)

                        {{ substr($restaurant->opening_time, 0, 5) }}
                        -
                        {{ substr($restaurant->closing_time, 0, 5) }}

                    @else

                        -

                    @endif
                </td>


                <td>

                    @if($restaurant->status)

                        <span>Đang hoạt động</span>

                    @else

                        <span>Ngừng hoạt động</span>

                    @endif

                </td>


                <td>

                    <a href="{{ route('admin.menu.restaurants.edit', $restaurant) }}">
                        Sửa
                    </a>


                    <form
                        action="{{ route('admin.menu.restaurants.destroy', $restaurant) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Bạn có chắc muốn xóa nhà hàng này?')"
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
                <td colspan="7" style="text-align:center;">
                    Chưa có nhà hàng nào.
                </td>
            </tr>

        @endforelse

    </tbody>
</table></div>

@endsection
