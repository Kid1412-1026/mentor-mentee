<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Activity;
use App\Models\Challenge;
use App\Models\KpiIndex;
use App\Models\Result;
use App\Models\Enrolment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Get the authenticated admin's ID
        $adminId = auth()->user()->admin->id;

        $students = Student::with([
            'activities' => function($query) {
                $query->select('id', 'student_id', 'sem', 'year', 'name', 'type', 'remark', 'uploads');
            },
            'challenges' => function($query) {
                $query->select('id', 'student_id', 'sem', 'year', 'name', 'type', 'remark');
            },
            'kpiIndexes' => function($query) {
                $query->select(
                    'id', 'student_id', 'cgpa', 'faculty_activity', 'university_activity',
                    'national_activity', 'international_activity', 'faculty_competition',
                    'university_competition', 'national_competition',
                    'international_competition', 'leadership', 'graduate_on_time',
                    'professional_certification', 'employability', 'mobility_program',
                    'sem', 'year'
                );
            },
            'enrolments.course' => function($query) {
                $query->select('id', 'code', 'name', 'credit_hour', 'section');
            },
            'user' => function($query) {
                $query->select('id', 'name', 'email', 'role');
            }
        ])
        ->where('admin_id', $adminId) // Add this line to filter by mentor
        ->select([
            'students.id',
            'students.matric_no',
            'students.name',
            'students.program',
            'students.email',
            'students.intake',
            'students.phone',
            'students.state',
            'students.address',
            'students.motto',
            'students.faculty',
            'students.img',
            'students.user_id',
            'students.admin_id',
            'students.created_at',
            'students.updated_at'
        ])
        ->when(request('search'), function($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('students.name', 'like', "%{$search}%")
                  ->orWhere('students.matric_no', 'like', "%{$search}%")
                  ->orWhere('students.program', 'like', "%{$search}%");
            });
        })
        ->when(request('faculty'), function($query, $faculty) {
            $query->where('students.faculty', $faculty);
        })
        ->when(request('intake'), function($query, $intake) {
            $query->where('students.intake', $intake);
        })
        ->withCount([
            'activities',
            'challenges',
            'enrolments',
            'kpiIndexes'
        ])
        ->withAvg('enrolments', 'pointer')
        ->withMax('kpiIndexes', 'cgpa')
        ->orderBy(request('sort', 'name'), request('direction', 'asc'))
        ->paginate(25)
        ->withQueryString();

        // Get unique faculties and intakes for filters (only for this mentor's students)
        $faculties = Student::where('admin_id', $adminId)->distinct()->pluck('faculty');
        $intakes = Student::where('admin_id', $adminId)->distinct()->pluck('intake');

        // Calculate statistics only for this mentor's students
        $statistics = [
            'total_students' => Student::where('admin_id', $adminId)->count(),
            'active_students' => Student::where('admin_id', $adminId)
                ->whereHas('user', function($query) {
                    $query->where('role', 'student');
                })->count(),
            'avg_cgpa' => KpiIndex::whereHas('student', function($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })->avg('cgpa'),
            'total_activities' => Activity::whereHas('student', function($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })->count(),
            'total_challenges' => Challenge::whereHas('student', function($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })->count()
        ];

        return view('pages.admin.report', compact('students', 'faculties', 'intakes', 'statistics'));
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

    public function export(Request $request, $id)
    {
        $student = Student::with([
            'activities',
            'challenges',
            'kpiIndexes',
            'enrolments.course',
            'user'
        ])->findOrFail($id);

        // Get academic progress data
        $academicProgress = $student->enrolments()
            ->select('sem', 'year', DB::raw('AVG(pointer) as avg_pointer'))
            ->groupBy('sem', 'year')
            ->orderBy('year')
            ->orderBy('sem')
            ->get();

        // Get KPI progress data
        $kpiProgress = $student->kpiIndexes()
            ->select(
                'sem',
                'year',
                'cgpa',
                DB::raw('(faculty_activity + university_activity + national_activity + international_activity) as total_activities'),
                DB::raw('(faculty_competition + university_competition + national_competition + international_competition) as total_competitions')
            )
            ->orderBy('year')
            ->orderBy('sem')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.student-report', [
            'student' => $student,
            'academicProgress' => $academicProgress,
            'kpiProgress' => $kpiProgress
        ]);

        $filename = 'student-report-' . $student->matric_no . '.pdf';

        return $pdf->stream($filename);
    }

    public function exportBatch(Request $request)
    {
        $ids = explode(',', $request->ids);
        $students = Student::with([
            'activities',
            'challenges',
            'kpiIndexes',
            'enrolments.course',
            'user'
        ])->whereIn('id', $ids)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.student-reports-batch', [
            'students' => $students
        ]);

        return $pdf->stream('student-reports-batch.pdf');
    }
}





