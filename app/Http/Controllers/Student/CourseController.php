<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Rule;
use App\Models\Programme;
use App\Models\Enrolment;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function viewcourse()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Get enrolled courses
        $enrolledCourses = Enrolment::with('course')
            ->where('student_id', $student->id)
            ->orderBy('year')
            ->orderBy('sem')
            ->get();

        // First get the programme ID based on the student's program name
        $programme = Programme::where('name', $student->program)->first();

        if (!$programme) {
            return view('pages.student.course', [
                'courses' => collect(),
                'enrolledCourses' => $enrolledCourses
            ]);
        }

        $courses = Rule::with(['course', 'programme'])
            ->where('programme_id', $programme->id)
            ->where('intake', $student->intake)
            ->get();

        return view('pages.student.course', compact('courses', 'enrolledCourses'));
    }

    public function enroll(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'semester_year' => 'required|regex:/^[1-2]\/[1-4]$/', // Format: "sem/year" (e.g., "1/1")
                'grade_pointer' => 'required|string|regex:/^[A-F][+-]?\/[0-4]\.[0-9]{2}$/', // Format: "grade/pointer" (e.g., "A/4.00")
                'rating' => 'required|integer|min:1|max:5',
            ]);

            $student = Auth::user()->student;

            if (!$student) {
                return redirect()->route('login')
                    ->with('error', 'Student profile not found.');
            }

            // Split semester_year into separate values
            [$sem, $year] = explode('/', $request->semester_year);

            // Split grade_pointer into separate values
            [$grade, $pointer] = explode('/', $request->grade_pointer);

            // Check if student is already enrolled in this course
            $existingEnrollment = Enrolment::where('student_id', $student->id)
                ->where('course_id', $request->course_id)
                ->first();

            if ($existingEnrollment) {
                return redirect()->route('student.course')
                    ->with('error', 'You are already enrolled in this course.');
            }

            // Begin transaction
            DB::beginTransaction();

            // Create new enrollment
            Enrolment::create([
                'student_id' => $student->id,
                'course_id' => $request->course_id,
                'sem' => $sem,
                'year' => $year,
                'pointer' => $pointer,
                'grade' => $grade,
                'rating' => $request->rating,
            ]);

            DB::commit();

            return redirect()->route('student.course')
                ->with('success', 'Successfully enrolled in the course.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Enrollment Error: ' . $e->getMessage());

            return redirect()->route('student.course')
                ->with('error', 'Failed to enroll in the course. Please try again.');
        }
    }

    public function updateEnrollment(Request $request, $id)
    {
        try {
            // Validate the request
            $request->validate([
                'semester_year' => 'required|regex:/^[1-2]\/[1-4]$/', // Format: "sem/year" (e.g., "1/1")
                'grade_pointer' => 'required|string|regex:/^[A-F][+-]?\/[0-4]\.[0-9]{2}$/', // Format: "grade/pointer" (e.g., "A/4.00")
                'rating' => 'required|integer|min:1|max:5',
            ]);

            $student = Auth::user()->student;

            if (!$student) {
                return redirect()->route('login')
                    ->with('error', 'Student profile not found.');
            }

            // Find the enrollment
            $enrollment = Enrolment::where('id', $id)
                ->where('student_id', $student->id)
                ->firstOrFail();

            // Split semester_year into separate values
            [$sem, $year] = explode('/', $request->semester_year);

            // Split grade_pointer into separate values
            [$grade, $pointer] = explode('/', $request->grade_pointer);

            // Begin transaction
            DB::beginTransaction();

            // Update enrollment
            $enrollment->update([
                'sem' => $sem,
                'year' => $year,
                'pointer' => $pointer,
                'grade' => $grade,
                'rating' => $request->rating,
            ]);

            DB::commit();

            return redirect()->route('student.course')
                ->with('success', 'Course enrollment updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Enrollment Update Error: ' . $e->getMessage());

            return redirect()->route('student.course')
                ->with('error', 'Failed to update enrollment. Please try again.');
        }
    }
}



