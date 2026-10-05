<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\MenuCategoryRequest;

class MenuCategoryController extends Controller
{
    /**
     * Hiển thị danh sách category
     */
    public function index(AdminResourceService $resources)
    {
        $categories = $resources->all(MenuCategory::class, orderBy: [['sort_order', 'asc'], ['id', 'asc']]);

        return view('admin.menu.categories.index', compact('categories'));
    }

    /**
     * Hiển thị form tạo category
     */
    public function create()
    {
        return view('admin.menu.categories.create');
    }

    /**
     * Lưu category mới
     */
    public function store(MenuCategoryRequest $request, AdminResourceService $resources)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = $resources->slug($validated['name']);
        }

        $validated['image'] = $resources->storeImage($request->file('image'), 'menu/categories');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['status'] = $request->boolean('status');

        $resources->create(MenuCategory::class, $validated);

        return redirect()
            ->route('admin.menu.categories.index')
            ->with('success', 'Thêm danh mục thành công.');
    }

    /**
     * Hiển thị form sửa category
     */
    public function edit(MenuCategory $category)
    {
        return view(
            'admin.menu.categories.edit',
            compact('category')
        );
    }

    /**
     * Cập nhật category
     */
    public function update(MenuCategoryRequest $request, MenuCategory $category, AdminResourceService $resources)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = $resources->slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $resources->replaceImage($request->file('image'), false, $category->image, 'menu/categories');
        } elseif ($request->boolean('remove_image')) {
            $validated['image'] = $resources->replaceImage(null, true, $category->image, 'menu/categories');
        } else {
            unset($validated['image']);
        }

        unset($validated['remove_image']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['status'] = $request->boolean('status');

        $resources->update($category, $validated);

        return redirect()
            ->route('admin.menu.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    /**
     * Xóa category
     */
    public function destroy(MenuCategory $category, AdminResourceService $resources)
    {
        $resources->delete($category);

        return redirect()
            ->route('admin.menu.categories.index')
            ->with('success', 'Xóa danh mục thành công.');
    }
}
