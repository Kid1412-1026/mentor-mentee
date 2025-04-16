<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KpiGoal;
use Illuminate\Http\Request;

class KPIGoalController extends Controller
{
    public function index()
    {
        $kpiGoals = KpiGoal::all();
        return view('pages.admin.kpi-goal', compact('kpiGoals'));
    }

    public function edit($id)
    {
        $kpiGoal = KpiGoal::where('id', $id)->first();

        if (!$kpiGoal) {
            return response()->json(['error' => 'KPI Goal not found'], 404);
        }

        return response()->json([
            'id' => $kpiGoal->id,
            'cgpa' => $kpiGoal->cgpa,
            'faculty_activity' => $kpiGoal->faculty_activity,
            'university_activity' => $kpiGoal->university_activity,
            'national_activity' => $kpiGoal->national_activity,
            'international_activity' => $kpiGoal->international_activity,
            'faculty_competition' => $kpiGoal->faculty_competition,
            'university_competition' => $kpiGoal->university_competition,
            'national_competition' => $kpiGoal->national_competition,
            'international_competition' => $kpiGoal->international_competition,
            'leadership' => $kpiGoal->leadership,
            'graduate_on_time' => $kpiGoal->graduate_on_time,
            'professional_certification' => $kpiGoal->professional_certification,
            'employability' => $kpiGoal->employability,
            'mobility_program' => $kpiGoal->mobility_program
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cgpa' => 'required|numeric|min:0|max:4',
            'faculty_activity' => 'required|integer|min:0',
            'university_activity' => 'required|integer|min:0',
            'national_activity' => 'required|integer|min:0',
            'international_activity' => 'required|integer|min:0',
            'faculty_competition' => 'required|integer|min:0',
            'university_competition' => 'required|integer|min:0',
            'national_competition' => 'required|integer|min:0',
            'international_competition' => 'required|integer|min:0',
            'leadership' => 'required|integer|min:0',
            'graduate_on_time' => 'required|string|max:255',
            'professional_certification' => 'required|integer|min:0',
            'employability' => 'required|string|max:255',
            'mobility_program' => 'required|integer|min:0'
        ]);

        $kpiGoal = KpiGoal::findOrFail($id);

        $kpiGoal->update($request->all());

        return redirect()->route('admin.kpigoal')
            ->with('success', 'KPI Goal updated successfully');
    }
}




