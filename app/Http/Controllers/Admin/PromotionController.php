<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\PromotionRequest;
use App\Jobs\DeletePublicFile;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $promotions = $resources->all(Promotion::class, orderBy: [['id', 'desc']]);

        return view(
            'admin.menu.promotions.index',
            compact('promotions')
        );
    }

    public function create()
    {
        return view('admin.menu.promotions.create');
    }

    public function store(PromotionRequest $request)
    {
        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Tạo slug tự động
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug(
                $validated['title']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload image
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store('promotions', 'public');
        }

        Promotion::create([
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,

            'slug' => $validated['slug'],

            'short_description' => $validated['short_description'] ?? null,
            'short_description_en' => $validated['short_description_en'] ?? null,

            'description' => $validated['description'] ?? null,
            'description_en' => $validated['description_en'] ?? null,

            'image' => $imagePath,

            'start_date' => $validated['start_date'] ?? null,

            'end_date' => $validated['end_date'] ?? null,

            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.menu.promotions.index')
            ->with(
                'success',
                'Thêm khuyến mãi thành công.'
            );
    }

    public function edit(Promotion $promotion)
    {
        return view(
            'admin.menu.promotions.edit',
            compact('promotion')
        );
    }

    public function update(
        PromotionRequest $request,
        Promotion $promotion
    ) {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug(
                $validated['title']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Giữ ảnh cũ nếu không upload ảnh mới
        |--------------------------------------------------------------------------
        */

        $imagePath = $promotion->image;

        if ($request->hasFile('image')) {

            if ($promotion->image) {
                DeletePublicFile::dispatch($promotion->image)->afterCommit();
            }

            $imagePath = $request
                ->file('image')
                ->store('promotions', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($promotion->image) {
                DeletePublicFile::dispatch($promotion->image)->afterCommit();
            }

            $imagePath = null;
        }

        $promotion->update([
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,

            'slug' => $validated['slug'],

            'short_description' => $validated['short_description'] ?? null,
            'short_description_en' => $validated['short_description_en'] ?? null,

            'description' => $validated['description'] ?? null,
            'description_en' => $validated['description_en'] ?? null,

            'image' => $imagePath,

            'start_date' => $validated['start_date'] ?? null,

            'end_date' => $validated['end_date'] ?? null,

            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.menu.promotions.index')
            ->with(
                'success',
                'Cập nhật khuyến mãi thành công.'
            );
    }

    public function destroy(Promotion $promotion)
    {
        if ($promotion->image) {
            DeletePublicFile::dispatch($promotion->image)->afterCommit();
        }

        $promotion->delete();

        return redirect()
            ->route('admin.menu.promotions.index')
            ->with(
                'success',
                'Xóa khuyến mãi thành công.'
            );
    }
}

