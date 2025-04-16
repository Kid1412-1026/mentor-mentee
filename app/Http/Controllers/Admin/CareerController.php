<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CareerController extends Controller
{
    public function index()
    {
        $careers = DB::table('careers')
            ->orderBy('created_at', 'desc')
            ->paginate(10)  // Add pagination with 10 items per page
            ->through(function($career) {
                $career->created_at = Carbon::parse($career->created_at);
                $career->updated_at = Carbon::parse($career->updated_at);
                return $career;
            });

        return view('pages.admin.career', compact('careers'));
    }

    public function edit($id)
    {
        $career = DB::table('careers')
            ->where('id', $id)
            ->first();

        if (!$career) {
            return response()->json(['error' => 'Career not found'], 404);
        }

        return response()->json([
            'id' => $career->id,
            'title' => $career->title,
            'description' => $career->description,
            'file' => $career->file
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
            $path = $request->file('file')->store('careers', 'public');
            $data['file'] = $path;
        }

        DB::table('careers')->insert($data);

        return redirect()->route('admin.career')->with('success', 'Career created successfully');
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
            $career = DB::table('careers')->where('id', $id)->first();
            if ($career && $career->file) {
                Storage::disk('public')->delete($career->file);
            }

            $path = $request->file('file')->store('careers', 'public');
            $data['file'] = $path;
        }

        DB::table('careers')->where('id', $id)->update($data);

        return redirect()->route('admin.career')->with('success', 'Career updated successfully');
    }

    public function destroy($id)
    {
        $career = DB::table('careers')->where('id', $id)->first();

        if ($career && $career->file) {
            Storage::disk('public')->delete($career->file);
        }

        DB::table('careers')->where('id', $id)->delete();

        return response()->json(['success' => true]);
    }
}




