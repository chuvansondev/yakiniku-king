<?php

namespace App\Http\Requests;

use App\Enums\AppLocale;
use Illuminate\Foundation\Http\FormRequest;

class LocaleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array { return ['locale' => ['required', 'in:'.implode(',', array_map(fn (AppLocale $locale): string => $locale->value, AppLocale::cases()))]]; }
}
