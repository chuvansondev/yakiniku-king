<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'], 'name_en' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'], 'address_en' => ['nullable', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:30'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'], 'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'google_map_url' => ['nullable', 'url', 'max:255'], 'opening_time' => ['nullable', 'date_format:H:i'], 'closing_time' => ['nullable', 'date_format:H:i'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 'remove_image' => ['nullable', 'boolean'], 'status' => ['nullable', 'boolean'],
        ];
    }
}
