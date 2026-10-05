<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:menu_categories,id'], 'name' => ['required', 'string', 'max:255'], 'name_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('menu_items', 'slug')->ignore($this->route('item'))],
            'description' => ['nullable', 'string'], 'description_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 'remove_image' => ['nullable', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'], 'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_must_try' => ['nullable', 'boolean'], 'status' => ['nullable', 'boolean'],
        ];
    }
}
