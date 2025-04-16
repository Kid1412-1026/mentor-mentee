<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentUsers = User::where('role', 'student')->get();

        $matric_no = [
            'BI21110296',
            'BI21110297',
            'BI21110298',
            'BI21110299',
            'BI21110300'
        ];

        $studentInfo = [
            [
                'state' => 'Selangor',
                'phone' => '0107066313',
                'motto' => 'Never Stop Learning'
            ],
            [
                'state' => 'Johor',
                'phone' => '0127766314',
                'motto' => 'Learn from Yesterday'
            ],
            [
                'state' => 'Penang',
                'phone' => '0139966315',
                'motto' => 'Knowledge is Power'
            ],
            [
                'state' => 'Sabah',
                'phone' => '0149966316',
                'motto' => 'Dream Big'
            ],
            [
                'state' => 'Sarawak',
                'phone' => '0159966317',
                'motto' => 'Strive for Excellence'
            ]
        ];

        foreach ($studentUsers as $index => $user) {
            Student::create([
                'matric_no' => $matric_no[$index],
                'name' => $user->name,
                'program' => 'Bachelor of Computer Science (Hons) in Software Engineering',
                'email' => $user->email,
                'intake' => 2021,
                'phone' => $studentInfo[$index]['phone'],
                'state' => $studentInfo[$index]['state'],
                'address' => 'Sample Address',
                'motto' => $studentInfo[$index]['motto'],
                'faculty' => 'Faculty of Computing and Informatics (FKI)',
                'user_id' => $user->id,
                'admin_id' => 1
            ]);
        }
    }
}

