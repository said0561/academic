@extends('layouts.app')

@section('title', 'Child Report Card')
@section('page_title', 'Child Report Card')

@section('content')
    @php
        $fullName = collect([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])->filter()->join(' ');

        $deptAvgs = $departmentAverages ?? [];
    @endphp

    <div class="card shadow-sm border-0 report-card-card">
        <div class="card-body report-card-body">

            {{-- HEADER YA SHULE / REPORT CARD --}}
            <div class="mb-3 border-bottom pb-2">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    {{-- LOGO YA SHULE --}}
                    <div class="me-2">
                        <img src="{{ asset('images/school-logo.png') }}"
                             alt="School Logo"
                             style="height: 60px; width: 60px; object-fit: contain;">
                    </div>
                    <div class="text-center">
                        <h5 class="mb-0 fw-bold text-uppercase" style="letter-spacing: .06em; color:#b93173;">
                            IBADHI ISLAMIC PRE &amp; PRIMARY SCHOOL
                        </h5>
                        <p class="small mb-0 text-muted">PUPILS ACADEMIC PROGRESS REPORT</p>

                        @if($selectedExam)
                            <p class="small mb-0 fw-semibold" style="color:#118a3b;">
                                {{ $selectedExam->name }} &nbsp;·&nbsp;
                                Term {{ $selectedExam->term }} &nbsp;·&nbsp;
                                {{ $selectedExam->year }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Decorative underline --}}
                <div class="d-flex justify-content-center">
                    <div style="height: 4px; width: 180px; background: linear-gradient(90deg,#b93173,#118a3b); border-radius: 999px;"></div>
                </div>
            </div>

            {{-- INFO ZA MTOTO NA DARASA --}}
            <div class="row small mb-3">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Student:</strong>
                        <span class="fw-semibold">{{ $fullName }}</span>
                    </p>
                    <p class="mb-1">
                        <strong>Class:</strong>
                        @if($student->class)
                            {{ $student->class->name }} {{ $student->class->stream }}
                        @else
                            <span class="text-muted">No class assigned</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1">
                        <strong>Exam:</strong>
                        @if($selectedExam)
                            {{ $selectedExam->name }} (Term {{ $selectedExam->term }}, {{ $selectedExam->year }})
                        @else
                            <span class="text-muted">No exam selected</span>
                        @endif
                    </p>
                    <p class="mb-1">
                        <strong>Date Printed:</strong>
                        {{ now()->format('d M Y') }}
                    </p>
                </div>
            </div>

            {{-- CHAGUA EXAM (IONEKANE KWENYE SCREEN TU, ISIFANYE PRINT) --}}
            <div class="row g-2 mb-3 exam-select-row">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Select Exam</label>
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

            {{-- OVERALL & DEPARTMENT AVERAGES – GRAPHIC SUMMARY --}}
            @if($overallAverage !== null)
                <div class="border rounded p-2 mb-3 bg-light">
                    <div class="row small avg-row text-center g-2">
                        {{-- Overall box --}}
                        <div class="col-md-4 col-sm-4 mb-2 avg-col">
                            <div class="p-2 h-100 rounded-3"
                                 style="background: linear-gradient(135deg,#b93173 0%,#de6da3 100%); color:#fff;">
                                <span class="d-block text-uppercase small fw-semibold">Overall Average</span>
                                <span class="d-block fs-5 fw-bold">{{ $overallAverage }} / 100</span>
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark me-1">
                                        Grade: <strong>{{ $overallGrade }}</strong>
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        {{ $overallRemarks }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Department boxes --}}
                        @foreach($deptAvgs as $deptName => $avg)
                            @php
                                [$p, $g, $r] = \App\Models\Result::computeGrade($avg, 100);
                            @endphp
                            <div class="col-md-4 col-sm-4 mb-2 avg-col">
                                <div class="p-2 h-100 rounded-3 border"
                                     style="background:#f9fafb; border-left:4px solid #118a3b;">
                                    <span class="text-muted d-block text-uppercase small fw-semibold">
                                        {{ $deptName }} Avg
                                    </span>
                                    <span class="fw-bold d-block">{{ $avg }} / 100</span>
                                    <span class="badge bg-success-subtle text-success mt-1">
                                        Grade: <strong>{{ $g }}</strong>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- MATOKEO KWA DEPARTMENT --}}
            @if($groupedResults->isNotEmpty())
                @foreach($groupedResults as $departmentName => $deptResults)
                    <div class="mb-3 border rounded overflow-hidden">
                        <div class="px-3 py-2 border-bottom"
                             style="background: linear-gradient(90deg,#111827,#1f2937); color:#e5e7eb;">
                            <h6 class="mb-0 small fw-semibold d-flex justify-content-between align-items-center">
                                <span>
                                    Department:
                                    <span class="text-warning">{{ $departmentName }}</span>
                                </span>
                                <span class="small text-muted">
                                    Subjects: {{ $deptResults->count() }}
                                </span>
                            </h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Subject</th>
                                        <th style="width: 130px;">Marks</th>
                                        <th style="width: 80px;">Grade</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deptResults as $index => $result)
                                        @php
                                            $score = (float) $result->score;
                                            $grade = $result->grade;
                                            $gradeClass = match($grade) {
                                                'A' => 'bg-success',
                                                'B' => 'bg-primary',
                                                'C' => 'bg-info',
                                                'D' => 'bg-warning text-dark',
                                                default => 'bg-danger',
                                            };
                                            $barWidth = max(0, min(100, $score)); // 0-100
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $result->subject->name ?? '—' }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold">
                                                        {{ $result->score }}
                                                    </span>
                                                    <div class="progress score-progress mt-1">
                                                        <div class="progress-bar"
                                                             role="progressbar"
                                                             style="width: {{ $barWidth }}%;"
                                                             aria-valuenow="{{ $barWidth }}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $gradeClass }}">
                                                    {{ $grade }}
                                                </span>
                                            </td>
                                            <td>{{ $result->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info small mb-0">
                    @if($exams->isEmpty())
                        No exams available for this student yet.
                    @elseif($selectedExamId)
                        No results found for this exam.
                    @else
                        Please select an exam to view this report card.
                    @endif
                </div>
            @endif

            {{-- SIGNATURES AREA – mzazi ata-print, kwa hiyo tunamuacha Parent + Teacher --}}
            <div class="row small mt-3">
                <div class="col-md-6 mb-3">
                    <p class="mb-1"><strong>Class Teacher:</strong> ________________________________________________________________</p>
                    <p class="mb-1"><strong>Signature:</strong> ________________________________________________________________</p>
                                        <p class="mb-1"><strong>Parent / Guardian:</strong> ________________________________________________________________</p>
                    <p class="mb-1"><strong>Signature:</strong> ________________________________________________________________</p>
                </div>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 actions-print-area">
                <a href="{{ route('parent.results.show', $student) }}"
                   class="btn btn-outline-secondary btn-sm">
                    &larr; Back to Results
                </a>

                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    Print Report Card
                </button>
            </div>

        </div>
    </div>

    {{-- JS: change exam --}}
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

    {{-- PRINT & STYLE CSS --}}
    <style>
        .avg-row {
            display: flex;
            flex-wrap: wrap;
        }
        .avg-row .avg-col {
            flex: 1 1 0;
        }

        .score-progress {
            height: 4px;
            background-color: #e5e7eb;
        }
        .score-progress .progress-bar {
            background: linear-gradient(90deg,#118a3b,#b93173);
        }

        @media print {
            /* Hifadhi rangi kwenye PDF/Print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Ficha vitu vya system wakati wa print */
            .exam-select-row {
                display: none !important;
            }

            .actions-print-area {
                display: none !important;
            }

            button, .btn {
                display: none !important;
            }

            .avg-row {
                flex-wrap: nowrap !important;
            }
        }
    </style>
@endsection
