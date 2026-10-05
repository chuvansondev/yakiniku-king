<?php

namespace App\Contracts\Repositories;

use App\Models\Recipe;
use App\Models\Tip;
use Illuminate\Database\Eloquent\Collection;

interface SecretRepositoryInterface
{
    public function recipes(): Collection;

    public function tips(): Collection;

    public function findRecipeBySlug(string $slug): ?Recipe;

    public function findTipBySlug(string $slug): ?Tip;
}
