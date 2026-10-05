<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface
{
    public function activeCategories(): Collection;

    public function activeCategoriesForHeader(): Collection;

    public function activeItems(?int $categoryId = null, bool $mustTry = false): Collection;

    public function activeKidsItems(string $type, string $foodCategory, string $sort): Collection;

    public function activeCombos(): Collection;

    public function activePromotions(): Collection;
}
