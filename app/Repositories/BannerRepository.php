<?php

namespace App\Repositories;

use App\Enums\BannerType;
use App\Contracts\Repositories\BannerRepositoryInterface;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BannerRepository implements BannerRepositoryInterface
{
    public function homepageBanners(): Collection
    {
        return Banner::query()->where('status', true)
            ->where(fn (Builder $query) => $query
                ->where(fn (Builder $query) => $query->where('type', BannerType::Image->value)->whereNotNull('image'))
                ->orWhere(fn (Builder $query) => $query->where('type', BannerType::Video->value)->whereNotNull('video_url')))
            ->orderBy('sort_order')->orderBy('id')->get();
    }
}
