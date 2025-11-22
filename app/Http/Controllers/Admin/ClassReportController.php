<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Result;
use Illuminate\Http\Request;

class ClassReportController extends Controller
{
    /**
     * Show exam report (totals, averages, positions) for a given class.
     */
    public function index(Request $request, SchoolClass $class)
    {
        // Exams ambazo zina matokeo kwa wanafunzi wa class hii
        $exams = Exam::whereHas('results', function ($q) use ($class) {
                $q->whereHas('student', function ($qs) use ($class) {
                    $qs->where('class_id', $class->id);
                });
            })
            ->orderBy('year', 'desc')
            ->orderBy('term')
            ->orderBy('name')
            ->get();

        $selectedExamId = $request->query('exam_id', $exams->first()->id ?? null);

        $rows = collect();
        $selectedExam = null;

        if ($selectedExamId) {
            $selectedExam = $exams->firstWhere('id', $selectedExamId);

            // Wanafunzi wote wa class hii
            $students = Student::where('class_id', $class->id)
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name')
                ->get();

            // Results zote za exam hii kwa class hii
            $results = Result::with('subject')
                ->where('exam_id', $selectedExamId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->groupBy('student_id');

            foreach ($students as $student) {
                $studentResults = $results->get($student->id, collect());

                if ($studentResults->isEmpty()) {
                    // Hakuna matokeo kwa huyu mwanafunzi kwenye exam hii
                    $rows->push([
                        'student'   => $student,
                        'total'     => null,
                        'subjects'  => 0,
                        'average'   => null,
                        'grade'     => null,
                        'remarks'   => null,
                        'position'  => null,
                    ]);

                    continue;
                }

                $totalScore   = $studentResults->sum('score');
                $subjectsCount = $studentResults->count();

                // Average kwa somo (out of 100)
                $average = $subjectsCount > 0
                    ? round($totalScore / $subjectsCount, 2)
                    : null;

                // Tumia grading system ile ile A–E kwa average
                $grade = null;
                $remarks = null;

                if ($average !== null) {
                    [$percentage, $g, $r] = Result::computeGrade($average, 100);
                    $grade = $g;
                    $remarks = $r;
                }

                $rows->push([
                    'student'   => $student,
                    'total'     => $totalScore,
                    'subjects'  => $subjectsCount,
                    'average'   => $average,
                    'grade'     => $grade,
                    'remarks'   => $remarks,
                ]);
            }

            // Panga kwa total descending na weka positions
            $rows = $rows->sortByDesc(function ($row) {
                    return $row['total'] ?? -1;
                })
                ->values();

            $lastTotal = null;
            $lastPosition = 0;
            $index = 0;

            foreach ($rows as &$row) {
                $index++;

                if ($row['total'] === null) {
                    $row['position'] = null;
                    continue;
                }

                if ($lastTotal !== null && $row['total'] === $lastTotal) {
                    // Same marks → same position
                    $row['position'] = $lastPosition;
                } else {
                    $row['position'] = $index;
                    $lastPosition = $index;
                    $lastTotal = $row['total'];
                }
            }
            unset($row);
        }

        return view('admin.classes.exam_report', [
            'class'         => $class,
            'exams'         => $exams,
            'selectedExamId'=> $selectedExamId,
            'selectedExam'  => $selectedExam,
            'rows'          => $rows,
        ]);
    }

            public function studentReport(Request $request, SchoolClass $class, Student $student)
        {
            // Hakikisha mwanafunzi huyu yuko kwenye hii class
            if ($student->class_id !== $class->id) {
                abort(404, 'Student not in this class.');
            }

            // Exams ambazo mwanafunzi huyu ana matokeo ndani ya hii class
            $exams = Exam::whereHas('results', function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                })
                ->orderBy('year', 'desc')
                ->orderBy('term')
                ->orderBy('name')
                ->get();

            $selectedExamId = $request->query('exam_id', $exams->first()->id ?? null);
            $selectedExam = $exams->firstWhere('id', $selectedExamId);

            $results = collect();
            $overallAverage = null;
            $overallGrade = null;
            $overallRemarks = null;
            $groupedResults = collect();

            if ($selectedExamId && $selectedExam) {
                $results = Result::with(['subject.department'])
                    ->where('student_id', $student->id)
                    ->where('exam_id', $selectedExamId)
                    ->get()
                    ->sortBy(function ($res) {
                        return $res->subject->name ?? '';
                    });

        if ($results->isNotEmpty()) {
            $overallAverage = round($results->avg('score'), 2);

            // Tumia grading system ile ile
            [$percentage, $grade, $remarks] = Result::computeGrade($overallAverage, 100);
            $overallGrade = $grade;
            $overallRemarks = $remarks;

            // Group by department (kwa jina)
            $groupedResults = $results->groupBy(function ($result) {
                return optional($result->subject->department)->name ?? 'Other';
            });

            // Averages za kila department
            $departmentAverages = [];
            foreach ($groupedResults as $deptName => $deptResults) {
                $departmentAverages[$deptName] = round($deptResults->avg('score'), 2);
            }
        } else {
            $departmentAverages = [];
        }
            }

            return view('admin.classes.report_card', [
                'class'          => $class,
                'student'        => $student,
                'exams'          => $exams,
                'selectedExamId' => $selectedExamId,
                'selectedExam'   => $selectedExam,
                'results'        => $results,
                'overallAverage' => $overallAverage,
                'overallGrade'   => $overallGrade,
                'overallRemarks' => $overallRemarks,
                'groupedResults' => $groupedResults,
                'departmentAverages' => $departmentAverages,

            ]);
        }

}
