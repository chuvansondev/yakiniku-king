<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\MenuItem;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\ComboRequest;
use App\Services\ComboService;

class ComboController extends Controller
{
    /**
     * Danh sách Combo
     */
    public function index(AdminResourceService $resources)
    {
        $combos = $resources->all(Combo::class, with: ['menuItems'], orderBy: [['sort_order', 'asc'], ['id', 'asc']]);

        return view(
            'admin.menu.combos.index',
            compact('combos')
        );
    }

    /**
     * Form thêm Combo
     */
    public function create(AdminResourceService $resources)
    {
        $menuItems = $resources->all(MenuItem::class, orderBy: [['name', 'asc']], filters: ['status' => true]);

        return view(
            'admin.menu.combos.create',
            compact('menuItems')
        );
    }

    /**
     * Lưu Combo
     */
    public function store(ComboRequest $request, ComboService $comboService)
    {
        $validated = $request->validated();

        $comboService->create($validated, $request->file('image'), $request->boolean('status'));

        return redirect()
            ->route('admin.menu.combos.index')
            ->with(
                'success',
                'Thêm Combo thành công.'
            );
    }

    /**
     * Form sửa Combo
     */
    public function edit(Combo $combo, AdminResourceService $resources)
    {
        $menuItems = $resources->all(MenuItem::class, orderBy: [['name', 'asc']], filters: ['status' => true]);

        $combo->load('menuItems');

        return view(
            'admin.menu.combos.edit',
            compact(
                'combo',
                'menuItems'
            )
        );
    }

    /**
     * Cập nhật Combo
     */
    public function update(
        ComboRequest $request,
        Combo $combo,
        ComboService $comboService,
    ) {
        $validated = $request->validated();

        $comboService->update($combo, $validated, $request->file('image'), $request->boolean('remove_image'), $request->boolean('status'));

        return redirect()
            ->route('admin.menu.combos.index')
            ->with(
                'success',
                'Cập nhật Combo thành công.'
            );
    }

    /**
     * Xóa Combo
     */
    public function destroy(Combo $combo, ComboService $comboService)
    {
        $comboService->delete($combo);

        return redirect()
            ->route('admin.menu.combos.index')
            ->with(
                'success',
                'Xóa Combo thành công.'
            );
    }
}
