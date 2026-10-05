<?php

namespace App\Repositories;

use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Models\Lead;

class LeadRepository implements LeadRepositoryInterface
{
    public function create(array $attributes): Lead
    {
        return Lead::query()->create($attributes);
    }
}
