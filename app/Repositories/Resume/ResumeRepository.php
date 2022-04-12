<?php


namespace App\Repositories\Resume;


use App\Exceptions\ResumeNotFoundException;
use App\Models\Resume;
use Carbon\Carbon;

class ResumeRepository implements ResumeRepositoryInterface
{

    public function getResumes(
        ?int $userId,
        string $search,
        string $mail,
        string $phone,
        string $site,
        string $level,
        string $scholarship,
        string $techStack,
        int $cityId,
        string $createdAt,
        int $page,
        int $perPage
    ) {
        $query = Resume::leftJoin('users', 'users.id', '=', 'resumes.registered_by')
            ->leftJoin('cities', 'cities.id', '=', 'resumes.city_id')
            ->leftJoin('states', 'states.id', '=', 'cities.state_id')
            ->where('resumes.active', 1);

        $this->filterByOwner($query, $userId);
        $this->filterBySearch($query, $search);
        $this->filterByMail($query, $mail);
        $this->filterByPhone($query, $phone);
        $this->filterBySite($query, $site);
        $this->filterByLevel($query, $level);
        $this->filterByScholarship($query, $scholarship);
        $this->filterByTechStack($query, $techStack);
        $this->filterByCity($query, $cityId);
        $this->orderByDate($query,$createdAt);

        return $query->paginate(
            $perPage,
            [
                'resumes.id as id',
                'resumes.full_name as full_name',
                'resumes.description as description',
                'resumes.mail as mail',
                'resumes.phone as phone',
                'resumes.site as site',
                'resumes.active as active',
                'resumes.level as level',
                'resumes.scholarship as scholarship',
                'resumes.tech_stack as tech_stack',
                'cities.name as city',
                'states.name as state'
            ],
            'page', $page
        );
    }

    public function createResume(
        int $userId,
        string $name,
        string $description,
        string $mail,
        string $phone,
        string $site,
        string $level,
        string $scholarship,
        string $techStack,
        int $cityId
    ) : Resume {
        $resume = new Resume();

        $resume->full_name = $name;
        $resume->description = $description;
        $resume->mail = $mail;
        $resume->phone = $phone;
        $resume->site = $site;
        $resume->level = $level;
        $resume->scholarship = $scholarship;
        $resume->tech_stack = $techStack;
        $resume->city_id = $cityId;
        $resume->active = 1;
        $resume->registered_by = $userId;

        $resume->save();

        return $resume;
    }

    public function updateResume(
        int $resumeId,
        string $name,
        string $description,
        string $mail,
        string $phone,
        string $site,
        string $level,
        string $scholarship,
        string $techStack,
        int $cityId
    ): Resume {
        $resume = Resume::find($resumeId);

        $this->validateResume($resume);

        $resume->full_name = $name;
        $resume->description = $description;
        $resume->mail = $mail;
        $resume->phone = $phone;
        $resume->site = $site;
        $resume->level = $level;
        $resume->scholarship = $scholarship;
        $resume->tech_stack = $techStack;
        $resume->city_id = $cityId;

        $resume->save();

        return $resume;
    }

    public function deleteResume(int $resumeId, int $userId) : void
    {
        $resume = Resume::find($resumeId);

        $this->validateResume($resume);

        $resume->active = 0;
        $resume->deleted_by = $userId;
        $resume->deleted_at = Carbon::now();

        $resume->save();
    }

    private function validateResume($resume)
    {
        if(!$resume) {
            Throw new ResumeNotFoundException();
        }
    }

    private function filterByOwner(&$query, $userId)
    {
        if (isset($userId)) {
            $query->where('resumes.registered_by', $userId);
        }
    }

    private function filterBySearch(&$query, $search)
    {
        if (isset($search)) {
            $query->where('resumes.full_name', 'like', '%'. $search .'%');
        }
    }

    private function filterByMail(&$query, $mail)
    {
        if (isset($mail)) {
            $query->where('resumes.mail', 'like', '%'. $mail .'%');
        }
    }

    private function filterByPhone(&$query, $phone)
    {
        if (isset($phone)) {
            $query->where('resumes.phone', 'like', '%'. $phone .'%');
        }
    }

    private function filterBySite(&$query, $site)
    {
        if (isset($site)) {
            $query->where('resumes.site', 'like', '%'. $site .'%');
        }
    }

    private function filterByLevel(&$query, $level)
    {
        if (isset($level)) {
            $query->where('resumes.level', $level);
        }
    }

    private function filterByScholarship(&$query, $scholarship)
    {
        if (isset($scholarship)) {
            $query->where('resumes.scholarship', $scholarship);
        }
    }

    private function filterByTechStack(&$query, $techStack)
    {
        if (isset($techStack)) {
            $query->where('resumes.tech_stack', 'like', '%'. $techStack .'%');
        }
    }

    private function filterByCity(&$query, $cityId)
    {
        if (isset($cityId)) {
            $query->where('resumes.city_id', $cityId);
        }
    }

    private function orderByDate(&$query, $order)
    {
        $selectedOrder = $order ?? 'asc';

        $query->orderBy('resumes.created_at', $selectedOrder);
    }
}
