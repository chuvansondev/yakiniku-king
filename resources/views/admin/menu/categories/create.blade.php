@extends('admin.layouts.app')

@section('title', __('Thêm danh mục'))

@section('page-title', __('Thêm danh mục'))

@section('content')

    <h1>{{ __('Thêm danh mục Menu') }}</h1>

    @if($errors->any())

        <div style="color: red; margin-bottom: 20px;">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('admin.menu.categories.store') }}"
        method="POST"
        enctype="multipart/form-data"
        style="background: white; padding: 25px;"
    >

        @csrf


        <div style="margin-bottom: 20px;">

            <label>
                {{ __('Tên danh mục') }}</label>

            <br>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                style="width: 100%; padding: 10px;"
                required
            >

        </div>

        @include('admin.menu.partials.english-field', ['name' => 'name_en', 'label' => __('Tên danh mục'), 'value' => old('name_en', ''), 'margin' => '20px'])


        <div style="margin-bottom: 20px;">

            <label>
                Slug
            </label>

            <br>

            <input
                type="text"
                name="slug"
                value="{{ old('slug') }}"
                placeholder="{{ __('Để trống để tự tạo') }}"
                style="width: 100%; padding: 10px;"
            >

        </div>

        @include('admin.menu.partials.english-field', ['name' => 'description_en', 'label' => __('Mô tả'), 'value' => old('description_en', ''), 'type' => 'textarea', 'rows' => 5, 'margin' => '20px'])


        <div style="margin-bottom: 20px;">

            <label>
                {{ __('Mô tả') }}</label>

            <br>

            <textarea
                name="description"
                rows="5"
                style="width: 100%; padding: 10px;"
            >{{ old('description') }}</textarea>

        </div>


        <div style="margin-bottom: 20px;">

            <label>
                Image
            </label>

            <br>

            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp"
                style="width: 100%; padding: 10px;"
            >

        </div>


        <div style="margin-bottom: 20px;">

            <label>
                {{ __('Thứ tự') }}</label>

            <br>

            <input
                type="number"
                name="sort_order"
                value="{{ old('sort_order', 0) }}"
                min="0"
                style="width: 100%; padding: 10px;"
            >

        </div>


        <div style="margin-bottom: 20px;">

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
                background: #111;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
            "
        >
            {{ __('Lưu') }}</button>


        <a
            href="{{ route('admin.menu.categories.index') }}"
            style="margin-left: 10px;"
        >
            {{ __('Quay lại') }}</a>

    </form>

@endsection