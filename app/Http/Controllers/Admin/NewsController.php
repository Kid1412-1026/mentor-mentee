<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $announcements = DB::table('announcements')
            ->orderBy('created_at', 'desc')
            ->paginate(10)  // Add pagination with 10 items per page
            ->through(function ($announcement) {
                $announcement->created_at = Carbon::parse($announcement->created_at);
                $announcement->updated_at = Carbon::parse($announcement->updated_at);
                return $announcement;
            });

        return view('pages.admin.news', compact('announcements'));
    }

    public function edit($id)
    {
        $announcement = DB::table('announcements')
            ->where('id', $id)
            ->first();

        if (!$announcement) {
            return response()->json(['error' => 'Announcement not found'], 404);
        }

        return response()->json([
            'id' => $announcement->id,
            'title' => $announcement->title,
            'description' => $announcement->description,
            'file' => $announcement->file
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|max:10240' // 10MB max
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now()
        ];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('announcements', 'public');
            $data['file'] = $path;
        }

        DB::table('announcements')->insert($data);

        return redirect()->route('admin.news')->with([
            'alert' => [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Announcement added successfully!'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|max:10240' // 10MB max
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'updated_at' => now()
        ];

        if ($request->hasFile('file')) {
            // Delete old file if exists
            $announcement = DB::table('announcements')->where('id', $id)->first();
            if ($announcement && $announcement->file) {
                Storage::disk('public')->delete($announcement->file);
            }

            $path = $request->file('file')->store('announcements', 'public');
            $data['file'] = $path;
        }

        DB::table('announcements')->where('id', $id)->update($data);

        return redirect()->route('admin.news')->with([
            'alert' => [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Announcement updated successfully!'
            ]
        ]);
    }

    public function destroy($id)
    {
        $announcement = DB::table('announcements')->where('id', $id)->first();

        if (!$announcement) {
            return redirect()->route('admin.news')->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Not Found!',
                    'message' => 'Announcement not found.'
                ]
            ]);
        }

        try {
            if ($announcement->file) {
                Storage::disk('public')->delete($announcement->file);
            }

            DB::table('announcements')->where('id', $id)->delete();

            return redirect()->route('admin.news')->with([
                'alert' => [
                    'type' => 'success',
                    'title' => 'Deleted!',
                    'message' => 'Announcement has been deleted successfully.'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.news')->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Failed to delete the announcement.'
                ]
            ]);
        }
    }
}
