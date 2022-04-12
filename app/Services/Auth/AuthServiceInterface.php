<?php


namespace App\Services\Auth;


interface AuthServiceInterface
{
    public function register(string $name, string $email, string $password, $photo) : array;

    public function login(string $email, string $password) : array;

    public function logout(string $token) : void;

    public function resendCode(string $email) : array;

    public function confirmCode(string $token, string $code) : array;

    public function privacyPolicy();
}
