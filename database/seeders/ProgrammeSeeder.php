<?php

namespace Database\Seeders;

use App\Models\Programme;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            ['code' => 'UH6481001', 'name' => 'Bachelor of Computer Science (Hons) in Software Engineering'],
            ['code' => 'UH6481002', 'name' => 'Bachelor of Computer Science (Hons) in Network Engineering'],
            ['code' => 'UH6481003', 'name' => 'Bachelor of Computer Science (Hons) in Multimedia Technology'],
            ['code' => 'UH6481004', 'name' => 'Bachelor of Computer Science (Hons) in Business Computing'],
            ['code' => 'UH6481005', 'name' => 'Bachelor of Computer Science (Hons) in Data Science']
        ];

        foreach ($programmes as $programme) {
            Programme::create($programme);
        }
    }
}
