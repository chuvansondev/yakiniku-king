<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Contracts\Repositories\LeadRepositoryInterface;

class LeadService
{
    public function __construct(private readonly LeadRepositoryInterface $leads) {}

    public function register(array $attributes): Lead
    {
        return $this->leads->create([...$attributes, 'status' => LeadStatus::New->value]);
    }
}
