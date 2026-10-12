@extends('admin.layouts.app')

@section('title', __('Khuyến mãi'))

@section('page-title', __('Khuyến mãi'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\Promotion::class, 'listRoute' => route('admin.menu.promotions.index')])

<div
    style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    "
>

    <h1>{{ __('Danh sách khuyến mãi') }}</h1>

    <a
        href="{{ route('admin.menu.promotions.create') }}"
        style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        {{ __('+ Thêm khuyến mãi') }}</a>

</div>


@if(session('success'))

    <div
        style="
            background:#d1fae5;
            color:#065f46;
            padding:12px;
            margin-bottom:20px;
        "
    >
        {{ session('success') }}
    </div>

@endif


<div style="background:white; padding:20px;">

    <div class="admin-table-wrap"><table
        width="100%"
        border="1"
        cellpadding="10"
        cellspacing="0"
    >

        <thead>

            <tr>

                <th>ID</th>

                <th>{{ __('Hình ảnh') }}</th>

                <th>{{ __('Tiêu đề') }}</th>

                <th>Slug</th>

                <th>{{ __('Thời gian') }}</th>

                <th>{{ __('Trạng thái') }}</th>

                <th>{{ __('Thao tác') }}</th>

            </tr>

        </thead>


        <tbody>

            @forelse($promotions as $promotion)

                <tr>

                    <td>
                        {{ $promotion->id }}
                    </td>


                    <td>

                        @if($promotion->image)

                            <img
                                src="{{ asset('storage/' . $promotion->image) }}"
                                alt="{{ $promotion->title }}"
                                style="
                                    width:180px;
                                    height:100px;
                                    object-fit:cover;
                                "
                            >

                        @else

                            {{ __('Không có ảnh') }}

                        @endif

                    </td>


                    <td>
                        {{ $promotion->title }}
                    </td>


                    <td>
                        {{ $promotion->slug }}
                    </td>


                    <td>

                        @if($promotion->start_date)
                            {{ $promotion->start_date->format('d/m/Y') }}
                        @else
                            ---
                        @endif

                        <br>

                        {{ __('đến') }}<br>

                        @if($promotion->end_date)
                            {{ $promotion->end_date->format('d/m/Y') }}
                        @else
                            ---
                        @endif

                    </td>


                    <td>

                        @if($promotion->status)

                            <span style="color:green;">
                                {{ __('Đang hiển thị') }}</span>

                        @else

                            <span style="color:red;">
                                {{ __('Đang ẩn') }}</span>

                        @endif

                    </td>


                    <td>

                        <a
                            href="{{ route('admin.menu.promotions.edit', $promotion) }}"
                        >
                            {{ __('Sửa') }}</a>


                        <form
                            action="{{ route('admin.menu.promotions.destroy', $promotion) }}"
                            method="POST"
                            style="display:inline;"
                            onsubmit="
                                return confirm(@json(__('Bạn có chắc muốn xóa khuyến mãi này?')))
                            "
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

                    <td
                        colspan="7"
                        style="text-align:center;"
                    >
                        {{ __('Chưa có chương trình khuyến mãi.') }}</td>

                </tr>

            @endforelse

        </tbody>

    </table></div>

</div>


@include('admin.menu.partials.pagination', ['paginator' => $promotions])
@endsection
