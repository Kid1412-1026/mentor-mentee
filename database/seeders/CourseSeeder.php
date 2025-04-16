<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'code' => 'KT14303',
                'name' => 'Programming Principles',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'  // Removed space before (FKI)
            ],
            [
                'code' => 'KT14503',
                'name' => 'Mathematics for Computing',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT14403',
                'name' => 'Discrete Structures',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT14603',
                'name' => 'Data Structures & Algorithms',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT14803',
                'name' => 'Network Fundamental',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT24703',
                'name' => 'Computer Architecture & Organization',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT24903',
                'name' => 'Probability and Statistics',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT24202',
                'name' => 'Artificial Intelligence',
                'credit_hour' => 2,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT24403',
                'name' => 'Operating Systems',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT24603',
                'name' => 'Database',
                'credit_hour' => 3,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT34102',
                'name' => 'Technopreneurship',
                'credit_hour' => 2,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KT34202',
                'name' => 'Ethics, Professionalism and E-Community',
                'credit_hour' => 2,
                'section' => 'Faculty Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)'
            ],
            [
                'code' => 'KK14202',
                'name' => 'Software Project Management',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK24503',
                'name' => 'Requirements Engineering',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK24703',
                'name' => 'Object Oriented Programming',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK24803',
                'name' => 'Software Design',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK24402',
                'name' => 'Graphics and Visualisation',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK24202',
                'name' => 'Big Data Analytics',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK24602',
                'name' => 'Internet of Things',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34703',
                'name' => 'Web Engineering',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34302',
                'name' => 'Parallel Programming and Distributed System',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34102',
                'name' => 'Software Engineering Project',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34502',
                'name' => 'Augmented Reality/Virtual Reality',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34702',
                'name' => 'Cloud Computing',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34202',
                'name' => 'Project I',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34223',
                'name' => 'UI/UX Design',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34243',
                'name' => 'Mobile Apps Development',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK34263',
                'name' => 'Software Quality and Testing',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK44104',
                'name' => 'Project II',
                'credit_hour' => 4,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK44113',
                'name' => 'Software Evolution Management',
                'credit_hour' => 3,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK44102',
                'name' => 'CyberSecurity',
                'credit_hour' => 2,
                'section' => 'Programme Core',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'KK44212',
                'name' => 'Industrial Training',
                'credit_hour' => 12,
                'section' => 'Industrial Training',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'UNI1',
                'name' => 'University Core 1',
                'credit_hour' => 2,
                'section' => 'University Core',
                'faculty' => 'Knowledge and Language Learning(PPIB)'  // Removed space before (PPIB)
            ],
            [
                'code' => 'UNI2',
                'name' => 'University Core 2',
                'credit_hour' => 2,
                'section' => 'University Core',
                'faculty' => 'Knowledge and Language Learning(PPIB)'
            ],
            [
                'code' => 'UNI3',
                'name' => 'University Core 3',
                'credit_hour' => 2,
                'section' => 'University Core',
                'faculty' => 'Knowledge and Language Learning(PPIB)'
            ],
            [
                'code' => 'LANGUAGE1',
                'name' => 'Language Level 1',
                'credit_hour' => 2,
                'section' => 'Language',
                'faculty' => 'Knowledge and Language Learning(PPIB)'
            ],
            [
                'code' => 'LANGUAGE2',
                'name' => 'Language Level 2',
                'credit_hour' => 2,
                'section' => 'Language',
                'faculty' => 'Knowledge and Language Learning(PPIB)'
            ],
            [
                'code' => 'LANGUAGE3',
                'name' => 'Language Level 3',
                'credit_hour' => 2,
                'section' => 'Language',
                'faculty' => 'Knowledge and Language Learning(PPIB)'
            ],
            [
                'code' => 'EXXXXX2',
                'name' => 'Co-Curriculum',
                'credit_hour' => 2,
                'section' => 'Co-Curriculum',
                'faculty' => 'Co-Curricular(PKPP)'  // Removed space before (PKPP)
            ],
            [
                'code' => 'ELECTIVE1',
                'name' => 'Elective 1(REFER TO PPIB COURSE OFFER)',
                'credit_hour' => 2,
                'section' => 'Elective',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'ELECTIVE2',
                'name' => 'Elective 2(REFER TO PPIB COURSE OFFER)',
                'credit_hour' => 2,
                'section' => 'Elective',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'ELECTIVE3',
                'name' => 'Elective 3(REFER TO OTHER FACULTY COURSE OFFER)',
                'credit_hour' => 3,
                'section' => 'Elective',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'ELECTIVE4',
                'name' => 'Elective 4(REFER TO OTHER FACULTY COURSE OFFER)',
                'credit_hour' => 3,
                'section' => 'Elective',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
            [
                'code' => 'ELECTIVE5',
                'name' => 'Elective 5(REFER TO OTHER FACULTY COURSE OFFER)',
                'credit_hour' => 3,
                'section' => 'Elective',
                'faculty' => 'Faculty of Computing and Informatics(FKI)',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}


