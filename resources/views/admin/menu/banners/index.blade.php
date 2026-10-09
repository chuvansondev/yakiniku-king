@extends('admin.layouts.app')

@section('title', 'Banner')

@section('page-title', 'Banner')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

    <h1>Danh sách Banner</h1>

    <a
        href="{{ route('admin.menu.banners.create') }}"
        style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        + Thêm Banner
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

                <th>Banner</th>

                <th>Tiêu đề</th>

                <th>Loại</th>

                <th>Link</th>

                <th>Thứ tự</th>

                <th>Trạng thái</th>

                <th>Thao tác</th>

            </tr>

        </thead>

        <tbody>

            @forelse($banners as $banner)

                <tr>

                    <td>
                        {{ $banner->id }}
                    </td>


                    <td>

                        @if($banner->image)

                            <img
                                src="{{ asset('storage/' . $banner->image) }}"
                                alt="{{ $banner->title }}"
                                style="
                                    width:180px;
                                    height:100px;
                                    object-fit:cover;
                                "
                            >

                        @elseif($banner->video_url)

                            <a
                                href="{{ $banner->video_url }}"
                                target="_blank"
                            >
                                Xem Video
                            </a>

                        @else

                            Không có

                        @endif

                    </td>


                    <td>
                        {{ $banner->title ?? '---' }}
                    </td>


                    <td>
                        {{ $banner->type }}
                    </td>


                    <td>

                        @if($banner->link)

                            <a
                                href="{{ $banner->link }}"
                                target="_blank"
                            >
                                {{ $banner->link }}
                            </a>

                        @else

                            ---

                        @endif

                    </td>


                    <td>
                        {{ $banner->sort_order }}
                    </td>


                    <td>

                        @if($banner->status)

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
                            href="{{ route('admin.menu.banners.edit', $banner) }}"
                        >
                            Sửa
                        </a>


                        <form
                            action="{{ route('admin.menu.banners.destroy', $banner) }}"
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Bạn có chắc muốn xóa banner này?')"
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

                    <td colspan="8" style="text-align:center;">
                        Chưa có Banner.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table></div>

</div>

@endsection