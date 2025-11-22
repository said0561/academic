<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultEntryController extends Controller
{
    /**
     * Show form to enter marks for a given class & subject.
     */

public function create(SchoolClass $class, Subject $subject, Request $request)
{
    $teacher = Auth::user();

    // (optional) unaweza kucheck kama mwalimu ana ruhusa ya somo hili na darasa hili
    // ukitaka, tuongeze baadaye.

    // Exams zote
    $exams = Exam::orderBy('year', 'desc')
        ->orderBy('term', 'desc')
        ->orderBy('name')
        ->get();

    $selectedExamId = $request->query('exam_id');

    // Wanafunzi wa class husika
    $students = Student::where('class_id', $class->id)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    // Existing results kwa exam + subject hii
    $existingResults = [];

    if ($selectedExamId) {
        $existingResults = Result::where('exam_id', $selectedExamId)
            ->where('subject_id', $subject->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');
    }

    return view('teacher.results.enter', [
        'class'           => $class,
        'subject'         => $subject,
        'exams'           => $exams,
        'selectedExamId'  => $selectedExamId,
        'students'        => $students,
        'existingResults' => $existingResults,
    ]);
}

    /**
     * Store marks for a given class & subject & exam.
     */
    public function store(Request $request, SchoolClass $class, Subject $subject)
    {
        $teacher = Auth::user();

        // Hakikisha assignment bado ipo
        $assignment = ClassSubject::where('class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_user_id', $teacher->id)
            ->first();

        if (!$assignment) {
            abort(403, 'You are not assigned to this class and subject.');
        }

        $data = $request->validate([
            'exam_id'           => ['required', 'exists:exams,id'],
            'scores'            => ['array'],
            'scores.*'          => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $examId = $data['exam_id'];
        $scores = $data['scores'] ?? [];

        // Kwa sasa tunachukulia exam total marks = 100
        $examTotalMarks = 100;

        DB::transaction(function () use ($scores, $examId, $subject, $examTotalMarks) {
            foreach ($scores as $studentId => $score) {
                if ($score === null || $score === '') {
                    continue;
                }

                $score = floatval($score);

                // Pata percentage + grade + remarks
                [$percentage, $grade, $remarks] = Result::computeGrade($score, $examTotalMarks);

                Result::updateOrCreate(
                    [
                        'exam_id'    => $examId,
                        'student_id' => $studentId,
                        'subject_id' => $subject->id,
                    ],
                    [
                        'score'   => $score,
                        'grade'   => $grade,
                        'remarks' => $remarks,
                    ]
                );
            }
        });

       return redirect()
    ->route('teacher.results.create', [
        'class'   => $class->id,
        'subject' => $subject->id,
        'exam_id' => $examId,
    ])
    ->with('status', 'Marks saved successfully.');

    }

    public function show(Request $request, SchoolClass $class, Subject $subject)
{
    $teacher = Auth::user();

    // Hakikisaha huyu mwalimu ame-assigniwa hii class+subject
    $assignment = ClassSubject::where('class_id', $class->id)
        ->where('subject_id', $subject->id)
        ->where('teacher_user_id', $teacher->id)
        ->first();

    if (!$assignment) {
        abort(403, 'You are not assigned to this class and subject.');
    }

    // Exams zote (kwa sasa simple – zote tu)
    $exams = Exam::orderBy('year', 'desc')
        ->orderBy('term')
        ->orderBy('name')
        ->get();

    // exam iliyochaguliwa: query param au ya kwanza
    $selectedExamId = $request->query('exam_id', $exams->first()->id ?? null);

    // Wanafunzi wa class hii
    $students = Student::where('class_id', $class->id)
        ->orderBy('first_name')
        ->orderBy('middle_name')
        ->orderBy('last_name')
        ->get();

    // Results zilizokwisha kuhifadhiwa kwa exam + subject + wanafunzi hawa
    $resultsMap = [];
    if ($selectedExamId && $students->isNotEmpty()) {
        $resultsMap = Result::where('exam_id', $selectedExamId)
            ->where('subject_id', $subject->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id'); // ili tuweze $resultsMap[$student->id]
    }

    return view('teacher.results.view', [
        'teacher'        => $teacher,
        'class'          => $class,
        'subject'        => $subject,
        'exams'          => $exams,
        'students'       => $students,
        'selectedExamId' => $selectedExamId,
        'resultsMap'     => $resultsMap,
    ]);
}

public function downloadTemplate(Request $request) 
{
    $data = $request->validate([
        'exam_id'    => ['required', 'exists:exams,id'],
        'class_id'   => ['required', 'exists:classes,id'],
        'subject_id' => ['required', 'exists:subjects,id'],
    ]);

    $exam    = Exam::findOrFail($data['exam_id']);
    $class   = SchoolClass::findOrFail($data['class_id']);
    $subject = Subject::findOrFail($data['subject_id']);

    // wanafunzi wa darasa husika
    $students = Student::where('class_id', $class->id)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    $fileName = sprintf(
        'results_template_%s_%s_%s.csv',
        str_replace(' ', '_', strtolower($exam->name)),
        str_replace(' ', '_', strtolower($class->name . $class->stream)),
        str_replace(' ', '_', strtolower($subject->code ?? $subject->name))
    );

    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $columns = ['student_id', 'full_name', 'score'];

    return response()->stream(function () use ($students, $columns) {
        $handle = fopen('php://output', 'w');

        // header row
        fputcsv($handle, $columns);

        // data rows
        foreach ($students as $student) {
            $fullName = collect([
                $student->first_name,
                $student->middle_name,
                $student->last_name,
            ])->filter()->join(' ');

            fputcsv($handle, [
                $student->id,
                $fullName,
                '', // empty score: mwalimu ataijaza
            ]);
        }

        fclose($handle);
    }, 200, $headers);
}

public function importCsv(Request $request)
{
    $data = $request->validate([
        'exam_id'    => ['required', 'exists:exams,id'],
        'class_id'   => ['required', 'exists:classes,id'],
        'subject_id' => ['required', 'exists:subjects,id'],
        'file'       => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
    ]);

    $examId    = (int) $data['exam_id'];
    $classId   = (int) $data['class_id'];
    $subjectId = (int) $data['subject_id'];

    $file = $data['file'];
    $path = $file->getRealPath();

    $handle = fopen($path, 'r');
    if (!$handle) {
        return back()->withErrors(['file' => 'Unable to read uploaded file.']);
    }

    $headerPassed = false;
    $imported = 0;

    try {
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {

            // ruka mistari iliyo tupu
            if (count(array_filter($row)) === 0) {
                continue;
            }

            // ruka header (mstari wa kwanza)
            if (!$headerPassed) {
                $headerPassed = true;
                continue;
            }

            // CSV: student_id, full_name, score
            [$studentId, $fullName, $score] = array_pad($row, 3, null);

            $studentId = (int) $studentId;
            $score     = $score !== null ? trim($score) : null;

            // hakikisha student_id na score zipo
            if (!$studentId || $score === null || $score === '') {
                continue;
            }

            // hakikisha score ni namba na 0–100
            if (!is_numeric($score)) {
                continue;
            }

            $score = (float) $score;
            if ($score < 0 || $score > 100) {
                continue;
            }

            // hakikisha mwanafunzi yuko kwenye darasa husika
            $student = Student::where('id', $studentId)
                ->where('class_id', $classId)
                ->first();

            if (!$student) {
                continue;
            }

            // compute grade & remarks (tumesema total_marks=100)
            [$percentage, $grade, $remarks] = Result::computeGrade($score, 100);

            // update or create result
            Result::updateOrCreate(
                [
                    'exam_id'    => $examId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                ],
                [
                    'score'   => $score,
                    'grade'   => $grade,
                    'remarks' => $remarks,
                ]
            );

            $imported++;
        }

        fclose($handle);

        return back()->with('status', "Successfully imported/updated {$imported} results from CSV.");

    } catch (\Throwable $e) {
        if (is_resource($handle)) {
            fclose($handle);
        }

        return back()->withErrors([
            'file' => 'Import failed: ' . $e->getMessage(),
        ]);
    }
}



}
