<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\BannerRequest;
use App\Jobs\DeletePublicFile;

class BannerController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $banners = $resources->all(Banner::class, orderBy: [['sort_order', 'asc'], ['id', 'asc']]);

        return view('admin.menu.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.menu.banners.create');
    }

    public function store(BannerRequest $request, AdminResourceService $resources)
    {
        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Upload image
        |--------------------------------------------------------------------------
        */

        $resources->create(Banner::class, [
            'title' => $validated['title'] ?? null,
            'title_en' => $validated['title_en'] ?? null,

            'type' => $validated['type'],

            'image' => $resources->storeImage($request->file('image'), 'banners'),

            'video_url' => $validated['video_url'] ?? null,

            'link' => $validated['link'] ?? null,

            'sort_order' => $validated['sort_order'] ?? 0,

            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.menu.banners.index')
            ->with('success', 'Thêm Banner thành công.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.menu.banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner, AdminResourceService $resources)
    {
        $validated = $request->validated();

        $imagePath = $banner->image;

        /*
        |--------------------------------------------------------------------------
        | Nếu upload ảnh mới
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if ($banner->image) {
                DeletePublicFile::dispatch($banner->image)->afterCommit();
            }

            $imagePath = $request
                ->file('image')
                ->store('banners', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($banner->image) {
                DeletePublicFile::dispatch($banner->image)->afterCommit();
            }

            $imagePath = null;
        }

        $resources->update($banner, [
            'title' => $validated['title'] ?? null,
            'title_en' => $validated['title_en'] ?? null,

            'type' => $validated['type'],

            'image' => $imagePath,

            'video_url' => $validated['video_url'] ?? null,

            'link' => $validated['link'] ?? null,

            'sort_order' => $validated['sort_order'] ?? 0,

            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.menu.banners.index')
            ->with('success', 'Cập nhật Banner thành công.');
    }

    public function destroy(Banner $banner, AdminResourceService $resources)
    {
        if ($banner->image) {
            DeletePublicFile::dispatch($banner->image)->afterCommit();
        }

        $resources->delete($banner);

        return redirect()
            ->route('admin.menu.banners.index')
            ->with('success', 'Xóa Banner thành công.');
    }
}
