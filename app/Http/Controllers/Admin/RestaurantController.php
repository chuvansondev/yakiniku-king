<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\RestaurantRequest;

class RestaurantController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $restaurants = $resources->all(Restaurant::class, orderBy: [['name', 'asc']]);

        return view('admin.menu.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.menu.restaurants.create');
    }

    public function store(RestaurantRequest $request, AdminResourceService $resources)
    {
        $validated = $request->validated();

        $validated['image'] = $resources->storeImage($request->file('image'), 'restaurants');

        $validated['status'] = $request->boolean('status');

        $resources->create(Restaurant::class, $validated);

        return redirect()
            ->route('admin.menu.restaurants.index')
            ->with('success', 'Thêm nhà hàng thành công.');
    }

    public function show(Restaurant $restaurant)
    {
        return redirect()->route(
            'admin.menu.restaurants.edit',
            $restaurant
        );
    }

    public function edit(Restaurant $restaurant)
    {
        return view('admin.menu.restaurants.edit', compact('restaurant'));
    }

    public function update(RestaurantRequest $request, Restaurant $restaurant, AdminResourceService $resources)
    {
        $validated = $request->validated();
        $oldImage = null;

        if ($request->hasFile('image') || $request->boolean('remove_image')) {
            $oldImage = $restaurant->image;
            $validated['image'] = $resources->replaceImage($request->file('image'), $request->boolean('remove_image'), $restaurant->image, 'restaurants');
        }

        unset($validated['remove_image']);
        $validated['status'] = $request->boolean('status');

        $resources->update($restaurant, $validated);
        $resources->deleteImage($oldImage);

        return redirect()
            ->route('admin.menu.restaurants.index')
            ->with('success', 'Cập nhật nhà hàng thành công.');
    }

    public function destroy(Restaurant $restaurant, AdminResourceService $resources)
    {
        $resources->delete($restaurant);

        return redirect()
            ->route('admin.menu.restaurants.index')
            ->with('success', 'Xóa nhà hàng thành công.');
    }
}
