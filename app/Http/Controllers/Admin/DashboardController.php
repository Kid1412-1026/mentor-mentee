<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Counseling;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function calculatePendingReports($admin)
    {
        // Get all batches for this admin's students
        $batches = Student::where('admin_id', $admin->id)
            ->distinct()
            ->pluck('intake');

        $pendingReports = [];
        $totalPending = 0;

        foreach ($batches as $batch) {
            $expectedReports = [];

            // Generate all expected report combinations
            for ($year = 1; $year <= 4; $year++) {
                for ($sem = 1; $sem <= 2; $sem++) {
                    $expectedReports[] = [
                        'sem' => $sem,
                        'year' => $year
                    ];
                }
            }

            // Get submitted reports for this batch
            $submittedReports = Meeting::where('admin_id', $admin->id)
                ->where('batch', $batch)
                ->get()
                ->map(function ($meeting) {
                    return [
                        'sem' => $meeting->sem,
                        'year' => $meeting->year
                    ];
                })
                ->toArray();

            // Find missing reports
            $missingReports = array_filter($expectedReports, function ($expected) use ($submittedReports) {
                return !in_array($expected, $submittedReports);
            });

            if (count($missingReports) > 0) {
                $pendingReports[] = [
                    'batch' => $batch,
                    'missing' => count($missingReports),
                    'details' => array_values($missingReports)
                ];
                $totalPending += count($missingReports);
            }
        }

        return [
            'total_pending_reports' => $totalPending,
            'pending_reports' => collect($pendingReports)
        ];
    }

    public function index()
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $user = auth()->user();
        $adminPose = null;
        $statistics = [];
        $students = collect();

        if ($user) {
            $admin = Admin::where('user_id', $user->id)->first();
            $adminPose = $admin ? $admin->pose : null;

            if ($admin) {
                $now = now();
                $weekEnd = now()->endOfWeek();

                // Calculate statistics
                $statistics = [
                    'total_mentees' => Student::where('admin_id', $admin->id)->count(),
                    'total_counseling' => Counseling::where('admin_id', $admin->id)->count(),
                    'upcoming_counseling' => Counseling::where('admin_id', $admin->id)
                        ->where('start_time', '>', $now)
                        ->where('status', '!=', 'cancelled')
                        ->with('student:id,name') // Add this line to eager load the student relationship
                        ->orderBy('start_time')
                        ->first(),
                    'this_week_counseling' => Counseling::where('admin_id', $admin->id)
                        ->where('start_time', '>', $now)
                        ->where('start_time', '<=', $weekEnd)
                        ->where('status', '!=', 'cancelled')
                        ->count()
                ];

                // Add pending reports statistics
                $pendingReports = $this->calculatePendingReports($admin);
                $statistics = array_merge($statistics, $pendingReports);
            }
        }

        $students = Student::query()
            ->where('admin_id', $admin->id)
            ->with(['user', 'kpiIndexes', 'enrolments'])
            ->select([
                'id',
                'matric_no',
                'name',
                'program',
                'faculty',
                'intake',
                'email',
                'phone',
                'img',
                'user_id',
                'admin_id',
                'created_at',
                'updated_at'
            ])
            ->withAvg('enrolments', 'pointer')
            ->withMax('kpiIndexes', 'cgpa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $upcomingMeetings = Counseling::where('admin_id', $admin->id)
            ->where('start_time', '>=', now())
            ->with('student:id,name,matric_no')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        return view('pages.admin.dashboard', compact('students', 'adminPose', 'statistics', 'upcomingMeetings'));
    }

    public function show(Student $student)
    {
        $student->load([
            'activities',
            'challenges',
            'kpiIndexes',
            'enrolments.course',
            'user'
        ]);

        $academicProgress = $student->enrolments()
            ->select('sem', 'year', DB::raw('AVG(pointer) as avg_pointer'))
            ->groupBy('sem', 'year')
            ->orderBy('year')
            ->orderBy('sem')
            ->get();

        $kpiProgress = $student->kpiIndexes()
            ->select(
                'sem',
                'year',
                'cgpa',
                DB::raw('(faculty_activity + university_activity + national_activity) as total_activities'),
                DB::raw('(faculty_competition + university_competition + national_competition) as total_competitions')
            )
            ->orderBy('year')
            ->orderBy('sem')
            ->get();

        return view('pages.admin.student-detail', compact('student', 'academicProgress', 'kpiProgress'));
    }
}

