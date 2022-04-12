<?php


namespace App\Services\Resume;


use App\Models\Resume;

interface ResumeServiceInterface
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
    );

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
    ) : Resume;

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
    ) : Resume;

    public function deleteResume(
        int $resumeId,
        int $userId
    ) : void;
}
