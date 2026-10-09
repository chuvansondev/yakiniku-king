<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(MenuService $menuService, ?MenuCategory $category = null): View
    {
        return $this->menuView(__('Thực đơn'), $category, $menuService);
    }

    public function mustTry(MenuService $menuService): View
    {
        return $this->menuView(__('Món nên thử'), null, $menuService, true);
    }

    public function forKids(Request $request, MenuService $menuService): View
    {
        $pageData = $menuService->kidsPageData(
            $request->query('type'),
            $request->query('food_category'),
            $request->query('sort'),
        );

        return view('frontend.menu.index', [
            'pageTitle' => __('Dành cho trẻ em'),
            'menuCategories' => $menuService->categories(),
            'menuItems' => collect(),
            ...$pageData,
            'combos' => collect(),
            'promotions' => collect(),
        ]);
    }

    public function combos(MenuService $menuService): View
    {
        $combos = $menuService->combos();

        return view('frontend.menu.index', [
            'pageTitle' => __('Combo'),
            'menuCategories' => $menuService->categories(),
            'menuItems' => collect(),
            'combos' => $combos,
            'promotions' => collect(),
        ]);
    }

    public function promotions(MenuService $menuService): View
    {
        return view('frontend.menu.index', [
            'pageTitle' => __('Khuyến mãi'),
            'menuCategories' => $menuService->categories(),
            'menuItems' => collect(),
            'combos' => collect(),
            'promotions' => $menuService->promotions(),
        ]);
    }

    private function menuView(string $pageTitle, ?MenuCategory $category, MenuService $menuService, bool $mustTry = false): View
    {
        abort_if($category && ! $category->status, 404);

        $menuItems = $menuService->items($category?->id, $mustTry);

        return view('frontend.menu.index', [
            'pageTitle' => $pageTitle,
            'menuCategories' => $menuService->categories(),
            'menuItems' => $menuItems,
            'menuItemsByCategory' => $menuItems->groupBy('category_id'),
            'combos' => collect(),
            'promotions' => collect(),
        ]);
    }
}
