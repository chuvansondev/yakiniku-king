<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComboRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $combo = $this->route('combo');
        return [
            'name' => ['required', 'string', 'max:255'], 'name_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('combos', 'slug')->ignore($combo)],
            'description' => ['nullable', 'string'], 'description_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 'remove_image' => ['nullable', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'], 'original_price' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'sort_order' => ['nullable', 'integer', 'min:0'], 'status' => ['nullable', 'boolean'], 'items' => ['nullable', 'array'],
            'items.*.menu_item_id' => ['required', 'exists:menu_items,id'], 'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
