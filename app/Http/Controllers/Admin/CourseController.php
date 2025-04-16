<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Course;

class CourseController extends Controller
{
    public function admincourse()
    {
        $courses = Course::orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function($course) {
                $course->created_at = Carbon::parse($course->created_at);
                $course->updated_at = Carbon::parse($course->updated_at);
                return $course;
            });

        return view('pages.admin.course', compact('courses'));
    }

    public function edit($id)
    {
        $course = Course::where('id', $id)->first();

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json([
            'id' => $course->id,
            'code' => $course->code,
            'name' => $course->name,
            'credit_hour' => $course->credit_hour,
            'section' => $course->section,
            'faculty' => $course->faculty,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:courses,code',
            'name' => 'required|string|max:255',
            'credit_hour' => 'required|integer|min:0',
            'section' => 'required|in:Faculty Core,Programme Core,Elective,University Core,Co-curriculum,Language,Industrial Training',
            'faculty' => 'nullable|string|max:255',
        ]);

        $data = [
            'code' => $request->code,
            'name' => $request->name,
            'credit_hour' => $request->credit_hour,
            'section' => $request->section,
            'faculty' => $request->faculty,
            'created_at' => now(),
            'updated_at' => now()
        ];

        Course::create($data);

        return redirect()->route('admin.course.index')->with('success', 'Course created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|unique:courses,code,' . $id,
            'name' => 'required|string|max:255',
            'credit_hour' => 'required|integer|min:0',
            'section' => 'required|in:Faculty Core,Programme Core,Elective,University Core,Co-curriculum,Language,Industrial Training',
            'faculty' => 'nullable|string|max:255',
        ]);

        $data = [
            'code' => $request->code,
            'name' => $request->name,
            'credit_hour' => $request->credit_hour,
            'section' => $request->section,
            'faculty' => $request->faculty,
            'updated_at' => now()
        ];

        Course::where('id', $id)->update($data);

        return redirect()->route('admin.course.index')->with('success', 'Course updated successfully');
    }

    public function destroy($id)
    {
        Course::where('id', $id)->delete();

        return response()->json(['success' => true]);
    }
}








