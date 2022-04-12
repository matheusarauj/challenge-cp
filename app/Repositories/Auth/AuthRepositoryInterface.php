<?php


namespace App\Repositories\Auth;

use App\Models\RegisterConfirm;
use App\Models\TokenTacking;
use App\Models\User;

interface AuthRepositoryInterface
{
    public function register(
        string $name,
        string $email,
        string $passwordHash,
        string $photoPath
    ) : User;

    public function registerCodeValidation(
        int $userId,
        string $codeHash,
        string $token
    ) : RegisterConfirm;

    public function invalidateOldCodes(int $userId) : void;

    public function trackToken(string $jwt) : TokenTacking;

    public function getRegisterConfirmFromToken(string $token);

    public function logout(string $token) : void;
}
