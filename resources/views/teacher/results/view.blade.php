@extends('layouts.app')

@section('title', 'View Results')
@section('page_title', 'View Results')

@section('content')
    @php
        $selectedExam = $selectedExamId
            ? $exams->firstWhere('id', (int) $selectedExamId)
            : null;
    @endphp

    {{-- TOP SUMMARY: CLASS + SUBJECT + EXAM --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-start gap-3">

            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-bar-chart-line-fill me-1 text-primary"></i>
                    {{ $class->name }} {{ $class->stream }} — {{ $subject->name }} ({{ $subject->code }})
                </h5>
                <p class="small text-muted mb-1">
                    View saved marks, grades and remarks for this class and subject.
                </p>
            </div>

            <div class="text-end small">
                @if($selectedExam)
                    <div class="text-muted">
                        <i class="bi bi-clipboard-check me-1"></i>
                        Selected Exam:
                    </div>
                    <div class="fw-semibold text-primary">
                        {{ $selectedExam->name }} – Term {{ $selectedExam->term }} – {{ $selectedExam->year }}
                    </div>
                @else
                    <span class="badge bg-warning-subtle text-warning">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        No exam selected
                    </span>
                @endif
            </div>

        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success py-2 small">
            {{ session('status') }}
        </div>
    @endif

    {{-- MAIN CARD: EXAM SELECTION + TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Exam selection --}}
            <div class="row g-2 mb-3 align-items-end small">
                <div class="col-md-6">
                    <label class="form-label mb-1 fw-semibold">
                        <i class="bi bi-list-check me-1 text-primary"></i>
                        Exam
                    </label>
                    <select id="examSelect" class="form-select form-select-sm">
                        <option value="">Select exam</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}"
                                {{ (int)($selectedExamId ?? 0) === $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} — Term {{ $exam->term }} {{ $exam->year }}
                            </option>
                        @endforeach
                    </select>
                    <div class="small text-muted mt-1">
                        Chagua mtihani ili kuona matokeo ya wanafunzi kwa kipindi husika.
                    </div>
                </div>

                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    @if($selectedExam)
                        <span class="badge bg-primary-subtle text-primary">
                            <i class="bi bi-info-circle me-1"></i>
                            Showing results for {{ $selectedExam->name }} (Term {{ $selectedExam->term }}, {{ $selectedExam->year }})
                        </span>
                    @else
                        <span class="small text-muted">
                            Hakuna mtihani uliyochaguliwa bado.
                        </span>
                    @endif
                </div>
            </div>

            {{-- Results table --}}
            <div class="table-responsive border rounded">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Student</th>
                            <th style="width: 100px;">Marks</th>
                            <th style="width: 90px;">Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php
                                $fullName = collect([
                                    $student->first_name,
                                    $student->middle_name,
                                    $student->last_name,
                                ])->filter()->join(' ');

                                $result = $resultsMap[$student->id] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fullName }}</td>
                                <td>
                                    @if($result)
                                        {{ $result->score }}
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($result)
                                        <span class="badge bg-secondary">{{ $result->grade }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($result)
                                        <span class="small">{{ $result->remarks }}</span>
                                    @else
                                        <span class="text-muted small">No marks yet</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center small text-muted py-3">
                                    No students found in this class.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to dashboard
                </a>

                <a href="{{ route('teacher.results.create', ['class' => $class->id, 'subject' => $subject->id]) }}"
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit / Enter Marks
                </a>
            </div>

        </div>
    </div>

    {{-- Change exam without losing the page --}}
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
