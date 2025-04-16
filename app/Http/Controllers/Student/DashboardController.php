<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the student record associated with this user
        $student = Student::where('user_id', $user->id)
            ->with([
                'activities' => function($query) {
                    $query->latest()->take(5);
                },
                'challenges' => function($query) {
                    $query->latest()->take(5);
                },
                'kpiIndexes' => function($query) {
                    $query->latest()->first();
                },
                'enrolments' => function($query) {
                    $query->with('course')
                        ->latest()
                        ->take(5);
                }
            ])
            ->first();

        // Get latest announcements
        $announcements = Announcement::latest()->take(10)->get();

        if (!$student) {
            return view('pages.student.dashboard', [
                'student' => null,
                'statistics' => null,
                'announcements' => $announcements
            ]);
        }

        // Calculate statistics for the dashboard
        $statistics = [
            'total_activities' => $student->activities()->count(),
            'total_challenges' => $student->challenges()->count(),
            'latest_cgpa' => $student->kpiIndexes()->latest()->value('cgpa') ?? 0,
            'total_courses' => $student->enrolments()->count(),
            'average_pointer' => $student->enrolments()->avg('pointer') ?? 0,
        ];

        // For debugging
        \Log::info('Student Dashboard Data:', [
            'student_id' => $student->id,
            'statistics' => $statistics
        ]);

        return view('pages.student.dashboard', compact('student', 'statistics', 'announcements'));
    }
}


