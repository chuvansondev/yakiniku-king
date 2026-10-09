@extends('admin.layouts.app')

@section('title', 'Khuyến mãi')

@section('page-title', 'Khuyến mãi')

@section('content')

<div
    style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    "
>

    <h1>Danh sách khuyến mãi</h1>

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
        + Thêm khuyến mãi
    </a>

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

                <th>Hình ảnh</th>

                <th>Tiêu đề</th>

                <th>Slug</th>

                <th>Thời gian</th>

                <th>Trạng thái</th>

                <th>Thao tác</th>

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

                            Không có ảnh

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

                        đến

                        <br>

                        @if($promotion->end_date)
                            {{ $promotion->end_date->format('d/m/Y') }}
                        @else
                            ---
                        @endif

                    </td>


                    <td>

                        @if($promotion->status)

                            <span style="color:green;">
                                Đang hiển thị
                            </span>

                        @else

                            <span style="color:red;">
                                Đang ẩn
                            </span>

                        @endif

                    </td>


                    <td>

                        <a
                            href="{{ route('admin.menu.promotions.edit', $promotion) }}"
                        >
                            Sửa
                        </a>


                        <form
                            action="{{ route('admin.menu.promotions.destroy', $promotion) }}"
                            method="POST"
                            style="display:inline;"
                            onsubmit="
                                return confirm(
                                    'Bạn có chắc muốn xóa khuyến mãi này?'
                                )
                            "
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

                    <td
                        colspan="7"
                        style="text-align:center;"
                    >
                        Chưa có chương trình khuyến mãi.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table></div>

</div>

@endsection