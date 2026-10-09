@php
    $fieldId = str_replace(['[', ']'], '-', $name);
    $fieldType = $type ?? 'text';
@endphp

<div class="admin-form-field">
    <label for="{{ $fieldId }}">{{ $label }} (English)</label>

    @if ($fieldType === 'textarea')
        <textarea
            id="{{ $fieldId }}"
            name="{{ $name }}"
            rows="{{ $rows ?? 4 }}"
            class="admin-form-input"
        >{{ old($name, $value ?? '') }}</textarea>
    @else
        <input
            id="{{ $fieldId }}"
            type="text"
            name="{{ $name }}"
            value="{{ old($name, $value ?? '') }}"
            class="admin-form-input"
        >
    @endif
</div>
