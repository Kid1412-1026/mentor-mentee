<?php

namespace Database\Seeders;

use App\Models\Rule;
use App\Models\Course;
use App\Models\Programme;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();
        $programme = Programme::first();

        Rule::create([
            'course_id' => $course->id,
            'programme_id' => $programme->id,
            'intake' => 2021
        ]);
    }
}
