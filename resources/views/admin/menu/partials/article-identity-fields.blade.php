<div class="admin-form-field">
    <label>Tiêu đề</label>
    <br>
    <input class="admin-form-input" type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required>
</div>

@include('admin.menu.partials.english-field', ['name' => 'title_en', 'label' => 'Tiêu đề', 'value' => $article->title_en ?? ''])

<div class="admin-form-field">
    <label>Slug</label>
    <br>
    <input class="admin-form-input" type="text" name="slug" value="{{ old('slug', $article->slug ?? '') }}" placeholder="Để trống để Laravel tự tạo">
</div>
