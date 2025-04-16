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

class BuildStructController extends Controller
{
    public function viewStruct()
    {
        // Fetch all programmes
        $programmes = Programme::select('id', 'code', 'name')->get();

        // Fetch all courses for the dropdown
        $courses = Course::select('id', 'code', 'name')->get();

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
            'course_id' => 'required|exists:courses,id',
            'intake' => 'required'
        ]);

        // Check if the rule already exists
        $existingRule = Rule::where([
            'programme_id' => $request->programme_id,
            'course_id' => $request->course_id,
            'intake' => $request->intake
        ])->first();

        if ($existingRule) {
            return redirect()->back()
                ->with('error', 'This course is already in the structure for this programme and intake');
        }

        // Create new rule
        Rule::create([
            'programme_id' => $request->programme_id,
            'course_id' => $request->course_id,
            'intake' => $request->intake
        ]);

        return redirect()->back()
            ->with('success', 'Course added to structure successfully');
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
}



