@extends('layouts.app')

@section('title', 'Class Exam Report')
@section('page_title', 'Class Exam Report')

@section('content')

    <div class="mb-3">
        <h6 class="fw-bold mb-1">
            {{ $class->name }} {{ $class->stream }}
        </h6>
        <p class="small text-muted mb-1">
            Exam performance summary for this class.
        </p>

        @if($selectedExam)
            <p class="small text-primary mb-0">
                Selected Exam:
                <strong>
                    {{ $selectedExam->name }}
                    – Term {{ $selectedExam->term }}
                    – {{ $selectedExam->year }}
                </strong>
            </p>
        @endif
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">

            {{-- Exam selection --}}
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Exam</label>
                    <select id="examSelect" class="form-select form-select-sm">
                        <option value="">Select exam</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}"
                                {{ (int)($selectedExamId ?? 0) === $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} — Term {{ $exam->term }} {{ $exam->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($rows->isEmpty())
                <div class="alert alert-info small mb-0">
                    No results available for this exam.
                </div>
            @else
                <div class="table-responsive border rounded">
                    <table class="table table-sm mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Student</th>
                                <th style="width: 110px;">Total Marks</th>
                                <th style="width: 80px;">Subjects</th>
                                <th style="width: 110px;">Average</th>
                                <th style="width: 70px;">Grade</th>
                                <th>Remarks</th>
                                <th style="width: 70px;">Position</th>
                                <th style="width: 120px;">Report</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($rows as $index => $row)
                                @php
                                    $student = $row['student'];
                                    $full = collect([
                                        $student->first_name,
                                        $student->middle_name,
                                        $student->last_name
                                    ])->filter()->join(' ');
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $full }}</td>

                                    <td>
                                        @if(!is_null($row['total']))
                                            {{ $row['total'] }}
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <td>{{ $row['subjects'] }}</td>

                                    <td>
                                        @if(!is_null($row['average']))
                                            {{ $row['average'] }}
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($row['grade']))
                                            {{ $row['grade'] }}
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <td>{{ $row['remarks'] ?? '—' }}</td>

                                    <td>
                                        @if(isset($row['position']) && $row['position'])
                                            <strong>{{ $row['position'] }}</strong>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($selectedExamId)
                                            <a href="{{ route('admin.classes.exam-report.student', [
                                                    'class'   => $class->id,
                                                    'student' => $student->id,
                                                    'exam_id' => $selectedExamId,
                                                ]) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Report Card
                                            </a>
                                        @else
                                            <span class="text-muted small">Select exam</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary btn-sm">
                    &larr; Back to Classes
                </a>

                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    Print Report
                </button>
            </div>

        </div>
    </div>

    {{-- Exam change reload --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const examSelect = document.getElementById('examSelect');
            if (!examSelect) return;

            examSelect.addEventListener('change', function () {
                const examId = this.value;
                if (!examId) return;

                const url = new URL(window.location.href);
                url.searchParams.set('exam_id', examId);
                window.location.href = url.toString();
            });
        });
    </script>

@endsection
