<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KpiIndex;
use App\Models\KpiGoal;
use App\Models\Activity;
use App\Models\Challenge;
use App\Models\Enrolment;
use Illuminate\Support\Facades\DB;

class KPIController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Get the KPI goals
        $kpiGoal = KpiGoal::first();

        // Get all activities for this student
        $activities = Activity::where('student_id', $student->id)
            ->select('sem', 'year', 'type', DB::raw('count(*) as count'))
            ->groupBy('sem', 'year', 'type')
            ->get();

        // Get all challenges for this student
        $challenges = Challenge::where('student_id', $student->id)
            ->select('sem', 'year', 'type', DB::raw('count(*) as count'))
            ->groupBy('sem', 'year', 'type')
            ->get();

        // Get academic performance (CGPA) by semester
        $enrolments = Enrolment::where('student_id', $student->id)
            ->select('sem', 'year', DB::raw('AVG(pointer) as cgpa'))
            ->groupBy('sem', 'year')
            ->get();

        // Process all data and update KPI indexes
        $years = collect([
            ...$activities->pluck('year'),
            ...$challenges->pluck('year'),
            ...$enrolments->pluck('year')
        ])->unique();

        foreach ($years as $year) {
            $semesters = collect([
                ...$activities->where('year', $year)->pluck('sem'),
                ...$challenges->where('year', $year)->pluck('sem'),
                ...$enrolments->where('year', $year)->pluck('sem')
            ])->unique();

            foreach ($semesters as $sem) {
                // Initialize counts
                $kpiData = [
                    'faculty_activity' => 0,
                    'university_activity' => 0,
                    'national_activity' => 0,
                    'international_activity' => 0,
                    'faculty_competition' => 0,
                    'university_competition' => 0,
                    'national_competition' => 0,
                    'international_competition' => 0,
                    'leadership' => 0,
                    'professional_certification' => 0,
                    'mobility_program' => 0,
                    'cgpa' => 0
                ];

                // Count activities by type
                $semActivities = $activities->where('year', $year)->where('sem', $sem);
                foreach ($semActivities as $activity) {
                    switch ($activity->type) {
                        case 'Faculty Activity':
                            $kpiData['faculty_activity'] += $activity->count;
                            break;
                        case 'University Activity':
                            $kpiData['university_activity'] += $activity->count;
                            break;
                        case 'National Activity':
                            $kpiData['national_activity'] += $activity->count;
                            break;
                        case 'International Activity':
                            $kpiData['international_activity'] += $activity->count;
                            break;
                        case 'Faculty Competition':
                            $kpiData['faculty_competition'] += $activity->count;
                            break;
                        case 'University Competition':
                            $kpiData['university_competition'] += $activity->count;
                            break;
                        case 'National Competition':
                            $kpiData['national_competition'] += $activity->count;
                            break;
                        case 'International Competition':
                            $kpiData['international_competition'] += $activity->count;
                            break;
                        case 'Leadership Program':
                            $kpiData['leadership'] += $activity->count;
                            break;
                        case 'Professional Certification':
                            $kpiData['professional_certification'] += $activity->count;
                            break;
                        case 'Mobility Program':
                            $kpiData['mobility_program'] += $activity->count;
                            break;
                    }
                }

                // Count challenges by type
                $semChallenges = $challenges->where('year', $year)->where('sem', $sem);
                foreach ($semChallenges as $challenge) {
                    switch ($challenge->type) {
                        case 'faculty':
                            $kpiData['faculty_competition'] = $challenge->count;
                            break;
                        case 'university':
                            $kpiData['university_competition'] = $challenge->count;
                            break;
                        case 'national':
                            $kpiData['national_competition'] = $challenge->count;
                            break;
                        case 'international':
                            $kpiData['international_competition'] = $challenge->count;
                            break;
                    }
                }

                // Get CGPA for this semester
                $semEnrolment = $enrolments->where('year', $year)->where('sem', $sem)->first();
                if ($semEnrolment) {
                    $kpiData['cgpa'] = $semEnrolment->cgpa;
                }

                // Update or create KPI index
                KpiIndex::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'sem' => $sem,
                        'year' => $year
                    ],
                    $kpiData
                );
            }
        }

        // Get updated KPI records
        $kpiIndexes = KpiIndex::where('student_id', $student->id)
            ->orderBy('year', 'desc')
            ->orderBy('sem', 'desc')
            ->get();

        // Calculate overall statistics
        $statistics = [
            'latest_cgpa' => $kpiIndexes->first()?->cgpa ?? 0,

            // Activities breakdown
            'faculty_activities' => $kpiIndexes->sum('faculty_activity'),
            'university_activities' => $kpiIndexes->sum('university_activity'),
            'national_activities' => $kpiIndexes->sum('national_activity'),
            'international_activities' => $kpiIndexes->sum('international_activity'),

            // Competitions breakdown
            'faculty_competitions' => $kpiIndexes->sum('faculty_competition'),
            'university_competitions' => $kpiIndexes->sum('university_competition'),
            'national_competitions' => $kpiIndexes->sum('national_competition'),
            'international_competitions' => $kpiIndexes->sum('international_competition'),

            // Other metrics
            'leadership_roles' => $kpiIndexes->sum('leadership'),
            'certifications' => $kpiIndexes->sum('professional_certification'),
            'mobility_programs' => $kpiIndexes->sum('mobility_program'),

            // Totals
            'total_activities' => $kpiIndexes->sum(function($kpi) {
                return $kpi->faculty_activity +
                       $kpi->university_activity +
                       $kpi->national_activity +
                       $kpi->international_activity;
            }),
            'total_competitions' => $kpiIndexes->sum(function($kpi) {
                return $kpi->faculty_competition +
                       $kpi->university_competition +
                       $kpi->national_competition +
                       $kpi->international_competition;
            })
        ];

        // Debug information
        \Log::info('KPI Data:', [
            'student_id' => $student->id,
            'kpi_records' => $kpiIndexes->toArray(),
            'statistics' => $statistics
        ]);

        return view('pages.student.kpi', compact('kpiIndexes', 'kpiGoal', 'statistics'));
    }
}







