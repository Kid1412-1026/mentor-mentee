<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Counseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MentorController extends Controller
{
    public function index()
    {
        return view('pages.student.mentor');
    }

    public function events()
    {
        try {
            $counselings = Counseling::where('student_id', Auth::user()->student->id)
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

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $now = now()->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();

        $request->validate([
            'start_time' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($now) {
                    $start = Carbon::parse($value)->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();
                    if ($start->lt($now)) {
                        $fail("The start time must be after or equal to the current time.");
                    }
                }
            ],
            'end_time' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $start = Carbon::parse($request->start_time)->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();
                    $end = Carbon::parse($value)->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();
                    if ($end->lte($start)) {
                        $fail('The end time must be after the start time.');
                    }
                }
            ],
            'duration' => 'required|integer|min:1',
            'venue' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $startTime = Carbon::parse($request->start_time)->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();
        $endTime = Carbon::parse($request->end_time)->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();

        // Check for overlapping sessions
        $conflict = Counseling::where(function ($query) use ($startTime, $endTime) {
            $query->where('student_id', Auth::id())
                ->orWhere('admin_id', Auth::user()->student->admin_id);
        })->where(function ($query) use ($startTime, $endTime) {
            $query->whereBetween('start_time', [$startTime, $endTime])
                ->orWhereBetween('end_time', [$startTime, $endTime])
                ->orWhere(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $endTime);
                });
        })->first();

        if ($conflict) {
            return back()->withInput()->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Scheduling Conflict',
                    'message' => 'The chosen time is not available.'
                ]
            ]);
        }

        Counseling::create([
            'student_id' => $student->id,
            'admin_id' => $student->admin_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => $request->duration,
            'venue' => $request->venue,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        return redirect()->route('student.mentor')->with([
            'alert' => [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Counseling session scheduled successfully.'
            ]
        ]);
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'pending' => '#FFA500',   // Orange
            'approved' => '#4F46E5',  // Indigo
            'completed' => '#10B981', // Green
            'cancelled' => '#EF4444', // Red
            default => '#6B7280'      // Gray
        };
    }

    public function getDetails($id)
    {
        try {
            $counseling = Counseling::where('id', $id)
                ->where('student_id', Auth::user()->student->id)
                ->firstOrFail();

            return response()->json($counseling);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch counseling details'], 404);
        }
    }

    public function destroy($id)
    {
        $student = Auth::user()->student;

        try {
            // Find the counseling session and ensure it belongs to the current student
            $counseling = Counseling::where('id', $id)
                ->where('student_id', $student->id)
                ->firstOrFail();

            // Only allow deletion of pending sessions
            if ($counseling->status !== 'pending') {
                return redirect()->route('student.mentor')->with([
                    'alert' => [
                        'type' => 'error',
                        'title' => 'Action Denied',
                        'message' => 'You can only delete pending counseling sessions.'
                    ]
                ]);
            }

            // Delete the counseling session
            $counseling->delete();

            return redirect()->route('student.mentor')->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Deleted!',
                    'message' => 'Counseling session has been deleted successfully.'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.mentor')->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Failed to delete the counseling session.'
                ]
            ]);
        }
    }
}





















