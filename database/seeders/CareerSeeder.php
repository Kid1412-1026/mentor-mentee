<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        Career::create([
            'title' => 'Software Developer Position',
            'description' => 'We are looking for a talented software developer...',
            'admin_id' => $admin->id,
        ]);
    }
}