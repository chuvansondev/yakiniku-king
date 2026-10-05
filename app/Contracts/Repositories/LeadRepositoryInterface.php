<?php

namespace App\Contracts\Repositories;

use App\Models\Lead;

interface LeadRepositoryInterface
{
    public function create(array $attributes): Lead;
}
