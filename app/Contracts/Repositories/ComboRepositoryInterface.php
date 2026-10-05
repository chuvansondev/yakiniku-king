<?php

namespace App\Contracts\Repositories;

use App\Models\Combo;

interface ComboRepositoryInterface
{
    public function create(array $attributes, array $items): Combo;

    public function update(Combo $combo, array $attributes, array $items): void;

    public function delete(Combo $combo): void;
}
