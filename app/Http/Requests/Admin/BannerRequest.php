<?php

namespace App\Http\Requests\Admin;

use App\Enums\BannerType;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'], 'title_en' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:'.implode(',', array_map(fn (BannerType $type): string => $type->value, BannerType::cases()))], 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'], 'video_url' => ['nullable', 'url', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'], 'sort_order' => ['nullable', 'integer', 'min:0'], 'status' => ['nullable', 'boolean'],
        ];
    }
}
