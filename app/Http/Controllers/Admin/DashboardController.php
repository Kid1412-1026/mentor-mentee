<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $admin = Auth::user()->admin;

        // If no admin profile exists, return empty paginator
        if (!$admin) {
            return view('pages.admin.dashboard', [
                'students' => Student::paginate(0)
            ]);
        }

        // Get paginated students data
        $students = Student::query()
            ->where('admin_id', $admin->id)
            ->with(['user', 'activities', 'challenges', 'kpiIndexes', 'enrolments'])
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
                'admin_id',
                'created_at',
                'updated_at'
            ])
            ->withCount(['activities', 'challenges', 'enrolments'])
            ->withAvg('enrolments', 'pointer')
            ->withMax('kpiIndexes', 'cgpa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.admin.dashboard', compact('students'));
    }
}



