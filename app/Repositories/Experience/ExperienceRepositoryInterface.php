<?php


namespace App\Repositories\Experience;


use App\Models\Experience;

interface ExperienceRepositoryInterface
{
    public function createExperience(
        int $userId,
        string $company,
        string $description,
        string $start,
        string $end,
        int $resumeId,
        int $cityId
    ) : Experience;

    public function getExperiences(
        ?int $userId,
        string $company,
        string $start,
        string $end,
        int $resumeId,
        int $cityId
    );

    public function updateExperience(
        int $userId,
        string $company,
        string $description,
        string $start,
        string $end,
        int $cityId,
        int $experienceId
    ) : Experience;

    public function deleteExperience(int $userId, int $experienceId) : void;
}
