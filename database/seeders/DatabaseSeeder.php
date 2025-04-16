<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Course;
use App\Models\Programme;
use App\Models\Rule;
use App\Models\KpiGoal;
use App\Models\Career;
use App\Models\Announcement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            StudentSeeder::class,
            CourseSeeder::class,
            ProgrammeSeeder::class,
            RuleSeeder::class,
            KpiGoalSeeder::class,
            CareerSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}

