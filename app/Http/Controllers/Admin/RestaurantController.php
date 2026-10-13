<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\RestaurantRequest;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index(\Illuminate\Http\Request $request, AdminResourceService $resources)
    {
        $restaurants = $resources->paginate(Restaurant::class, $request, orderBy: [['name', 'asc']]);

        return view('admin.menu.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.menu.restaurants.create');
    }

    public function store(RestaurantRequest $request, AdminResourceService $resources)
    {
        $validated = $request->validated();
        $tables = $validated['tables'] ?? [];
        unset($validated['tables']);

        $validated['image'] = $resources->storeImage($request->file('image'), 'restaurants');

        $validated['status'] = $request->boolean('status');

        DB::transaction(function () use ($resources, $validated, $tables): void {
            $restaurant = $resources->create(Restaurant::class, $validated);
            $this->syncTables($restaurant, $tables);
        });

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
        $restaurant->load('tables');
        return view('admin.menu.restaurants.edit', compact('restaurant'));
    }

    public function update(RestaurantRequest $request, Restaurant $restaurant, AdminResourceService $resources)
    {
        $validated = $request->validated();
        $hasTables = array_key_exists('tables', $validated);
        $tables = $validated['tables'] ?? [];
        unset($validated['tables']);
        $oldImage = null;

        if ($request->hasFile('image') || $request->boolean('remove_image')) {
            $oldImage = $restaurant->image;
            $validated['image'] = $resources->replaceImage($request->file('image'), $request->boolean('remove_image'), $restaurant->image, 'restaurants');
        }

        unset($validated['remove_image']);
        $validated['status'] = $request->boolean('status');

        DB::transaction(function () use ($resources, $restaurant, $validated, $tables, $hasTables): void {
            $resources->update($restaurant, $validated);
            if ($hasTables) $this->syncTables($restaurant, $tables);
        });
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

    private function syncTables(Restaurant $restaurant, array $tables): void
    {
        $activeCodes = [];
        foreach ($tables as $table) {
            $activeCodes[] = $table['code'];
            $restaurant->tables()->updateOrCreate(
                ['code' => $table['code']],
                [
                    'floor' => $table['floor'],
                    'capacity' => $table['capacity'],
                    'position' => $table['position'] ?? 0,
                    'status' => (bool) ($table['status'] ?? false),
                ],
            );
        }

        $restaurant->tables()->whereNotIn('code', $activeCodes)->update(['status' => false]);
    }
}
