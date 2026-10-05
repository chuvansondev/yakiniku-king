<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\TipRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TipController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $tips = $resources->all(Tip::class, orderBy: [['id', 'desc']]);

        return view(
            'admin.menu.tips.index',
            compact('tips')
        );
    }

    public function create()
    {
        return view('admin.menu.tips.create');
    }

    public function store(TipRequest $request)
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
                ->store('tips', 'public');
        }

        Tip::create([
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
            ->route('admin.menu.tips.index')
            ->with(
                'success',
                'Thêm bí kíp thành công.'
            );
    }

    public function edit(Tip $tip)
    {
        return view(
            'admin.menu.tips.edit',
            compact('tip')
        );
    }

    public function update(
        TipRequest $request,
        Tip $tip
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

        $imagePath = $tip->image;

        if ($request->hasFile('image')) {

            if ($tip->image) {
                Storage::disk('public')
                    ->delete($tip->image);
            }

            $imagePath = $request
                ->file('image')
                ->store('tips', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($tip->image) {
                Storage::disk('public')->delete($tip->image);
            }

            $imagePath = null;
        }

        $tip->update([
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
            ->route('admin.menu.tips.index')
            ->with(
                'success',
                'Cập nhật bí kíp thành công.'
            );
    }

    public function destroy(Tip $tip)
    {
        if ($tip->image) {
            Storage::disk('public')
                ->delete($tip->image);
        }

        $tip->delete();

        return redirect()
            ->route('admin.menu.tips.index')
            ->with(
                'success',
                'Xóa bí kíp thành công.'
            );
    }
}
