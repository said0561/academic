<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;


class StudentController extends Controller
{
        public function index(Request $request)
    {
        // All classes for filter
        $classes = SchoolClass::orderBy('name')
            ->orderBy('stream')
            ->get();

        $classId = $request->query('class_id');

        $query = Student::with('class');

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $students = $query
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(20)
            ->withQueryString(); // ili pagination ibebe class_id pia

        return view('admin.students.index', [
            'students' => $students,
            'classes'  => $classes,
            'classId'  => $classId,
        ]);
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->orderBy('stream')->get();

        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name'=> 'nullable|string|max:255',
            'last_name'  => 'required|string|max:255',
            'gender'     => 'nullable|in:M,F',
            'dob'        => 'nullable|date',
            'class_id'   => 'required|exists:classes,id',
        ]);

        Student::create($data);

        return redirect()
            ->route('admin.students.index')
            ->with('status', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::orderBy('name')->orderBy('stream')->get();

        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'middle_name'=> 'nullable|string|max:255',
            'gender'     => 'nullable|in:M,F',
            'dob'        => 'nullable|date',
            'class_id'   => 'required|exists:classes,id',
        ]);

        $student->update($data);

        return redirect()
            ->route('admin.students.index')
            ->with('status', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('status', 'Student deleted.');
    }


    public function show(Student $student)
{
    // Kama hutaki page ya "View", unaweza kum-redirect tu kwenye edit
    return redirect()->route('admin.students.edit', $student->id);
    
    // Au ukitaka, unaweza kurender view ya details:
    // return view('admin.students.show', compact('student'));
}

}
