<?php


namespace App\Repositories\Profile;


use App\Models\User;

interface ProfileRepositoryInterface
{
    public function update($userId, $attributes);

    public function changePassword(int $userId, string $newPassword) : User;
}
