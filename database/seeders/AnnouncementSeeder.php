<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        Announcement::create([
            'title' => 'Welcome to New Semester',
            'description' => 'Welcome to the new academic semester...',
            'admin_id' => $admin->id,
        ]);
    }
}