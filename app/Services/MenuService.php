<?php

namespace App\Services;

use App\Enums\KidsFoodCategory;
use App\Enums\KidsItemType;
use App\Models\Combo;
use App\Models\MenuItem;
use App\Contracts\Repositories\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MenuService
{
    public const KIDS_ITEM_TYPE_LABELS = [KidsItemType::Food->value => 'Đồ ăn', KidsItemType::Utensil->value => 'Dụng cụ', KidsItemType::Supply->value => 'Đồ dùng'];
    public const FOOD_CATEGORY_LABELS = [KidsFoodCategory::Meat->value => 'Thịt', KidsFoodCategory::SideDish->value => 'Món ăn kèm', KidsFoodCategory::Vegetable->value => 'Rau củ', KidsFoodCategory::Soup->value => 'Súp', KidsFoodCategory::RiceNoodles->value => 'Cơm và mì', KidsFoodCategory::Dessert->value => 'Tráng miệng'];
    public const KIDS_ITEM_SORT_LABELS = ['featured' => 'Thứ tự mặc định', 'name_asc' => 'Tên: A-Z', 'name_desc' => 'Tên: Z-A', 'price_asc' => 'Giá: thấp đến cao', 'price_desc' => 'Giá: cao đến thấp'];

    public function __construct(private readonly MenuRepositoryInterface $menus) {}

    public function categories(): Collection { return $this->menus->activeCategories(); }

    public function headerCategories(): Collection { return $this->menus->activeCategoriesForHeader(); }

    public function items(?int $categoryId = null, bool $mustTry = false): Collection { return $this->menus->activeItems($categoryId, $mustTry); }

    public function kidsItems(string $type, string $foodCategory, string $sort): Collection { return $this->menus->activeKidsItems($type, $foodCategory, $sort); }

    public function kidsPageData(mixed $requestedType, mixed $requestedFoodCategory, mixed $requestedSort): array
    {
        $type = is_string($requestedType) && array_key_exists($requestedType, self::KIDS_ITEM_TYPE_LABELS) ? $requestedType : '';
        $foodCategory = is_string($requestedFoodCategory) && array_key_exists($requestedFoodCategory, self::FOOD_CATEGORY_LABELS) ? $requestedFoodCategory : '';
        $sort = is_string($requestedSort) && array_key_exists($requestedSort, self::KIDS_ITEM_SORT_LABELS) ? $requestedSort : 'featured';

        return [
            'kidsItems' => $this->kidsItems($type, $foodCategory, $sort),
            'kidsItemTypeLabels' => array_map(fn (string $label): string => __($label), self::KIDS_ITEM_TYPE_LABELS),
            'foodCategoryLabels' => array_map(fn (string $label): string => __($label), self::FOOD_CATEGORY_LABELS),
            'kidsItemSortLabels' => array_map(fn (string $label): string => __($label), self::KIDS_ITEM_SORT_LABELS),
            'selectedType' => $type,
            'selectedFoodCategory' => $foodCategory,
            'selectedSort' => $sort,
        ];
    }

    public function combos(): Collection
    {
        $combos = $this->menus->activeCombos();
        $combos->each(function (Combo $combo): void {
            $combo->setAttribute('retail_total', $combo->menuItems->sum(fn (MenuItem $item): float => (float) $item->price * $item->pivot->quantity));
        });
        return $combos;
    }

    public function promotions(): Collection { return $this->menus->activePromotions(); }
}
