<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CareerController extends Controller
{
    public function studcareer()
    {
        $careers = DB::table('careers')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Changed from get() to paginate()

        // Format the dates after pagination
        $careers->getCollection()->transform(function($career) {
            $career->created_at = Carbon::parse($career->created_at)->format('d M Y');
            return $career;
        });

        return view('pages.student.career', compact('careers'));
    }
}


