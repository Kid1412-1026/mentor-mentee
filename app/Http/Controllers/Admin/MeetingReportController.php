<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use App\Models\Student;

class MeetingReportController extends Controller
{
    public function index()
    {
        // Get the authenticated admin's ID
        $adminId = Auth::id();

        // Fetch meetings for the current admin
        $meetings = Meeting::where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.admin.meeting-report', [
            'meetings' => $meetings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sem_year' => 'required|string',
            'batch' => 'required|string|max:9',
            'session_date' => 'required|date',
            'method' => 'required|in:face-to-face,online',
            'duration' => 'required|integer|min:1',
            'agenda' => 'required|string',
            'discussion' => 'required|string',
            'action' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        // Split sem_year into sem and year
        [$sem, $year] = explode('/', $validated['sem_year']);

        // Check if a record already exists for this sem/year/batch combination
        $existingMeeting = Meeting::where('sem', $sem)
            ->where('year', $year)
            ->where('batch', $validated['batch'])
            ->first();

        if ($existingMeeting) {
            return redirect()->back()
                ->withInput()
                ->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Duplicate found!',
                    'message' => 'A meeting report already exists for this semester, year and batch combination.'
                ]
            ]);
        }

        // Create new meeting record
        Meeting::create([
            'sem' => $sem,
            'year' => $year,
            'batch' => $validated['batch'],
            'session_date' => $validated['session_date'],
            'method' => $validated['method'],
            'duration' => $validated['duration'],
            'agenda' => $validated['agenda'],
            'discussion' => $validated['discussion'],
            'action' => $validated['action'],
            'remarks' => $validated['remarks'],
            'admin_id' => Auth::id(),
        ]);

        return redirect()->route('admin.meetingreport.index')
            ->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Meeting report added successfully!'
                ]
            ]);
    }

    public function export(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        $students = Student::where('intake', $meeting->batch)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.meeting-report', [
            'meeting' => $meeting,
            'students' => $students
        ]);

        $filename = 'meeting-report-' . $meeting->session_date->format('Y-m-d') . '.pdf';

        return $pdf->stream($filename);
    }

    public function exportBatch(Request $request)
    {
        $ids = explode(',', $request->ids);
        $meetings = Meeting::whereIn('id', $ids)->get();

        // Create a new PDF instance with multiple pages
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.meeting-reports-batch', [
            'meetings' => $meetings
        ]);

        return $pdf->stream('meeting-reports-batch.pdf');
    }
}

