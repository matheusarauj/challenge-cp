<?php


namespace App\Services\Experience;


use App\Models\Experience;
use App\Repositories\Experience\ExperienceRepositoryInterface;

class ExperienceService implements ExperienceServiceInterface
{
    private ExperienceRepositoryInterface $experienceRepository;

    public function __construct(ExperienceRepositoryInterface $experienceRepository)
    {
        $this->experienceRepository = $experienceRepository;
    }

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
        return $this->experienceRepository->createExperience(
            $userId,
            $company,
            $description,
            $start,
            $end,
            $resumeId,
            $cityId
        );
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
        return $this->experienceRepository->getExperiences(
            $userId,
            $company,
            $start,
            $end,
            $resumeId,
            $cityId,
        );
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
        return $this->experienceRepository->updateExperience(
            $userId,
            $company,
            $description,
            $start,
            $end,
            $cityId,
            $experienceId
        );
    }

    public function deleteExperience(int $userId, int $experienceId) : void
    {
        $this->experienceRepository->deleteExperience($userId, $experienceId);
    }
}
