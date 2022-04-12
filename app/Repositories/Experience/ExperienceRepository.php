<?php


namespace App\Repositories\Experience;


use App\Exceptions\ExperienceNotFoundException;
use App\Models\Experience;
use Carbon\Carbon;

class ExperienceRepository implements ExperienceRepositoryInterface
{

    public function createExperience(
        int $userId,
        string $company,
        string $description,
        string $start,
        string $end,
        int $resumeId,
        int $cityId
    ) : Experience
    {
        $experience = new Experience();

        $experience->company = $company;
        $experience->description = $description;
        $experience->start = $start;
        $experience->end = $end;
        $experience->resume_id = $resumeId;
        $experience->city_id = $cityId;
        $experience->registered_by = $userId;
        $experience->active = 1;

        $experience->save();

        return $experience;
    }

    public function getExperiences(
        ?int $userId,
        string $company,
        string $start,
        string $end,
        int $resumeId,
        int $cityId
    )
    {
        $query = Experience::select(
            'experiences.id',
            'experiences.company',
            'experiences.description',
            'experiences.start',
            'experiences.end',
            'cities.name',
            'experiences.resume_id')
            ->leftJoin('users', 'users.id', '=', 'experiences.registered_by')
            ->leftJoin('cities', 'cities.id', '=', 'experiences.city_id')
            ->leftJoin('states', 'states.id', '=', 'cities.state_id')
            ->where('active', 1);

        $this->filterByCompany($query, $company);
        $this->filterByDate($query, $start, $end);
        $this->filterByCity($query, $cityId);

        $query->get();
    }

    public function updateExperience(
        int $userId,
        string $company,
        string $description,
        string $start,
        string $end,
        int $cityId,
        int $experienceId
    ) : Experience
    {
        $experience = Experience::find($experienceId);

        $this->validateExperience($experience);

        $experience->company = $company;
        $experience->description = $description;
        $experience->start = $start;
        $experience->end = $end;
        $experience->city_id = $cityId;
        $experience->registered_by = $userId;

        $experience->save();

        return $experience;
    }

    public function deleteExperience(int $userId, int $experienceId) : void
    {
        $experience = Experience::find($experienceId);

        $this->validateExperience($experience);

        $experience->active = 0;
        $experience->deleted_at = Carbon::now();
        $experience->deleted_by = $userId;

        $experience->save();
    }

    private function validateExperience($experience)
    {
        if(!$experience){
            Throw new ExperienceNotFoundException();
        }
    }

    private function filterByCompany(&$query, $company)
    {
        if(isset($company)) {
            $query->where('experiences.company', 'like', '%'. $company .'%');
        }
    }

    private function filterByDate(&$query, $start, $end)
    {
        if(isset($start)) {
            $query->where('experiences.start', '>=', $start);
        }

        if(isset($end)) {
            $query->where('experiences.end', '<=', $end);
        }
    }

    private function filterByCity(&$query, $cityId)
    {
        if(isset($cityId)) {
            $query->where('experiences.city_id', $cityId);
        }
    }
}
