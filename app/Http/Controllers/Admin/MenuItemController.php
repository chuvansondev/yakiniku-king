<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\MenuItemRequest;

class MenuItemController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $items = $resources->all(MenuItem::class, with: ['category'], orderBy: [['sort_order', 'asc'], ['id', 'asc']]);

        return view('admin.menu.items.index', compact('items'));
    }

    public function create(AdminResourceService $resources)
    {
        $categories = $resources->all(MenuCategory::class, orderBy: [['sort_order', 'asc']], filters: ['status' => true]);

        return view(
            'admin.menu.items.create',
            compact('categories')
        );
    }

    public function store(MenuItemRequest $request, AdminResourceService $resources)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = $resources->slug($validated['name']);
        }

        $validated['image'] = $resources->storeImage($request->file('image'), 'menu/items');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_must_try'] = $request->boolean('is_must_try');
        $validated['status'] = $request->boolean('status');

        $resources->create(MenuItem::class, $validated);

        return redirect()
            ->route('admin.menu.items.index')
            ->with('success', 'Thêm món ăn thành công.');
    }

    public function edit(MenuItem $item, AdminResourceService $resources)
    {
        $categories = $resources->all(MenuCategory::class, orderBy: [['sort_order', 'asc']], filters: ['status' => true]);

        return view(
            'admin.menu.items.edit',
            compact('item', 'categories')
        );
    }

    public function update(MenuItemRequest $request, MenuItem $item, AdminResourceService $resources)
    {
        $validated = $request->validated();
        $oldImage = null;

        if (empty($validated['slug'])) {
            $validated['slug'] = $resources->slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $oldImage = $item->image;
            $validated['image'] = $resources->replaceImage($request->file('image'), false, $item->image, 'menu/items');
        } elseif ($request->boolean('remove_image')) {
            $oldImage = $item->image;
            $validated['image'] = $resources->replaceImage(null, true, $item->image, 'menu/items');
        } else {
            unset($validated['image']);
        }

        unset($validated['remove_image']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_must_try'] = $request->boolean('is_must_try');
        $validated['status'] = $request->boolean('status');

        $resources->update($item, $validated);
        $resources->deleteImage($oldImage);

        return redirect()
            ->route('admin.menu.items.index')
            ->with('success', 'Cập nhật món ăn thành công.');
    }

    public function destroy(MenuItem $item, AdminResourceService $resources)
    {
        $resources->delete($item);

        return redirect()
            ->route('admin.menu.items.index')
            ->with('success', 'Xóa món ăn thành công.');
    }
}
