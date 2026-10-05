<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(SettingService $settingService)
    {
        $settings = $settingService->all();

        return view('admin.menu.settings.index', compact('settings'));
    }

    public function update(Request $request, SettingService $settingService)
    {
        $settings = $request->input('settings', []);

        $settingService->updateMany($settings);

        return redirect()
            ->route('admin.menu.settings.index')
            ->with('success', 'Cập nhật cài đặt thành công.');
    }
}
