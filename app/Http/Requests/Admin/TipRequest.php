<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'], 'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tips', 'slug')->ignore($this->route('tip'))],
            'short_description' => ['nullable', 'string'], 'short_description_en' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'], 'content_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 'remove_image' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'], 'status' => ['nullable', 'boolean'],
        ];
    }
}
