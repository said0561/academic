<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentResultController extends Controller
{
    /**
     * Show results for a specific child (student).
     */
    public function index(Request $request, Student $student)
    {
        $parent = Auth::user();

        // Hakikisha huyu student ni mtoto wa mzazi huyu
        $isChild = $parent->children()
            ->where('students.id', $student->id)
            ->exists();

        if (! $isChild) {
            abort(403, 'You are not allowed to view results for this student.');
        }

        // Exams ambazo huyu mwanafunzi ana matokeo
        $exams = Exam::whereHas('results', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->orderBy('year', 'desc')
            ->orderBy('term')
            ->orderBy('name')
            ->get();

        $selectedExamId = $request->query('exam_id', $exams->first()->id ?? null);

        $results = collect();
        $overallAverage = null;
        $overallGrade = null;
        $overallRemarks = null;
        $groupedResults = collect();

        if ($selectedExamId) {
            $results = Result::with(['subject.department'])
                ->where('student_id', $student->id)
                ->where('exam_id', $selectedExamId)
                ->get()
                ->sortBy(function ($res) {
                    return $res->subject->name ?? '';
                });

            if ($results->isNotEmpty()) {
                // Average ya marks (tuna assume out of 100)
                $overallAverage = round($results->avg('score'), 2);

                // Tumia grading system ile ile (A–E) kwa average
                [$percentage, $grade, $remarks] = Result::computeGrade($overallAverage, 100);
                $overallGrade = $grade;
                $overallRemarks = $remarks;

                // Group by department
                $groupedResults = $results->groupBy(function ($result) {
                    return optional($result->subject->department)->name ?? 'Other';
                });
            }
        }

        return view('parent.results.show', [
            'parent'          => $parent,
            'student'         => $student,
            'exams'           => $exams,
            'selectedExamId'  => $selectedExamId,
            'results'         => $results,
            'overallAverage'  => $overallAverage,
            'overallGrade'    => $overallGrade,
            'overallRemarks'  => $overallRemarks,
            'groupedResults'  => $groupedResults,
        ]);
    }

    public function report(Request $request, Student $student)
{
    $parent = Auth::user();

    // Hakikisha huyu student ni mtoto wa mzazi huyu
    $isChild = $parent->children()
        ->where('students.id', $student->id)
        ->exists();

    if (! $isChild) {
        abort(403, 'You are not allowed to view results for this student.');
    }

    // Exams ambazo mwanafunzi huyu ana matokeo
    $exams = Exam::whereHas('results', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })
        ->orderBy('year', 'desc')
        ->orderBy('term')
        ->orderBy('name')
        ->get();

    $selectedExamId = $request->query('exam_id', $exams->first()->id ?? null);
    $selectedExam   = $exams->firstWhere('id', $selectedExamId);

    $results            = collect();
    $overallAverage     = null;
    $overallGrade       = null;
    $overallRemarks     = null;
    $groupedResults     = collect();
    $departmentAverages = [];

    if ($selectedExamId && $selectedExam) {
        $results = Result::with(['subject.department'])
            ->where('student_id', $student->id)
            ->where('exam_id', $selectedExamId)
            ->get()
            ->sortBy(function ($res) {
                return $res->subject->name ?? '';
            });

        if ($results->isNotEmpty()) {
            // Overall
            $overallAverage = round($results->avg('score'), 2);
            [$percentage, $grade, $remarks] = Result::computeGrade($overallAverage, 100);
            $overallGrade   = $grade;
            $overallRemarks = $remarks;

            // Group by department
            $groupedResults = $results->groupBy(function ($result) {
                return optional($result->subject->department)->name ?? 'Other';
            });

            // Department averages
            foreach ($groupedResults as $deptName => $deptResults) {
                $departmentAverages[$deptName] = round($deptResults->avg('score'), 2);
            }
        }
    }

    return view('parent.results.report_card', [
        'parent'             => $parent,
        'student'            => $student,
        'exams'              => $exams,
        'selectedExamId'     => $selectedExamId,
        'selectedExam'       => $selectedExam,
        'results'            => $results,
        'overallAverage'     => $overallAverage,
        'overallGrade'       => $overallGrade,
        'overallRemarks'     => $overallRemarks,
        'groupedResults'     => $groupedResults,
        'departmentAverages' => $departmentAverages,
    ]);
}

}
