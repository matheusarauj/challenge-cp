<?php


namespace App\Services\Resume;


use App\Models\Resume;
use App\Repositories\Resume\ResumeRepositoryInterface;

class ResumeService implements ResumeServiceInterface
{
    private ResumeRepositoryInterface $resumeRepository;

    public function __construct(ResumeRepositoryInterface $resumeRepository)
    {
        $this->resumeRepository = $resumeRepository;
    }

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
        return $this->resumeRepository->getResumes(
          $userId,
          $search,
          $mail,
          $phone,
          $site,
          $level,
          $scholarship,
          $techStack,
          $cityId,
          $createdAt,
          $page,
          $perPage
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
        return $this->resumeRepository->createResume(
          $userId,
          $name,
          $description,
          $mail,
          $phone,
          $site,
          $level,
          $scholarship,
          $techStack,
          $cityId
        );
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
        return $this->resumeRepository->updateResume(
            $resumeId,
            $name,
            $description,
            $mail,
            $phone,
            $site,
            $level,
            $scholarship,
            $techStack,
            $cityId
        );
    }

    public function deleteResume(int $resumeId, int $userId) : void
    {
        $this->resumeRepository->deleteResume($resumeId, $userId);
    }
}
