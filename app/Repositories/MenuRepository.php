<?php

namespace App\Repositories;

use App\Contracts\Repositories\MenuRepositoryInterface;
use App\Models\Combo;
use App\Models\KidsItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function activeCategories(): Collection
    {
        return MenuCategory::query()->where('status', true)->orderBy('sort_order')->orderBy('name')->get();
    }

    public function activeCategoriesForHeader(): Collection
    {
        return MenuCategory::query()->where('status', true)->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'slug']);
    }

    public function activeItems(?int $categoryId = null, bool $mustTry = false): Collection
    {
        return MenuItem::query()
            ->where('status', true)
            ->when($categoryId !== null, fn ($query) => $query->where('category_id', $categoryId))
            ->when($mustTry, fn ($query) => $query->where('is_must_try', true))
            ->with('category')->orderBy('sort_order')->orderBy('name')->get();
    }

    public function activeKidsItems(string $type, string $foodCategory, string $sort): Collection
    {
        $query = KidsItem::query()->where('status', true)
            ->when($type !== '', fn ($query) => $query->where('type', $type))
            ->when($foodCategory !== '', fn ($query) => $query->where('food_category', $foodCategory));

        match ($sort) {
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'price_asc' => $query->orderBy('price')->orderBy('name'),
            'price_desc' => $query->orderByDesc('price')->orderBy('name'),
            default => $query->orderBy('type')->orderBy('food_category')->orderBy('sort_order')->orderBy('name'),
        };

        return $query->get();
    }

    public function activeCombos(): Collection
    {
        return Combo::query()->where('status', true)->with([
            'menuItems' => fn ($query) => $query->where('menu_items.status', true)->with('category')->orderBy('menu_items.sort_order')->orderBy('menu_items.name'),
        ])->orderBy('sort_order')->orderBy('name')->get();
    }

    public function activePromotions(): Collection
    {
        return Promotion::query()->where('status', true)->latest()->get();
    }
}
