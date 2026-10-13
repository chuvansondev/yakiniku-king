<div class="admin-form-field">
    <label>{{ __('Tiêu đề') }}</label>
    <br>
    <input class="admin-form-input" type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required>
</div>

@include('admin.menu.partials.english-field', ['name' => 'title_en', 'label' => __('Tiêu đề'), 'value' => $article->title_en ?? ''])

<div class="admin-form-field">
    <label>Slug</label>
    <br>
    <input class="admin-form-input" type="text" name="slug" value="{{ old('slug', $article->slug ?? '') }}" placeholder="{{ __('admin_lists.slug_auto') }}">
</div>
