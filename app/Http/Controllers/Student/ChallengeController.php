<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Challenge;

class ChallengeController extends Controller
{
    public function studchallenge()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $challenges = Challenge::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function($challenge) {
                $challenge->created_at = Carbon::parse($challenge->created_at);
                $challenge->updated_at = Carbon::parse($challenge->updated_at);
                return $challenge;
            });

        return view('pages.student.challenge', compact('challenges'));
    }

    public function edit($id)
    {
        $student = Auth::user()->student;
        $challenge = Challenge::where('id', $id)
            ->where('student_id', $student->id)
            ->first();

        if (!$challenge) {
            return response()->json(['error' => 'Challenge not found'], 404);
        }

        return response()->json([
            'id' => $challenge->id,
            'sem' => $challenge->sem,
            'year' => $challenge->year,
            'name' => $challenge->name,
            'type' => $challenge->type,
            'remark' => $challenge->remark
        ]);
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $request->validate([
            'sem_year' => 'required|string',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'remark' => 'nullable|string|max:500'
        ]);

        list($sem, $year) = explode('/', $request->sem_year);

        $data = [
            'student_id' => $student->id,
            'sem' => $sem,
            'year' => $year,
            'name' => $request->name,
            'type' => $request->type,
            'remark' => $request->remark
        ];

        Challenge::create($data);

        return redirect()->route('student.challenge')->with('success', 'Challenge created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sem_year' => 'required|string',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'remark' => 'nullable|string|max:500'
        ]);

        $student = Auth::user()->student;
        $challenge = Challenge::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        list($sem, $year) = explode('/', $request->sem_year);

        $challenge->update([
            'sem' => $sem,
            'year' => $year,
            'name' => $request->name,
            'type' => $request->type,
            'remark' => $request->remark,
        ]);

        return redirect()->route('student.challenge')->with('success', 'Challenge updated successfully');
    }

    public function destroy($id)
    {
        $student = Auth::user()->student;
        $challenge = Challenge::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $challenge->delete();

        return response()->json(['success' => true]);
    }
}


