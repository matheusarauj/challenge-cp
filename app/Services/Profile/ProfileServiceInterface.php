<?php


namespace App\Services\Profile;


use App\Models\User;

interface ProfileServiceInterface
{
    public function update($userId, $name, $email, $photo);
    public function changePassword(int $userId, string $currentPassword, string $newPassword) : User;
}
