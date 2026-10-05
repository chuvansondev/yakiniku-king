<?php

namespace App\Http\Requests\Admin;

use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:30'], 'email' => ['nullable', 'email', 'max:255'], 'message' => ['nullable', 'string'], 'status' => ['required', 'in:'.implode(',', array_map(fn (LeadStatus $status): string => $status->value, LeadStatus::cases()))]];
    }
}
