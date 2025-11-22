<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        $subjects = Subject::with('department')
            ->orderBy('name')
            ->get();

        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.subjects.create', compact('departments'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:50', 'unique:subjects,code'],
            'department_id' => ['required', 'exists:departments,id'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->has('is_active');

        Subject::create($data);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'Subject created successfully.');
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.subjects.edit', compact('subject', 'departments'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:50', 'unique:subjects,code,' . $subject->id],
            'department_id' => ['required', 'exists:departments,id'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $subject->update($data);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'Subject updated successfully.');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'Subject deleted.');
    }
}
