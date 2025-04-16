<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Counseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class MentorController extends Controller
{
    public function index()
    {
        return view('pages.student.mentor');
    }

    public function events()
    {
        try {
            $counselings = Counseling::where('student_id', Auth::id())
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
        try {
            // Get current time in Malaysia timezone
            $now = now()->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();

            // Parse the input times and set them to Malaysia timezone
            $startTime = Carbon::parse($request->start_time)->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();
            $endTime = Carbon::parse($request->end_time)->setTimezone('Asia/Kuala_Lumpur')->startOfMinute();

            // Debug logging
            \Log::info('Time comparison:', [
                'now' => $now->toDateTimeString(),
                'start_time' => $startTime->toDateTimeString(),
                'end_time' => $endTime->toDateTimeString(),
                'is_start_after_now' => $startTime->gte($now)
            ]);

            $validated = $request->validate([
                'start_time' => ['required', 'date', function ($attribute, $value, $fail) use ($now, $startTime) {
                    if ($startTime->lt($now)) {
                        $fail("The start time ({$startTime->toDateTimeString()}) must be after or equal to current time ({$now->toDateTimeString()}).");
                    }
                }],
                'end_time' => ['required', 'date', function ($attribute, $value, $fail) use ($startTime, $endTime) {
                    if ($endTime->lte($startTime)) {
                        $fail('The end time must be after the start time.');
                    }
                }],
                'duration' => 'required|integer|min:1',
                'venue' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            // Get the authenticated student's mentor (admin)
            $student = Auth::user()->student;
            if (!$student || !$student->admin_id) {
                return response()->json([
                    'error' => 'No mentor assigned. Please contact your administrator.'
                ], 422);
            }

            // Check for existing counseling sessions that overlap
            $existingSession = Counseling::where(function($query) use ($validated, $student) {
                $query->where('student_id', Auth::id())
                    ->where(function($q) use ($validated) {
                        $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                            ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                            ->orWhere(function($q) use ($validated) {
                                $q->where('start_time', '<=', $validated['start_time'])
                                    ->where('end_time', '>=', $validated['end_time']);
                            });
                    });
            })->orWhere(function($query) use ($validated, $student) {
                $query->where('admin_id', $student->admin_id)
                    ->where(function($q) use ($validated) {
                        $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                            ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                            ->orWhere(function($q) use ($validated) {
                                $q->where('start_time', '<=', $validated['start_time'])
                                    ->where('end_time', '>=', $validated['end_time']);
                            });
                    });
            })->first();

            if ($existingSession) {
                $message = $existingSession->student_id == Auth::id()
                    ? 'You already have a counseling session scheduled during this time'
                    : 'Your mentor is not available during this time slot';

                return response()->json([
                    'error' => $message
                ], 422);
            }

            // Create the counseling session with explicit data
            $counselingData = [
                'student_id' => Auth::id(),
                'admin_id' => $student->admin_id,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'duration' => $validated['duration'],
                'venue' => $validated['venue'],
                'description' => $validated['description'] ?? null,
                'status' => 'pending'
            ];

            $counseling = Counseling::create($counselingData);

            if (!$counseling) {
                throw new \Exception('Failed to create counseling session');
            }

            // Make sure we're using the correct route name and it exists
            return response()->json([
                'success' => true,
                'message' => 'Counseling session scheduled successfully',
                'redirect' => route('student.mentor')
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Counseling session creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to schedule counseling session',
                'debug' => $e->getMessage()
            ], 500);
        }
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
}













