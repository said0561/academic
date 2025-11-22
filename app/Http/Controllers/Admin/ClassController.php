<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('name')->orderBy('stream')->get();

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'stream' => 'nullable|string|max:50',
        ]);

        SchoolClass::create($data);

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'Class created successfully.');
    }

    public function edit(SchoolClass $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'stream' => 'nullable|string|max:50',
        ]);

        $class->update($data);

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'Class deleted.');
    }
}
