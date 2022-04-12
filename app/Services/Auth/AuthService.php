<?php

namespace App\Services\Auth;

use App\Exceptions\AlreadyVerifiedException;
use App\Exceptions\DuplicateMailException;
use App\Exceptions\NotVerifiedException;
use App\Exceptions\TokenNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\WrongCodeException;
use App\Exceptions\WrongPasswordException;
use App\Models\User;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Hash\HashServiceInterface;
use App\Services\Jwt\JwtServiceInterface;
use App\Services\Mail\MailServiceInterface;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class AuthService implements AuthServiceInterface
{
    private FtpServiceInterface $ftpService;
    private MailServiceInterface $mailService;
    private HashServiceInterface $hashService;
    private JwtServiceInterface $jwtService;
    private AuthRepositoryInterface $authRepository;
    private UserRepositoryInterface $userRepository;

    /**
     * AuthService constructor.
     * @param FtpServiceInterface $ftpService
     * @param MailServiceInterface $mailService
     * @param HashServiceInterface $hashService
     * @param JwtServiceInterface $jwtService
     * @param AuthRepositoryInterface $authRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        FtpServiceInterface $ftpService,
        MailServiceInterface $mailService,
        HashServiceInterface $hashService,
        JwtServiceInterface $jwtService,
        AuthRepositoryInterface $authRepository,
        UserRepository $userRepository
    ) {
        $this->ftpService = $ftpService;
        $this->mailService = $mailService;
        $this->hashService = $hashService;
        $this->jwtService = $jwtService;
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     * @param $photo
     * @return array
     * @throws \Exception
     */
    public function register(string $name, string $email, string $password, $photo) : array
    {
        if($this->mailService->verifyRecordMail($email)) {
            Throw new DuplicateMailException();
        }

        $passwordHash = $this->hashService->create($password);
        $photoPath = $this->ftpService->uploadFile($photo);

        $user = $this->authRepository->register($name, $email, $passwordHash, $photoPath);
        $token = $this->registerCodeAndSendByEmail($user);

        return [
            'user' => $user,
            'token_to_validate_code' => $token
        ];
    }

    /**
     * @param $email
     * @param $password
     * @return array
     * @throws NotVerifiedException
     * @throws WrongPasswordException
     * @throws UserNotFoundException
     */
    public function login($email, $password): array
    {
        $user = $this->userRepository->findUserByAttribute(User::EMAIL, $email);

        $this->validateUser($user);
        $this->validateEmailNotVerified($user);
        $this->validatePassword($user, $password);

        $jwt = $this->generateToken($user);

        return [
            'user' => $user,
            'token' => $jwt,
        ];
    }


    public function logout(string $token) : void
    {
        $this->authRepository->logout($token);
    }

    /**
     * @param $email
     * @return array
     * @throws AlreadyVerified
     * @throws \Exception
     */
    public function resendCode(string $email): array
    {
        $user = $this->userRepository->findUserByAttribute(User::EMAIL, $email);

        $this->validateUser($user);
        $this->validateEmailAlreadyVerified($user);
        $this->invalidateOldCodes($user);

        $token = $this->registerCodeAndSendByEmail($user);

        return [
            'user' => $user,
            'token_to_validate_code' => $token
        ];
    }

    /**
     * @param $token
     * @param $code
     * @return array
     * @throws WrongCodeException
     * @throws UserNotFoundException
     */
    public function confirmCode($token, $code) : array
    {
        $registerConfirms = $this->authRepository->getRegisterConfirmFromToken($token);
        $this->validateToken($registerConfirms);

        $codeHash = $registerConfirms->code_hash;
        $this->validateCode($codeHash, $code);

        $user = $this->userRepository->updateUser($registerConfirms->user_id, [User::VERIFIED => User::CHECKED]);

        $this->invalidateOldCodes($user);

        $jwt = $this->generateToken($user);

        return [
            'user' => $user,
            'token' => $jwt
        ];
    }

    /**
     * @return mixed
     */
    public function privacyPolicy()
    {
        $pdf = PDF::loadView('terms');
        return $pdf->download('terms.pdf');
    }

    /**
     * @param $codeHash
     * @param $code
     * @throws WrongCodeException
     */
    private function validateCode($codeHash, $code)
    {
        $isValid = $this->hashService->validate($codeHash, $code);

        if (!$isValid) {
            throw new WrongCodeException();
        }
    }

    /**
     * @param $user
     * @return mixed
     */
    private function generateToken($user)
    {
        $object = $user->toArray();
        $object['generate_date'] = Carbon::now();

        $jwt = $this->jwtService->create($object);

        $this->authRepository->trackToken($jwt);

        return $jwt;
    }

    /**
     * @param $user
     * @return void
     * @throws AlreadyVerifiedException
     */
    private function validateEmailAlreadyVerified($user): void
    {
        if ($user->verified) {
            throw new AlreadyVerifiedException();
        }
    }

    /**
     * @param $user
     * @return bool
     * @throws NotVerifiedException
     * @throws \Exception
     */
    private function validateEmailNotVerified($user): bool
    {
        if (!$user->verified) {
            $this->invalidateOldCodes($user);
            $newJwt = $this->registerCodeAndSendByEmail($user);
            throw new NotVerifiedException($newJwt);
        }
        return $user->verified;
    }

    /**
     * @param $user
     * @param $password
     * @return void
     * @throws WrongPasswordException
     */
    private function validatePassword($user, $password)
    {
        $validPassword = $this->hashService->validate($user->password, $password);

        if (!$validPassword) {
            throw new WrongPasswordException();
        }
    }

    /**
     * @param $user
     * @return bool
     * @throws UserNotFoundException
     */
    private function validateUser($user)
    {
        if(!$user) {
            Throw new UserNotFoundException();
        }
    }

    /**
     * @param $user
     * @return bool
     * @throws UserNotFoundException
     */
    private function validateToken($token)
    {
        if(!$token) {
            Throw new TokenNotFoundException();
        }
    }

    /**
     * @param $user
     */
    private function invalidateOldCodes($user)
    {
        $this->authRepository->invalidateOldCodes($user->id);
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    private function registerCodeAndSendByEmail(User $user) : string
    {
        $email = $user->email;
        $name = $user->name;
        $userId = $user->id;

        $code = $this->generateConfirmationCode();
        $codeHash = $this->hashService->create($code);
        $jwt = $this->jwtService->create(
            [
                'id' => $userId,
                'created_at' => Carbon::now()
            ]
        );

        $this->authRepository->registerCodeValidation($userId, $codeHash, $jwt);
        $this->mailService->sendConfirmationCode($code, $email, $name);

        return $jwt;
    }

    /**
     * @return int
     * @throws \Exception
     */
    private function generateConfirmationCode(): int
    {
        if (env('APP_ENV') === 'testing') {
            return 123456;
        }

        return random_int(10000, 999999);
    }
}
