<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    public function index(SettingService $settingService)
    {
        $settings = $settingService->all();

        return view('admin.menu.settings.index', compact('settings'));
    }

    public function update(Request $request, SettingService $settingService)
    {
        $allowed = [
            'site_name', 'site_name_en', 'hotline', 'email',
            'facebook_url', 'youtube_url', 'zalo_url',
            'footer_address', 'footer_address_en',
            'footer_description', 'footer_description_en',
        ];
        $settings = $request->input('settings', []);

        if (! is_array($settings)) {
            throw ValidationException::withMessages(['settings' => __('The settings field must be an array.')]);
        }

        $rules = ['settings' => ['required', 'array:'.implode(',', $allowed)]];
        foreach ($allowed as $key) {
            $rules["settings.$key"] = match ($key) {
                'email' => ['nullable', 'email', 'max:255'],
                'facebook_url', 'youtube_url', 'zalo_url' => ['nullable', 'url', 'max:255'],
                default => ['nullable', 'string', 'max:5000'],
            };
        }

        Validator::make(['settings' => $settings], $rules)->validate();
        $settings = array_intersect_key($settings, array_flip($allowed));

        $settingService->updateMany($settings);

        return redirect()
            ->route('admin.menu.settings.index')
            ->with('success', 'Cập nhật cài đặt thành công.');
    }
}
