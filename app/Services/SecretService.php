<?php

namespace App\Services;

use App\Contracts\Repositories\SecretRepositoryInterface;
use App\Models\Recipe;
use App\Models\Tip;
use Illuminate\Database\Eloquent\Collection;

class SecretService
{
    public function __construct(private readonly SecretRepositoryInterface $articles) {}

    public function recipes(): Collection { return $this->articles->recipes(); }

    public function tips(): Collection { return $this->articles->tips(); }

    public function recipe(string $slug): Recipe
    {
        $recipe = $this->articles->findRecipeBySlug($slug);
        abort_if(! $recipe || ! $this->isPublished($recipe), 404);
        return $recipe;
    }

    public function tip(string $slug): Tip
    {
        $tip = $this->articles->findTipBySlug($slug);
        abort_if(! $tip || ! $this->isPublished($tip), 404);
        return $tip;
    }

    private function isPublished(Recipe|Tip $article): bool
    {
        return $article->status && (! $article->published_at || $article->published_at->isPast());
    }
}
