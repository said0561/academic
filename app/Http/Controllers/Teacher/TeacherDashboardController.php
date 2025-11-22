<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        // Masomo yote aliyokabidhiwa mwalimu huyu (pivot class_subject)
        $assignments = ClassSubject::with(['class', 'subject'])
            ->where('teacher_user_id', $teacher->id)
            ->get()
            ->sortBy(function ($item) {
                return ($item->class->name ?? '') . ' ' . ($item->subject->name ?? '');
            });

        return view('teacher.dashboard', [
            'teacher'     => $teacher,
            'assignments' => $assignments,
        ]);
    }
}
