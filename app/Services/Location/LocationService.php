<?php


namespace App\Services\Location;


use App\Repositories\Location\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LocationService implements LocationServiceInterface
{
    private LocationRepositoryInterface $locationRepository;

    /**
     * LocationService constructor.
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param $search
     * @param $page
     * @param $perPage
     * @return mixed
     */
    public function searchCity(string $search, int $page, int $perPage) : Collection
    {
        return $this->locationRepository->searchCity($search, $page, $perPage);
    }
}
