<?php

namespace App\Repositories;

use App\Contracts\Repositories\ComboRepositoryInterface;
use App\Models\Combo;

class ComboRepository implements ComboRepositoryInterface
{
    public function create(array $attributes, array $items): Combo
    {
        $combo = Combo::query()->create($attributes);
        $combo->menuItems()->sync($items);

        return $combo;
    }

    public function update(Combo $combo, array $attributes, array $items): void
    {
        $combo->update($attributes);
        $combo->menuItems()->sync($items);
    }

    public function delete(Combo $combo): void
    {
        $combo->delete();
    }
}
