<?php

namespace Tests\Helpers;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestDatabaseCreationHelper
{

    public function createTestUser(
        $name = 'Sicrano',
        $email = 'sicrano@email.com',
        $password = '123mudar@@',
        $verified = 0
    ): User {
        $user = new User();

        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->photo = '...';
        $user->verified = $verified;

        $user->save();

        return $user;
    }

    public function updateUser($userId, $column, $value)
    {
        $user = new User();

        $user->exists = true;
        $user->id = $userId;
        $user->{$column} = $value;

        $user->save();
    }

    public function createResume(
        $registeredBy = 1,
        $fullName = 'Matheus Araujo Curriculo',
        $description = 'Desenvolvedor Frontend',
        $mail = 'matheus@teste.com',
        $phone = '83996222245',
        $site = 'www.mysite.com',
        $level = 'JUNIOR',
        $scholarship = 'HIGHSCHOOL',
        $techStack = 'php - js - sql',
        $active = 1,
        $cityId = 1
    )
    {
        $resume = new Resume();

        $resume->full_name = $fullName;
        $resume->description = $description;
        $resume->mail = $mail;
        $resume->phone = $phone;
        $resume->site = $site;
        $resume->active = $active;
        $resume->level = $level;
        $resume->scholarship = $scholarship;
        $resume->tech_stack = $techStack;
        $resume->registered_by = $registeredBy;
        $resume->city_id = $cityId;

        $resume->save();

        return $resume;
    }
}
