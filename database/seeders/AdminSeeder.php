<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        Admin::create([
            'name' => 'Admin User',
            'phone' => '0123456789',
            'email' => 'admin@example.com',
            'faculty' => 'Faculty of Computing and Informatics (FKI)',
            'pose' => 'Head of Programme(Software Engineering)',
            'user_id' => $adminUser->id,
        ]);
    }
}
