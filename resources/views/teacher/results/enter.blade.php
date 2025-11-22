@extends('layouts.app')

@section('title', 'Enter Marks')
@section('page_title', 'Enter Marks')

@section('content')
    @php
        // Exam iliyochaguliwa sasa (kutoka kwa ?exam_id=...)
        $selectedExam = $selectedExamId
            ? $exams->firstWhere('id', (int) $selectedExamId)
            : null;
    @endphp

    {{-- TOP: CLASS + SUBJECT + EXAM INFO --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-start gap-3">

            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-journal-check me-1 text-primary"></i>
                    {{ $class->name }} {{ $class->stream }} — {{ $subject->name }} ({{ $subject->code }})
                </h5>
                <p class="small text-muted mb-1">
                    Enter marks for each student. Marks are out of <strong>100</strong>.
                </p>

                @if($selectedExam)
                    <p class="small text-primary mb-0">
                        <i class="bi bi-clipboard-check me-1"></i>
                        Selected Exam:
                        <strong>
                            {{ $selectedExam->name }} – Term {{ $selectedExam->term }} – {{ $selectedExam->year }}
                        </strong>
                    </p>
                @else
                    <p class="small text-danger mb-0">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        No exam selected. Please choose an exam to start entering marks.
                    </p>
                @endif
            </div>

            {{-- BULK TOOLS: DOWNLOAD + UPLOAD CSV (only if exam selected) --}}
            @if($selectedExam)
                <div class="small d-flex flex-column align-items-end gap-1">

                    {{-- Download CSV TEMPLATE --}}
                    <form method="GET"
                          action="{{ route('teacher.results.template') }}"
                          class="d-inline">
                        <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-download me-1"></i>
                            CSV Template
                        </button>
                    </form>

                    {{-- UPLOAD FILLED CSV --}}
                    <form method="POST"
                          action="{{ route('teacher.results.import') }}"
                          enctype="multipart/form-data"
                          class="d-inline mt-1">
                        @csrf
                        <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                        <div class="d-flex flex-wrap gap-1 justify-content-end">
                            <input type="file"
                                   name="file"
                                   accept=".csv,text/csv"
                                   class="form-control form-control-sm"
                                   style="width: 220px;"
                                   required>
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-upload me-1"></i>
                                Upload CSV
                            </button>
                        </div>

                        <div class="small text-muted mt-1">
                            Use the template, fill scores then upload.
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>

    {{-- EXAM SELECTION CARD (GET kwa route teacher.results.create) --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <form method="GET"
                  action="{{ route('teacher.results.create', ['class' => $class->id, 'subject' => $subject->id]) }}"
                  class="row g-2 align-items-end small">

                <div class="col-md-4">
                    <label for="exam_id" class="form-label mb-1 fw-semibold">
                        <i class="bi bi-list-check me-1 text-primary"></i>
                        Exam
                    </label>
                    <select name="exam_id" id="exam_id" class="form-select form-select-sm">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}"
                                {{ (string)$selectedExamId === (string)$exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} — Term {{ $exam->term }} {{ $exam->year }}
                            </option>
                        @endforeach
                    </select>
                    <div class="small text-muted mt-1">
                        Chagua mtihani kisha bonyeza <strong>Load Students</strong>.
                    </div>
                </div>

                <div class="col-md-4">
                    {{-- future: extra filters / search --}}
                </div>

                <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                    <button type="submit" class="btn btn-sm btn-primary mt-3">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Load Students
                    </button>
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-sm btn-outline-secondary mt-3">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- STATUS / ERRORS --}}
    @if (session('status'))
        <div class="alert alert-success small">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- KAMA exam haijachaguliwa / hakuna wanafunzi --}}
    @if(!$selectedExam)
        <div class="alert alert-info small">
            Please select an exam and click <strong>Load Students</strong> to start entering marks.
        </div>

    @elseif($students->isEmpty())
        <div class="alert alert-warning small">
            No students found in this class.
        </div>

    @else
        {{-- FORM YA KU-SAVE MARKS --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <form method="POST"
                      action="{{ route('teacher.results.store', ['class' => $class->id, 'subject' => $subject->id]) }}">
                    @csrf

                    <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>Student</th>
                                    <th style="width: 130px;">Score (0–100)</th>
                                    <th style="width: 80px;">Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    @php
                                        $fullName = collect([
                                            $student->first_name,
                                            $student->middle_name,
                                            $student->last_name,
                                        ])->filter()->join(' ');

                                        $existing = $existingResults[$student->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $fullName }}</td>
                                        <td>
                                            <input
                                                type="number"
                                                name="scores[{{ $student->id }}]"
                                                class="form-control form-control-sm"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                value="{{ old('scores.'.$student->id, $existing->score ?? '') }}"
                                            >
                                        </td>
                                        <td>
                                            @if($existing)
                                                <span class="badge bg-secondary">{{ $existing->grade }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($existing)
                                                <span class="small">{{ $existing->remarks }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-2 border-top d-flex justify-content-between align-items-center">
                        <span class="small text-muted">
                            Existing marks will be <strong>updated</strong> if you change the score and save.
                        </span>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-check2-circle me-1"></i>
                            Save Marks
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
