<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\RecipeRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $recipes = $resources->all(Recipe::class, orderBy: [['id', 'desc']]);

        return view(
            'admin.menu.recipes.index',
            compact('recipes')
        );
    }

    public function create()
    {
        return view('admin.menu.recipes.create');
    }

    public function store(RecipeRequest $request)
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
                ->store('recipes', 'public');
        }

        Recipe::create([
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,

            'slug' => $validated['slug'],

            'short_description' => $validated['short_description'] ?? null,
            'short_description_en' => $validated['short_description_en'] ?? null,

            'content' => $validated['content'] ?? null,
            'content_en' => $validated['content_en'] ?? null,

            'image' => $imagePath,

            'status' => $request->boolean('status'),

            'published_at' => $validated['published_at'] ?? null,
        ]);

        return redirect()
            ->route('admin.menu.recipes.index')
            ->with(
                'success',
                'Thêm công thức thành công.'
            );
    }

    public function edit(Recipe $recipe)
    {
        return view(
            'admin.menu.recipes.edit',
            compact('recipe')
        );
    }

    public function update(
        RecipeRequest $request,
        Recipe $recipe
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

        $imagePath = $recipe->image;

        if ($request->hasFile('image')) {

            if ($recipe->image) {
                Storage::disk('public')
                    ->delete($recipe->image);
            }

            $imagePath = $request
                ->file('image')
                ->store('recipes', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }

            $imagePath = null;
        }

        $recipe->update([
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,

            'slug' => $validated['slug'],

            'short_description' => $validated['short_description'] ?? null,
            'short_description_en' => $validated['short_description_en'] ?? null,

            'content' => $validated['content'] ?? null,
            'content_en' => $validated['content_en'] ?? null,

            'image' => $imagePath,

            'status' => $request->boolean('status'),

            'published_at' => $validated['published_at'] ?? null,
        ]);

        return redirect()
            ->route('admin.menu.recipes.index')
            ->with(
                'success',
                'Cập nhật công thức thành công.'
            );
    }

    public function destroy(Recipe $recipe)
    {
        if ($recipe->image) {
            Storage::disk('public')
                ->delete($recipe->image);
        }

        $recipe->delete();

        return redirect()
            ->route('admin.menu.recipes.index')
            ->with(
                'success',
                'Xóa công thức thành công.'
            );
    }
}
