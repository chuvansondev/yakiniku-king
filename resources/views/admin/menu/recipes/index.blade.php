@extends('admin.layouts.app')

@section('title', __('Recipes'))

@section('page-title', __('Recipes'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\Recipe::class, 'listRoute' => route('admin.menu.recipes.index')])

<div
    style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    "
>

    <h1>{{ __('Danh sách công thức') }}</h1>

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
        {{ __('+ Thêm công thức') }}</a>

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

                <th>{{ __('Ngày đăng') }}</th>

                <th>{{ __('Trạng thái') }}</th>

                <th>{{ __('Thao tác') }}</th>

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

                            {{ __('Không có ảnh') }}

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

                            {{ __('Chưa đăng') }}

                        @endif

                    </td>


                    <td>

                        @if($recipe->status)

                            <span style="color:green;">
                                {{ __('Đang hiển thị') }}</span>

                        @else

                            <span style="color:red;">
                                {{ __('Đang ẩn') }}</span>

                        @endif

                    </td>


                    <td>

                        <a
                            href="{{ route('admin.menu.recipes.edit', $recipe) }}"
                        >
                            {{ __('Sửa') }}</a>


                        <form
                            action="{{ route('admin.menu.recipes.destroy', $recipe) }}"
                            method="POST"
                            style="display:inline;"
                            onsubmit="
                                return confirm(@json(__('Bạn có chắc muốn xóa công thức này?')))
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
                        {{ __('Chưa có công thức.') }}</td>

                </tr>

            @endforelse

        </tbody>

    </table></div>

</div>


@include('admin.menu.partials.pagination', ['paginator' => $recipes])
@endsection
