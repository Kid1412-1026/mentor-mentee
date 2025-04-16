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
        // Get the authenticated user and their admin record
        $user = Auth::user();
        $admin = $user->admin;

        if (!$admin) {
            return view('pages.admin.mentor', [
                'students' => Student::paginate(0),
                'statistics' => [
                    'total_mentees' => 0,
                    'active_mentees' => 0,
                    'recent_activities' => collect()
                ]
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

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
}





