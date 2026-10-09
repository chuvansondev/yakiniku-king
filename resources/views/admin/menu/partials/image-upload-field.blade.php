<div
    class="admin-form-field"
    data-vue-image-field
    data-name="{{ $name ?? 'image' }}"
    data-accept="{{ $accept ?? '.jpg,.jpeg,.png,.webp' }}"
    data-image-path="{{ $image ?? '' }}"
    data-storage-url="{{ asset('storage') }}"
    data-image-alt="{{ $alt ?? '' }}"
    data-label="{{ $label ?? 'Hình ảnh' }}"
    data-preview-style="{{ $previewStyle ?? 'width:300px; max-height:200px; object-fit:cover;' }}"
    data-show-caption="{{ ($showCaption ?? true) ? 'true' : 'false' }}"
></div>
