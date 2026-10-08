<?php

namespace App\Services;

use App\Contracts\Repositories\ComboRepositoryInterface;
use App\Jobs\DeletePublicFile;
use App\Models\Combo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComboService
{
    public function __construct(private readonly ComboRepositoryInterface $combos) {}

    public function create(array $attributes, ?UploadedFile $image, bool $status): Combo
    {
        $items = $attributes['items'] ?? [];
        $attributes = $this->prepareAttributes($attributes, $status);
        $attributes['image'] = $image?->store('menu/combos', 'public');

        return DB::transaction(fn (): Combo => $this->combos->create($attributes, $this->prepareItems($items)));
    }

    public function update(Combo $combo, array $attributes, ?UploadedFile $image, bool $removeImage, bool $status): void
    {
        $items = $attributes['items'] ?? [];
        $attributes = $this->prepareAttributes($attributes, $status);
        $oldImage = $combo->image;

        if ($image !== null) {
            $attributes['image'] = $image->store('menu/combos', 'public');
        } elseif ($removeImage) {
            $attributes['image'] = null;
        } else {
            $attributes['image'] = $oldImage;
        }

        DB::transaction(fn () => $this->combos->update($combo, $attributes, $this->prepareItems($items)));

        if (($image !== null || $removeImage) && $oldImage) {
            DeletePublicFile::dispatch($oldImage)->afterCommit();
        }
    }

    public function delete(Combo $combo): void
    {
        $image = $combo->image;
        $this->combos->delete($combo);

        if ($image) {
            DeletePublicFile::dispatch($image)->afterCommit();
        }
    }

    private function prepareAttributes(array $attributes, bool $status): array
    {
        $attributes['slug'] = ($attributes['slug'] ?? null) ?: Str::slug($attributes['name']);
        $attributes['sort_order'] = $attributes['sort_order'] ?? 0;
        $attributes['status'] = $status;
        unset($attributes['items'], $attributes['image'], $attributes['remove_image']);

        return $attributes;
    }

    private function prepareItems(array $items): array
    {
        $sync = [];
        foreach ($items as $item) {
            $sync[$item['menu_item_id']] = ['quantity' => $item['quantity']];
        }

        return $sync;
    }
}
