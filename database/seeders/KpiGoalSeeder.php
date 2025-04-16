<?php

namespace Database\Seeders;

use App\Models\KpiGoal;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class KpiGoalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        KpiGoal::create([
            'cgpa' => 2.50,
            'faculty_activity' => 2,
            'university_activity' => 2,
            'national_activity' => 1,
            'international_activity' => 1,
            'faculty_competition' => 1,
            'university_competition' => 1,
            'national_competition' => 1,
            'international_competition' => 1,
            'leadership' => 1,
            'graduate_on_time' => 'Yes',
            'professional_certification' => 1,
            'employability' => 'Within 1 year',
            'mobility_program' => 1,
            'admin_id' => $admin->id,
        ]);
    }
}
