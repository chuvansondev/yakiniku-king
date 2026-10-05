<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KidsFoodCategory;
use App\Enums\KidsItemType;
use App\Http\Controllers\Controller;
use App\Models\KidsItem;
use App\Services\AdminResourceService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\KidsItemRequest;
use Illuminate\View\View;

class KidsItemController extends Controller
{
    private const TYPE_LABELS = [
        KidsItemType::Food->value => 'Đồ ăn',
        KidsItemType::Utensil->value => 'Dụng cụ',
        KidsItemType::Supply->value => 'Đồ dùng',
    ];

    private const FOOD_CATEGORY_LABELS = [
        KidsFoodCategory::Meat->value => 'Thịt',
        KidsFoodCategory::SideDish->value => 'Món ăn kèm',
        KidsFoodCategory::Vegetable->value => 'Rau củ',
        KidsFoodCategory::Soup->value => 'Súp',
        KidsFoodCategory::RiceNoodles->value => 'Cơm và mì',
        KidsFoodCategory::Dessert->value => 'Tráng miệng',
    ];

    public function index(AdminResourceService $resources): View
    {
        $items = $resources->all(KidsItem::class, orderBy: [['type', 'asc'], ['sort_order', 'asc'], ['name', 'asc']]);

        return view('admin.kids-items.index', [
            'items' => $items,
            'typeLabels' => self::TYPE_LABELS,
            'foodCategoryLabels' => self::FOOD_CATEGORY_LABELS,
        ]);
    }

    public function create(): View
    {
        return view('admin.kids-items.create', [
            'typeLabels' => self::TYPE_LABELS,
            'foodCategoryLabels' => self::FOOD_CATEGORY_LABELS,
        ]);
    }

    public function store(KidsItemRequest $request, AdminResourceService $resources): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $resources->slug(($validated['slug'] ?? null) ?: $validated['name']);
        $validated['image'] = $resources->storeImage($request->file('image'), 'kids/items');
        $validated['food_category'] = $validated['type'] === KidsItemType::Food->value
            ? ($validated['food_category'] ?? null)
            : null;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['status'] = $request->boolean('status');

        $resources->create(KidsItem::class, $validated);

        return redirect()
            ->route('admin.kids-items.index')
            ->with('success', 'Thêm nội dung trẻ em thành công.');
    }

    public function edit(KidsItem $kidsItem): View
    {
        return view('admin.kids-items.edit', [
            'kidsItem' => $kidsItem,
            'typeLabels' => self::TYPE_LABELS,
            'foodCategoryLabels' => self::FOOD_CATEGORY_LABELS,
        ]);
    }

    public function update(KidsItemRequest $request, KidsItem $kidsItem, AdminResourceService $resources): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $resources->slug(($validated['slug'] ?? null) ?: $validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $resources->replaceImage($request->file('image'), false, $kidsItem->image, 'kids/items');
        } elseif ($request->boolean('remove_image')) {
            $validated['image'] = $resources->replaceImage(null, true, $kidsItem->image, 'kids/items');
        } else {
            unset($validated['image']);
        }

        unset($validated['remove_image']);
        $validated['food_category'] = $validated['type'] === KidsItemType::Food->value
            ? ($validated['food_category'] ?? null)
            : null;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['status'] = $request->boolean('status');

        $resources->update($kidsItem, $validated);

        return redirect()
            ->route('admin.kids-items.index')
            ->with('success', 'Cập nhật nội dung trẻ em thành công.');
    }

    public function destroy(KidsItem $kidsItem, AdminResourceService $resources): RedirectResponse
    {
        $resources->deleteImage($kidsItem->image);
        $resources->delete($kidsItem);

        return redirect()
            ->route('admin.kids-items.index')
            ->with('success', 'Xóa nội dung trẻ em thành công.');
    }
}
