@extends('admin.layouts.app')

@section('title', 'Recipes')

@section('page-title', 'Recipes')

@section('content')

<div
    style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    "
>

    <h1>Danh sách công thức</h1>

    <a
        href="{{ route('admin.menu.recipes.create') }}"
        style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        + Thêm công thức
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

                <th>Ngày đăng</th>

                <th>Trạng thái</th>

                <th>Thao tác</th>

            </tr>

        </thead>


        <tbody>

            @forelse($recipes as $recipe)

                <tr>

                    <td>
                        {{ $recipe->id }}
                    </td>


                    <td>

                        @if($recipe->image)

                            <img
                                src="{{ asset('storage/' . $recipe->image) }}"
                                alt="{{ $recipe->title }}"
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
                        {{ $recipe->title }}
                    </td>


                    <td>
                        {{ $recipe->slug }}
                    </td>


                    <td>

                        @if($recipe->published_at)

                            {{ $recipe->published_at->format('d/m/Y H:i') }}

                        @else

                            Chưa đăng

                        @endif

                    </td>


                    <td>

                        @if($recipe->status)

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
                            href="{{ route('admin.menu.recipes.edit', $recipe) }}"
                        >
                            Sửa
                        </a>


                        <form
                            action="{{ route('admin.menu.recipes.destroy', $recipe) }}"
                            method="POST"
                            style="display:inline;"
                            onsubmit="
                                return confirm(
                                    'Bạn có chắc muốn xóa công thức này?'
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
                        Chưa có công thức.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table></div>

</div>

@endsection