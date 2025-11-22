<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    /**
     * Show form to manage subjects & teacher assignments for a class.
     */
    public function edit(SchoolClass $class)
    {
        // Active subjects only
        $subjects = Subject::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Teachers (users with role 'teacher')
        $teachers = User::whereHas('roles', function ($q) {
                $q->where('slug', 'teacher');
            })
            ->orderBy('name')
            ->get();

        // Existing assignments: subject_id => teacher_user_id
        $existing = $class->subjects()
            ->withPivot('teacher_user_id')
            ->get()
            ->pluck('pivot.teacher_user_id', 'id')
            ->toArray();

        return view('admin.classes.subjects', [
            'class'        => $class,
            'subjects'     => $subjects,
            'teachers'     => $teachers,
            'assignments'  => $existing,
        ]);
    }

    /**
     * Update subjects & teacher assignments for a class.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'subject_ids'   => ['array'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],

            'teacher_ids'   => ['array'],
            'teacher_ids.*' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $subjectIds = $data['subject_ids'] ?? [];
        $teacherIds = $data['teacher_ids'] ?? [];

        $syncData = [];

        foreach ($subjectIds as $subjectId) {
            $teacherId = $teacherIds[$subjectId] ?? null;

            $syncData[$subjectId] = [
                'teacher_user_id' => $teacherId,
            ];
        }

        // Sync pivot: class_subject
        $class->subjects()->sync($syncData);

        return redirect()
            ->route('admin.classes.subjects.edit', $class)
            ->with('status', 'Class subjects & teachers updated successfully.');
    }
}
