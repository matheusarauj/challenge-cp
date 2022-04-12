<?php


namespace App\Repositories\User;


use App\Exceptions\UserNotFoundException;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @param $name
     * @param $email
     * @param $passwordHash
     * @param $photoPath
     * @return User
     */
    public function createUser(string $name, string $email, string $passwordHash, string $photoPath) : User
    {
        $user = new User();

        $user->name = $name;
        $user->email = $email;
        $user->password = $passwordHash;
        $user->photo = $photoPath;
        $user->verified = User::CHECKED;

        $user->save();

        return $user;
    }

    public function findUserByAttribute(string $attribute, $value) : User
    {
        return User::where($attribute, $value)->first();
    }

    public function updateUser($id, $attributes) : User
    {
        $user = User::find($id)->first();

        if(!$user) {
            Throw new UserNotFoundException();
        }

        foreach ($attributes as $key => $value) {
            $user->{$key} = $value;
        }

        $user->save();
        return $user;
    }
}
