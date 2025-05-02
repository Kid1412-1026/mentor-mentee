<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Models\Counseling;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $admin = $user->admin;

        if (!$admin) {
            // Log the issue for debugging
            \Log::warning('No admin record found for user:', ['user_id' => $user->id]);

            return view('pages.admin.mentor', [
                'students' => Student::paginate(0),
                'statistics' => [
                    'total_mentees' => 0,
                    'active_mentees' => 0,
                    'recent_activities' => collect()
                ],
                'error' => 'Admin profile not found. Please contact system administrator.'
            ]);
        }

        // Get students under this admin
        $students = Student::query()
            ->where('admin_id', $admin->id)
            ->with(['user', 'activities', 'challenges'])
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
                'admin_id'
            ])
            ->orderBy('name')
            ->paginate(10);

        // Get quick statistics
        $statistics = [
            'total_mentees' => $students->total(),
            'active_mentees' => $students->where('user.active', true)->count(),
            'recent_activities' => collect()
        ];

        // Get recent activities if there are students
        if ($students->isNotEmpty()) {
            $statistics['recent_activities'] = Activity::whereIn('student_id', $students->pluck('id'))
                ->latest()
                ->take(5)
                ->get();
        }

        return view('pages.admin.mentor', compact('students', 'statistics'));
    }

    public function meetingreport()
    {
        return view('pages.admin.meeting-report');
    }

    public function events()
    {
        try {
            $counselings = Counseling::where('admin_id', Auth::id())
                ->with('student:id,name')
                ->get()
                ->map(function ($counseling) {
                    return [
                        'id' => $counseling->id,
                        'title' => $counseling->description ?? 'Counseling Session',
                        'start' => $counseling->start_time,
                        'end' => $counseling->end_time,
                        'backgroundColor' => $this->getStatusColor($counseling->status),
                        'borderColor' => $this->getStatusColor($counseling->status),
                        'extendedProps' => [
                            'student_name' => $counseling->student->name,
                            'venue' => $counseling->venue,
                            'duration' => $counseling->duration,
                            'status' => $counseling->status
                        ]
                    ];
                });

            return response()->json($counselings);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch events'], 500);
        }
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'pending' => '#FFA500',   // Orange
            'approved' => '#4F46E5',  // Indigo
            'completed' => '#10B981', // Green
            'rejected' => '#EF4444',  // Red
            default => '#6B7280'      // Gray
        };
    }

    public function updateStatus($id, Request $request)
    {
        try {
            $counseling = Counseling::where('admin_id', Auth::id())
                ->findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,approved,completed,rejected'
            ]);

            $counseling->status = $request->status;
            $counseling->save();

            return redirect()->back()->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Status updated successfully.'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Failed to update status.'
                ]
            ]);
        }
    }

    public function assignMentor()
    {
        // Get the authenticated user and their admin record
        $user = Auth::user();
        $admin = $user->admin;

        // Get all admins to be used as mentors
        $mentors = \App\Models\Admin::with('user')
            ->select(['id', 'user_id', 'phone', 'faculty', 'pose'])
            ->get()
            ->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->user->name
                ];
            });

        // Get unassigned students
        $unassignedStudents = Student::whereNull('admin_id')
            ->with(['user'])
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
                'admin_id'
            ])
            ->orderBy('name')
            ->paginate(10);

        return view('pages.admin.assign-mentor', [
            'unassignedStudents' => $unassignedStudents,
            'mentors' => $mentors
        ]);
    }

    public function assignMentorToStudent(Student $student)
    {
        try {
            $admin = Auth::user()->admin;

            if (!$admin) {
                return redirect()->back()->with([
                    'alert' => [
                        'type' => 'error',
                        'title' => 'Unauthorized',
                        'message' => 'You are not authorized to assign mentees.'
                    ]
                ]);
            }

            $student->admin_id = $admin->id;
            $student->save();

            return redirect()->back()->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Student successfully assigned as mentee.'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Failed to assign student as mentee.'
                ]
            ]);
        }
    }


    public function assignMentorBulk(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:students,id',
                'mentor_id' => 'required|exists:admins,id'
            ]);

            $studentIds = $validated['student_ids'];
            $mentorId = $validated['mentor_id'];

            // Update all selected students
            Student::whereIn('id', $studentIds)
                ->whereNull('admin_id')
                ->update(['admin_id' => $mentorId]);

            return redirect()->back()->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Students successfully assigned to mentor.'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Failed to assign students to mentor.'
                ]
            ]);
        }
    }
}







