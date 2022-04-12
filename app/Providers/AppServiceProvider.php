<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Experience\ExperienceRepository;
use App\Repositories\Experience\ExperienceRepositoryInterface;
use App\Repositories\Location\LocationRepository;
use App\Repositories\Location\LocationRepositoryInterface;
use App\Repositories\Profile\ProfileRepository;
use App\Repositories\Profile\ProfileRepositoryInterface;
use App\Repositories\Resume\ResumeRepository;
use App\Repositories\Resume\ResumeRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Experience\ExperienceService;
use App\Services\Experience\ExperienceServiceInterface;
use App\Services\Ftp\FtpService;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Hash\HashService;
use App\Services\Hash\HashServiceInterface;
use App\Services\Jwt\JwtService;
use App\Services\Jwt\JwtServiceInterface;
use App\Services\Location\LocationService;
use App\Services\Location\LocationServiceInterface;
use App\Services\Mail\MailService;
use App\Services\Mail\MailServiceInterface;
use App\Services\Profile\ProfileService;
use App\Services\Profile\ProfileServiceInterface;
use App\Services\Resume\ResumeService;
use App\Services\Resume\ResumeServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ExperienceRepositoryInterface::class,
            ExperienceRepository::class,
        );

        $this->app->bind(
            ExperienceServiceInterface::class,
            ExperienceService::class,
        );

        $this->app->bind(
            ResumeRepositoryInterface::class,
            ResumeRepository::class,
        );

        $this->app->bind(
            ResumeServiceInterface::class,
            ResumeService::class,
        );

        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class,
        );

        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class,
        );

        $this->app->bind(
            MailServiceInterface::class,
            MailService::class,
        );

        $this->app->bind(
            HashServiceInterface::class,
            HashService::class,
        );

        $this->app->bind(
            JwtServiceInterface::class,
            JwtService::class,
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->bind(
            FtpServiceInterface::class,
            FtpService::class,
        );

        $this->app->bind(
            ProfileServiceInterface::class,
            ProfileService::class,
        );

        $this->app->bind(
            ProfileRepositoryInterface::class,
            ProfileRepository::class,
        );

        $this->app->bind(
            LocationRepositoryInterface::class,
            LocationRepository::class,
        );

        $this->app->bind(
            LocationServiceInterface::class,
            LocationService::class,
        );
    }
}
