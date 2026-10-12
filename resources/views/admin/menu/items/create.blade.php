@extends('admin.layouts.app')

@section('title', __('Thêm món'))

@section('page-title', __('Thêm món'))

@section('content')

    <h1>{{ __('Thêm món ăn') }}</h1>

    @if($errors->any())

        <div style="color:red;">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('admin.menu.items.store') }}"
        method="POST"
        enctype="multipart/form-data"
        style="background:white; padding:25px;"
    >

        @csrf


        <div style="margin-bottom:20px;">

            <label>{{ __('Danh mục') }}</label>

            <br>

            <select
                name="category_id"
                style="width:100%; padding:10px;"
                required
            >

                <option value="">
                    {{ __('-- Chọn danh mục --') }}</option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <div style="margin-bottom:20px;">

            <label>{{ __('Tên món') }}</label>

            <br>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                style="width:100%; padding:10px;"
                required
            >

        </div>
        
        @include('admin.menu.partials.english-field', ['name' => 'name_en', 'label' => __('Tên món'), 'value' => old('name_en', ''), 'margin' => '20px'])
        
        @include('admin.menu.partials.english-field', ['name' => 'description_en', 'label' => __('Mô tả'), 'value' => old('description_en', ''), 'type' => 'textarea', 'rows' => 5, 'margin' => '20px'])


        <div style="margin-bottom:20px;">

            <label>Slug</label>

            <br>

            <input
                type="text"
                name="slug"
                value="{{ old('slug') }}"
                placeholder="{{ __('Để trống để tự tạo') }}"
                style="width:100%; padding:10px;"
            >

        </div>


        <div style="margin-bottom:20px;">

            <label>{{ __('Mô tả') }}</label>

            <br>

            <textarea
                name="description"
                rows="5"
                style="width:100%; padding:10px;"
            >{{ old('description') }}</textarea>

        </div>


        <div style="margin-bottom:20px;">

            <label>{{ __('Hình ảnh') }}</label>

            <br>

            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp"
                style="width:100%; padding:10px;"
            >

        </div>


        <div style="margin-bottom:20px;">

            <label>{{ __('Giá') }}</label>

            <br>

            <input
                type="number"
                name="price"
                value="{{ old('price', 0) }}"
                min="0"
                step="0.01"
                style="width:100%; padding:10px;"
                required
            >

        </div>


        <div style="margin-bottom:20px;">

            <label>{{ __('Thứ tự') }}</label>

            <br>

            <input
                type="number"
                name="sort_order"
                value="{{ old('sort_order', 0) }}"
                min="0"
                style="width:100%; padding:10px;"
            >

        </div>


        <div style="margin-bottom:15px;">

            <label>

                <input
                    type="checkbox"
                    name="is_must_try"
                    value="1"
                >

                Must Try

            </label>

        </div>


        <div style="margin-bottom:20px;">

            <label>

                <input
                    type="checkbox"
                    name="status"
                    value="1"
                    checked
                >

                {{ __('Hiển thị') }}</label>

        </div>


        <button
            type="submit"
            style="
                background:#111;
                color:white;
                padding:10px 20px;
                border:none;
                border-radius:5px;
            "
        >
            {{ __('Lưu') }}</button>

        <a
            href="{{ route('admin.menu.items.index') }}"
            style="margin-left:10px;"
        >
            {{ __('Quay lại') }}</a>

    </form>

@endsection
