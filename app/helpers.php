<?php

use App\Services\SettingService;
use App\Enums\AppLocale;
use Illuminate\Database\Eloquent\Model;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return app(SettingService::class)->get($key, $default);
    }
}

if (! function_exists('localized_text')) {
    function localized_text(Model $model, string $attribute): ?string
    {
        if (app()->getLocale() === AppLocale::English->value) {
            $translatedValue = $model->getAttribute($attribute.'_en');

            if (is_string($translatedValue) && trim($translatedValue) !== '') {
                return $translatedValue;
            }
        }

        $value = $model->getAttribute($attribute);

        return is_string($value) && $value !== '' ? __($value) : null;
    }
}

if (! function_exists('localized_setting')) {
    function localized_setting(string $key, mixed $default = null): mixed
    {
        return app(SettingService::class)->localized($key, $default);
    }
}

if (! function_exists('localized_price')) {
    function localized_price(float|int|string $amount): string
    {
        if (app()->getLocale() === AppLocale::English->value) {
            return number_format((float) $amount, 0, '.', ',').' VND';
        }

        return number_format((float) $amount, 0, ',', '.').' đ';
    }
}
