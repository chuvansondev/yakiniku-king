<?php

namespace App\Repositories;

use App\Contracts\Repositories\SecretRepositoryInterface;
use App\Models\Recipe;
use App\Models\Tip;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SecretRepository implements SecretRepositoryInterface
{
    public function recipes(): Collection
    {
        return $this->published(Recipe::query())->get();
    }

    public function tips(): Collection
    {
        return $this->published(Tip::query())->get();
    }

    public function findRecipeBySlug(string $slug): ?Recipe
    {
        return Recipe::query()->where('slug', $slug)->first();
    }

    public function findTipBySlug(string $slug): ?Tip
    {
        return Tip::query()->where('slug', $slug)->first();
    }

    private function published(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where(fn (Builder $query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->latest('published_at')->latest('id');
    }
}
