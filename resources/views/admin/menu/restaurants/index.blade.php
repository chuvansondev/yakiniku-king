@extends('admin.layouts.app')

@section('title', __('Nhà hàng'))
@section('page-title', __('Nhà hàng'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\Restaurant::class, 'listRoute' => route('admin.menu.restaurants.index')])

@if(session('success'))
    <div class="admin-notice" role="status">{{ session('success') }}</div>
@endif

<div class="admin-page-heading">
    <h1>{{ __('Quản lý nhà hàng') }}</h1>
    <a href="{{ route('admin.menu.restaurants.create') }}"
    style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        {{ __('+ Thêm nhà hàng') }}</a>
</div>


<div class="admin-table-wrap"><table
    border="1"
    cellpadding="10"
    cellspacing="0"
    width="100%"
>
    <thead>
        <tr>
            <th>{{ __('Ảnh') }}</th>
            <th>{{ __('Tên') }}</th>
            <th>{{ __('Địa chỉ') }}</th>
            <th>{{ __('SĐT') }}</th>
            <th>{{ __('Giờ mở cửa') }}</th>
            <th>{{ __('Trạng thái') }}</th>
            <th>{{ __('Thao tác') }}</th>
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

                            {{ __('Không có ảnh') }}

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

                        <span>{{ __('Đang hoạt động') }}</span>

                    @else

                        <span>{{ __('Ngừng hoạt động') }}</span>

                    @endif

                </td>


                <td>

                    <a href="{{ route('admin.menu.restaurants.edit', $restaurant) }}">
                        {{ __('Sửa') }}</a>


                    <form
                        action="{{ route('admin.menu.restaurants.destroy', $restaurant) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm(@json(__('Bạn có chắc muốn xóa nhà hàng này?')))"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            {{ __('Xóa') }}</button>
                    </form>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="7" style="text-align:center;">
                    {{ __('Chưa có nhà hàng nào.') }}</td>
            </tr>

        @endforelse

    </tbody>
</table></div>


@include('admin.menu.partials.pagination', ['paginator' => $restaurants])
@endsection
