<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function studentprofile()
    {
        // Check if a user is logged in
        if (!session()->has('userid')) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $userid = session('userid');

        // Fetch student details from the 'student' table
        $student = Student::where('userid', $userid)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        return view('studprofile', compact('student'));
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'studimg' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userid = session('userid');
        $student = Student::where('userid', $userid)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        if ($request->hasFile('studimg')) {
            // Delete old image if it exists
            if ($student->studimg) {
                Storage::disk('public')->delete($student->studimg);
            }

            // Store the new image in 'public/student_images'
            $path = $request->file('studimg')->store('student_images', 'public');

            // Update the database with the new image path
            $student->studimg = $path;
            $student->save();
        }

        return redirect()->back()->with('success', 'Profile image updated successfully.');
    }

    public function updateProfile(Request $request)
    {
        $userid = session('userid');
        $student = Student::where('userid', $userid)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        $student->update([
            'studintake' => $request->input('studintake'),
            'studphoneno' => $request->input('studphoneno'),
            'studstate' => $request->input('studstate'),
            'studaddress' => $request->input('studaddress'),
            'studmotto' => $request->input('studmotto'),
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
