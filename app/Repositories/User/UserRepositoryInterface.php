<?php


namespace App\Repositories\User;


use App\Models\User;

interface UserRepositoryInterface
{
    public function createUser(string $name, string $email, string $passwordHash, string $photoPath) : User;

    public function findUserByAttribute(string $attribute, $value) : User;

    public function updateUser(int $id, array $attributes) : User;
}
