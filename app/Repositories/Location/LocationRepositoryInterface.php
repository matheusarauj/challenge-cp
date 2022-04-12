<?php

namespace App\Repositories\Location;

use Illuminate\Database\Eloquent\Collection;

interface LocationRepositoryInterface
{
    public function searchCity(string $search, int $page, int $perPage) : Collection;
}
