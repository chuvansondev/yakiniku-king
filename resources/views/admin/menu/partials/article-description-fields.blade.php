@include('admin.menu.partials.english-field', ['name' => 'short_description_en', 'label' => 'Mô tả ngắn', 'value' => $article->short_description_en ?? '', 'type' => 'textarea', 'rows' => 4])

<div class="admin-form-field">
    <label>Mô tả ngắn</label>
    <br>
    <textarea class="admin-form-input" name="short_description" rows="4">{{ old('short_description', $article->short_description ?? '') }}</textarea>
</div>

<div class="admin-form-field">
    <label>{{ $contentLabel }}</label>
    <br>
    <textarea class="admin-form-input" name="content" rows="15">{{ old('content', $article->content ?? '') }}</textarea>
</div>

@include('admin.menu.partials.english-field', ['name' => 'content_en', 'label' => $contentLabel, 'value' => $article->content_en ?? '', 'type' => 'textarea', 'rows' => 15])
