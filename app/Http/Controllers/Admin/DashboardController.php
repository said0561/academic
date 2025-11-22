<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
        'totalStudents' => \App\Models\Student::count(),
        'totalClasses'  => \App\Models\SchoolClass::count(),
        'totalExams'    => \App\Models\Exam::count(),
        'totalTeachers' => \App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'teacher'))->count(),
        'totalParents'  => \App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'parent'))->count(),
        'totalUsers'    => \App\Models\User::count(),
        'recentExams'   => \App\Models\Exam::orderByDesc('year')
                            ->orderByDesc('term')
                            ->limit(5)
                            ->get(),
    ]);
    }
}
