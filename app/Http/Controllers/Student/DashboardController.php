<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Announcement;
use App\Models\Counseling;
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
            ->select(['id', 'user_id', 'img', 'name', 'matric_no', 'admin_id'])
            ->first();

        // Get latest announcements
        $announcements = Announcement::latest()->take(10)->get();

        if (!$student || !$student->admin_id) {
            return view('pages.student.dashboard', [
                'student' => $student,
                'statistics' => null,
                'announcements' => $announcements,
                'nextSession' => null,
                'accountLocked' => true
            ]);
        }

        // Get the next upcoming counseling session
        $nextSession = Counseling::where('student_id', $student->id)
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->first();

        // Calculate statistics for the dashboard
        $statistics = [
            'total_activities' => $student->activities()->count(),
            'total_challenges' => $student->challenges()->count(),
            'latest_cgpa' => $student->kpiIndexes()->latest()->value('cgpa') ?? 0,
            'total_courses' => $student->enrolments()->count(),
            'average_pointer' => $student->enrolments()->avg('pointer') ?? 0,
        ];

        return view('pages.student.dashboard', [
            'student' => $student,
            'statistics' => $statistics,
            'announcements' => $announcements,
            'nextSession' => $nextSession,
            'accountLocked' => false
        ]);
    }
}








