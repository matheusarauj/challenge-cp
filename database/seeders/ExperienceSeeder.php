<?php


namespace Database\Seeders;

use App\Models\Experience;
use App\Models\User;
use App\Models\Resume;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExperienceSeeder extends Seeder
{
    public function run()
    {

        $users = User::latest()->take(5)->get();
        $usersIds = $users->pluck('id');

        $resumes = Resume::latest()->take(10)->get();
        $resumesIds = $resumes->pluck('id');

        for ($i = 0; $i < 25; $i += 1) {
            $randomUserIndex = random_int(0, 4);
            $randomResumeIndex = random_int(0, 9);

            $selectedCity = random_int(0, 100);
            $randomString = Str::random(10);

            $experienceObjects[] = [
                'company' => $randomString,
                'description' => $randomString,
                'start' => '2010-01-01',
                'end' => '2010-01-05',
                'active' => 1,
                'resume_id' => $resumesIds[$randomResumeIndex],
                'city_id' => $selectedCity,
                'registered_by' => $usersIds[$randomUserIndex],
            ];
        }

        Experience::insert($experienceObjects);
    }
}
