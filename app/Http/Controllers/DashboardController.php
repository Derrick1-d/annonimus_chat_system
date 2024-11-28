<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function studentDashboard()
    {
        $lecturers = User::where('role', 'lecturer')->get();
        return view('student.dashboard', compact('lecturers'));
    }
}
