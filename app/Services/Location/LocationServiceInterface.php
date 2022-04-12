<?php


namespace App\Services\Location;


use Illuminate\Database\Eloquent\Collection;

interface LocationServiceInterface
{
    public function searchCity(string $search, int $page, int $perPage) : Collection;
}
