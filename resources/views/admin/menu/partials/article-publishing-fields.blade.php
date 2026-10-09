<div class="admin-form-field">
    <label>Ngày đăng</label>
    <br>
    <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" style="width:100%; padding:10px;">
</div>

<div class="admin-form-field">
    <label>
        <input type="checkbox" name="status" value="1" {{ old('status', $article->status ?? true) ? 'checked' : '' }}>
        {{ $statusLabel }}
    </label>
</div>
