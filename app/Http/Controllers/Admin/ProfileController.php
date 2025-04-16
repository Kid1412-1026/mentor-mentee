<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function adminprofile()
{
    // Check if a user is logged in
    if (!session()->has('userid')) {
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    $userid = session('userid');

    // Fetch admin details from the 'admin' table
    $admin = Admin::where('userid', $userid)->first();

    if (!$admin) {
        return redirect()->back()->with('error', 'Admin profile not found.');
    }

    return view('adminprofile', compact('admin'));
}

public function updateProfileImage(Request $request)
{
    $request->validate([
        'adminimg' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $userid = session('userid');
    $admin = Admin::where('userid', $userid)->first();

    if ($request->hasFile('adminimg')) {
        // Delete old image if it exists
        if ($admin->adminimg) {
            Storage::disk('public')->delete($admin->adminimg);
        }

        // Store the new image in 'public/admin_images'
        $path = $request->file('adminimg')->store('admin_images', 'public');

        // Update the database with the new image path
        $admin->adminimg = $path;
        $admin->save();
    }

    return redirect()->back()->with('success', 'Profile image updated successfully.');
}

public function updateProfile(Request $request)
{
    $userid = session('userid');
    $admin = Admin::where('userid', $userid)->first();

    if (!$admin) {
        return redirect()->back()->with('error', 'Admin profile not found.');
    }

    $admin->update([
        'adminpose' => $request->input('adminpose'),
        'adminphoneno' => $request->input('adminphoneno'),
        'adminname' => $request->input('adminname'),
    ]);

    return redirect()->back()->with('success', 'Profile updated successfully.');
}

}
