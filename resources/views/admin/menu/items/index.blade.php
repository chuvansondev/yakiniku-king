@extends('admin.layouts.app')

@section('title', __('Menu Items'))

@section('page-title', __('Menu Items'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\MenuItem::class, 'listRoute' => route('admin.menu.items.index')])

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

        <h1>{{ __('Món ăn') }}</h1>

        <a
            href="{{ route('admin.menu.items.create') }}"
            style="
                background:#111;
                color:white;
                padding:10px 15px;
                text-decoration:none;
                border-radius:5px;
            "
        >
            {{ __('+ Thêm món') }}</a>

    </div>


    @if(session('success'))

        <div
            style="
                background:#d4edda;
                color:#155724;
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
            cellpadding="10"
            cellspacing="0"
            border="1"
            style="border-collapse:collapse;"
        >

            <thead>

                <tr>
                    <th>ID</th>
                    <th>{{ __('Tên món') }}</th>
                    <th>{{ __('Danh mục') }}</th>
                    <th>{{ __('Giá') }}</th>
                    <th>{{ __('Must Try') }}</th>
                    <th>{{ __('Trạng thái') }}</th>
                    <th>{{ __('Thao tác') }}</th>
                </tr>

            </thead>

            <tbody>

                @forelse($items as $item)

                    <tr>

                        <td>
                            {{ $item->id }}
                        </td>

                        <td>
                            {{ $item->name }}
                        </td>

                        <td>
                            {{ $item->category->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ localized_price($item->price) }}
                        </td>

                        <td>
                            {{ $item->is_must_try ? __('Có') : __('Không') }}
                        </td>

                        <td>

                            @if($item->status)

                                <span style="color:green;">
                                    {{ __('Hiển thị') }}</span>

                            @else

                                <span style="color:red;">
                                    {{ __('Ẩn') }}</span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.menu.items.edit', $item) }}"
                            >
                                {{ __('Sửa') }}</a>

                            <form
                                action="{{ route('admin.menu.items.destroy', $item) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm(@json(__('Bạn có chắc muốn xóa món này?')))"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    style="color:red; margin-left:10px;"
                                >
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
                            {{ __('Chưa có món ăn nào.') }}</td>

                    </tr>

                @endforelse

            </tbody>

        </table></div>

    </div>


@include('admin.menu.partials.pagination', ['paginator' => $items])
@endsection
