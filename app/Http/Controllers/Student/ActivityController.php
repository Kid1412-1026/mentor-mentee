<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $activities = Activity::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function ($activity) {
                $activity->created_at = Carbon::parse($activity->created_at);
                $activity->updated_at = Carbon::parse($activity->updated_at);
                return $activity;
            });

        return view('pages.student.activity', compact('activities'));
    }

    public function edit($id)
    {
        $student = Auth::user()->student;
        $activity = Activity::where('id', $id)
            ->where('student_id', $student->id)
            ->first();

        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        return response()->json([
            'id' => $activity->id,
            'sem' => $activity->sem,
            'year' => $activity->year,
            'name' => $activity->name,
            'type' => $activity->type,
            'remark' => $activity->remark,
            'uploads' => $activity->uploads
        ]);
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $request->validate([
            'activities' => 'required|array',
            'activities.*.sem_year' => 'required|string',
            'activities.*.name' => 'required|string|max:255',
            'activities.*.type' => 'required|string|max:255',
            'activities.*.remark' => 'nullable|string|max:500',
            'activities.*.uploads' => 'nullable|file|max:2048' // 2MB max
        ]);

        foreach ($request->activities as $activityData) {
            list($sem, $year) = explode('/', $activityData['sem_year']);

            $data = [
                'student_id' => $student->id,
                'sem' => $sem,
                'year' => $year,
                'name' => $activityData['name'],
                'type' => $activityData['type'],
                'remark' => $activityData['remark'] ?? null
            ];

            if (isset($activityData['uploads']) && $activityData['uploads']->isValid()) {
                $path = $activityData['uploads']->store('activities', 'public');
                $data['uploads'] = $path;
            }

            Activity::create($data);
        }

        return redirect()->route('student.activity')->with([
            'alert' => [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Activities added successfully!'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sem_year' => 'required|string',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'remark' => 'nullable|string|max:500',
            'uploads' => 'nullable|file|max:2048'
        ]);

        $student = Auth::user()->student;
        $activity = Activity::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        list($sem, $year) = explode('/', $request->sem_year);

        if ($request->hasFile('uploads')) {
            if ($activity->uploads) {
                Storage::disk('public')->delete($activity->uploads);
            }
            $path = $request->file('uploads')->store('activities', 'public');
            $activity->uploads = $path;
        }

        $activity->update([
            'sem' => $sem,
            'year' => $year,
            'name' => $request->name,
            'type' => $request->type,
            'remark' => $request->remark,
        ]);

        return redirect()->route('student.activity')->with([
            'alert' => [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Activities updated successfully!'
            ]
        ]);
    }

    // Inside your controller

    public function destroy($id)
    {
        $student = Auth::user()->student;
        $activity = Activity::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        try {
            $activity->delete();

            // Flash a success notification
            return redirect()->route('student.activity')->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Deleted!',
                    'message' => 'Activity has been deleted successfully.'
                ]
            ]);
        } catch (\Exception $e) {
            // Flash an error notification
            return redirect()->route('student.activity')->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Failed to delete the activity.'
                ]
            ]);
        }
    }

}



















