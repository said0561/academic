<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::orderBy('year', 'desc')
            ->orderBy('term', 'desc')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.exams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'term' => ['required', 'integer', 'min:1', 'max:3'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        Exam::create($data);

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'Exam created successfully.');
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'term' => ['required', 'integer', 'min:1', 'max:3'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $exam->update($data);

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'Exam deleted successfully.');
    }
}
