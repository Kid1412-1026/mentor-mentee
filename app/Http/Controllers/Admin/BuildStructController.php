<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Programme;
use App\Models\CourseStructure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BuildStructController extends Controller
{
    public function viewStruct()
    {
        // Fetch all programmes
        $programmes = Programme::select('id', 'code', 'name')->get();

        // Fetch all courses for the dropdown with all necessary fields
        $courses = Course::select('id', 'code', 'name', 'credit_hour', 'section', 'faculty')->get();

        // Fetch unique intakes
        $intakes = DB::table('students')
            ->select('intake')
            ->distinct()
            ->whereNotNull('intake')
            ->orderBy('intake', 'desc')
            ->pluck('intake');

        // Fetch course structure rules with their related courses
        $rules = Rule::with(['course' => function($query) {
            $query->select('id', 'code', 'name', 'credit_hour', 'section', 'faculty');
        }])->get();

        return view('pages.admin.buildcoursestruct', compact('programmes', 'intakes', 'rules', 'courses'));
    }

    public function getStructure(Request $request)
    {
        $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'intake' => 'required'
        ]);

        $rules = Rule::with(['course' => function($query) {
            $query->select('id', 'code', 'name', 'credit_hour', 'section', 'faculty');
        }])
        ->where('programme_id', $request->programme_id)
        ->where('intake', $request->intake)
        ->get();

        return response()->json($rules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
            'intake' => 'required'
        ]);

        $successCount = 0;
        $errorCount = 0;

        foreach ($request->course_ids as $courseId) {
            // Check if the rule already exists
            $existingRule = Rule::where([
                'programme_id' => $request->programme_id,
                'course_id' => $courseId,
                'intake' => $request->intake
            ])->first();

            if (!$existingRule) {
                // Create new rule
                Rule::create([
                    'programme_id' => $request->programme_id,
                    'course_id' => $courseId,
                    'intake' => $request->intake
                ]);
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        $message = "$successCount courses added to structure successfully.";
        if ($errorCount > 0) {
            $message .= " $errorCount courses were skipped as they already exist in the structure.";
        }

        return redirect()->back()->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'course_id' => 'required|exists:courses,id',
            'intake' => 'required'
        ]);

        $rule = Rule::findOrFail($id);

        $rule->update([
            'programme_id' => $request->programme_id,
            'course_id' => $request->course_id,
            'intake' => $request->intake
        ]);

        return redirect()->back()
            ->with('success', 'Course structure updated successfully');
    }

    public function destroy($id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete();

        return redirect()->back()
            ->with('success', 'Course removed from structure successfully');
    }

    public function exportBatch(Request $request)
    {
        $intakes = explode(',', $request->intakes);

        $courseStructures = [];
        foreach ($intakes as $intake) {
            $rules = Rule::where('intake', $intake)
                ->with(['course' => function($query) {
                    $query->select('id', 'code', 'name', 'credit_hour', 'section', 'faculty');
                }])
                ->get()
                ->groupBy('course.section');

            $courseStructures[$intake] = $rules;
        }

        $sections = [
            'Faculty Core',
            'Programme Core',
            'Elective',
            'University Core',
            'Co-curriculum',
            'Language',
            'Industrial Training'
        ];

        $pdf = Pdf::loadView('pdfs.course-structure-batch', [
            'courseStructures' => $courseStructures,
            'sections' => $sections
        ]);

        return $pdf->stream('course-structure-report.pdf');
    }
}

