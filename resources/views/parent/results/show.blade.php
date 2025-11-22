@extends('layouts.app')

@section('title', 'Child Results')
@section('page_title', 'Child Results')

@section('content')
    @php
        $fullName = collect([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])->filter()->join(' ');
    @endphp

    {{-- CHILD HEADER CARD --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div class="d-flex align-items-center">
                {{-- Avatar circle --}}
                <div class="me-3">
                    <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                         style="width: 42px; height: 42px;">
                        <span class="fw-bold text-success">
                            {{ strtoupper(mb_substr($student->first_name ?? 'S', 0, 1)) }}
                        </span>
                    </div>
                </div>

                <div>
                    <h6 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-person-fill text-success"></i>
                        <span>{{ $fullName }}</span>
                    </h6>
                    <p class="small text-muted mb-0">
                        Class:
                        @if($student->class)
                            {{ $student->class->name }} {{ $student->class->stream }}
                        @else
                            <span class="text-muted">No class assigned</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="text-end small text-muted">
                <div>
                    <i class="bi bi-calendar-event me-1"></i>
                    <strong>{{ now()->format('d M Y') }}</strong>
                </div>

                @if($selectedExamId && $exams->isNotEmpty())
                    @php
                        $selectedExam = $exams->firstWhere('id', $selectedExamId);
                    @endphp
                    @if($selectedExam)
                        <div class="mt-1">
                            <span class="text-muted">Selected Exam:</span><br>
                            <span class="fw-semibold text-primary">
                                {{ $selectedExam->name }}
                                – Term {{ $selectedExam->term }}
                                – {{ $selectedExam->year }}
                            </span>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    {{-- EXAM SELECTION + OVERALL SUMMARY --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">

            {{-- Exam selection --}}
            <div class="row g-2 align-items-end mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        <i class="bi bi-list-check me-1 text-primary"></i>
                        Select Exam
                    </label>
                    <select id="examSelect" class="form-select form-select-sm">
                        <option value="">Choose exam...</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}"
                                {{ (int)($selectedExamId ?? 0) === $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} — Term {{ $exam->term }} {{ $exam->year }}
                            </option>
                        @endforeach
                    </select>
                    <div class="small text-muted mt-1">
                        Chagua mtihani ili kuona alama za mwanao kwa kipindi husika.
                    </div>
                </div>

                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    @if($selectedExamId && isset($selectedExam))
                        <span class="badge bg-primary-subtle text-primary small">
                            <i class="bi bi-info-circle me-1"></i>
                            Showing results for: {{ $selectedExam->name }} (Term {{ $selectedExam->term }}, {{ $selectedExam->year }})
                        </span>
                    @else
                        <span class="small text-muted">
                            Hakuna mtihani uliyochaguliwa bado.
                        </span>
                    @endif
                </div>
            </div>

            {{-- Overall summary --}}
            @if($overallAverage !== null)
                <div class="border rounded p-2 bg-light">
                    <div class="row text-center small">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="text-muted text-uppercase" style="font-size: 0.7rem;">Overall Average</div>
                            <div class="fw-bold fs-6">{{ $overallAverage }} / 100</div>
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="text-muted text-uppercase" style="font-size: 0.7rem;">Overall Grade</div>
                            <div class="fw-bold fs-6">{{ $overallGrade }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted text-uppercase" style="font-size: 0.7rem;">Remarks</div>
                            <div class="fw-bold fs-6">{{ $overallRemarks }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- RESULTS GROUPED BY DEPARTMENT --}}
    @if($groupedResults->isNotEmpty())
        @foreach($groupedResults as $departmentName => $deptResults)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 small fw-semibold">
                        <i class="bi bi-journal-bookmark me-1 text-primary"></i>
                        Department:
                        <span class="text-primary">{{ $departmentName }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Subject</th>
                                    <th style="width: 100px;">Marks</th>
                                    <th style="width: 90px;">Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deptResults as $index => $result)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $result->subject->name ?? '—' }}</td>
                                        <td>{{ $result->score }}</td>
                                        <td>{{ $result->grade }}</td>
                                        <td>{{ $result->remarks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-center small text-muted mb-0">
                    @if($exams->isEmpty())
                        No exams available for this student yet.
                    @elseif($selectedExamId)
                        No results found for this exam.
                    @else
                        Please select an exam to view results.
                    @endif
                </p>
            </div>
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Back to dashboard
        </a>

        @if($selectedExamId)
            <a href="{{ route('parent.results.report-card', [
                    'student' => $student->id,
                    'exam_id' => $selectedExamId,
                ]) }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-file-earmark-text me-1"></i>
                View Report Card
            </a>
        @endif
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
