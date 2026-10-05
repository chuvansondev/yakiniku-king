<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface BannerRepositoryInterface
{
    public function homepageBanners(): Collection;
}
