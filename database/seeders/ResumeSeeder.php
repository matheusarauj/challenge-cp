<?php


namespace Database\Seeders;

use App\Models\User;
use App\Models\Resume;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResumeSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            Resume::JUNIOR_LEVEL,
            Resume::STAFF_LEVEL,
            Resume::SENIOR_LEVEL
        ];

        $scholarships = [
            Resume::HIGHSCHOOL_SCHOLARSHIP,
            Resume::BACHELOR_SCHOLARSHIP,
            Resume::MASTER_SCHOLARSHIP
        ];

        $users = User::latest()->take(5)->get();
        $usersIds = $users->pluck('id');

        for ($i = 0; $i < 25; $i += 1) {
            $randomUserIndex = random_int(0, 4);
            $randomLevelIndex = random_int(0, 2);
            $randomScholarshipIndex = random_int(0, 2);

            $selectedCity = random_int(0, 100);
            $randomString = Str::random(10);

            $resumeObjects[] = [
                'full_name' => $randomString,
                'description' => $randomString,
                'mail' => $randomString,
                'phone' => $randomString,
                'site' => $randomString,
                'active' => 1,
                'level' => $levels[$randomLevelIndex],
                'scholarship' => $scholarships[$randomScholarshipIndex],
                'tech_stack' => $randomString,
                'city_id' => $selectedCity,
                'registered_by' => $usersIds[$randomUserIndex],
            ];
        }

        Resume::insert($resumeObjects);
    }
}
