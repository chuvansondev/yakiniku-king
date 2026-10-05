<?php

namespace App\Http\Requests\Admin;

use App\Enums\KidsFoodCategory;
use App\Enums\KidsItemType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KidsItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'], 'name_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('kids_items', 'slug')->ignore($this->route('kidsItem') ?? $this->route('kids_item'))],
            'type' => ['required', Rule::in(array_map(fn (KidsItemType $type): string => $type->value, KidsItemType::cases()))],
            'food_category' => ['nullable', Rule::in(array_map(fn (KidsFoodCategory $category): string => $category->value, KidsFoodCategory::cases()))],
            'description' => ['nullable', 'string'], 'description_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 'remove_image' => ['nullable', 'boolean'],
            'price' => ['nullable', 'numeric', 'min:0'], 'sort_order' => ['nullable', 'integer', 'min:0'], 'status' => ['nullable', 'boolean'],
        ];
    }
}
