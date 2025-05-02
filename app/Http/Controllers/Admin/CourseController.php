<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Rule;

class CourseController extends Controller
{
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
            'faculty' => 'required|string|max:255',
        ]);

        Course::create([
            'code' => $request->code,
            'name' => $request->name,
            'credit_hour' => $request->credit_hour,
            'section' => $request->section,
            'faculty' => $request->faculty,
        ]);

        return redirect()->route('admin.course.index')->with([
            'alert' => [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Course added successfully!'
            ]
        ]);
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

        return redirect()->route('admin.course.index')->with([
            'alert' => [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Course updated successfully!'
            ]
        ]);
    }
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return redirect()->route('admin.course.index')->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Not Found!',
                    'message' => 'Course entry not found.'
                ]
            ]);
        }

        try {

            $course->delete();

            return redirect()->route('admin.course.index')->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Deleted!',
                    'message' => 'Course entry has been deleted successfully.'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.course.index')->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Failed to delete the course entry.'
                ]
            ]);
        }
    }

    public function admincourse()
    {
        $query = DB::table('courses');

        // Apply search filter (debounced)
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Apply section filter
        if ($section = request('section')) {
            $query->where('section', $section);
        }

        // Apply faculty filter
        if ($faculty = request('faculty')) {
            $query->where('faculty', $faculty);
        }

        $courses = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.course', compact('courses'));
    }
}








