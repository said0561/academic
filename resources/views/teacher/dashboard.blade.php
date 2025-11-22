@extends('layouts.app')

@section('title', 'Teacher Dashboard')
@section('page_title', 'Teacher Dashboard')

@section('content')
    @php
        $classesCount = $assignments->pluck('class.id')->filter()->unique()->count();
        $subjectsCount = $assignments->pluck('subject.id')->filter()->unique()->count();
    @endphp

    {{-- Welcome + small summary --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-person-badge-fill me-1 text-primary"></i>
                    Welcome, {{ $teacher->name }}
                </h5>
                <p class="small text-muted mb-0">
                    Hapa unaweza kuona madarasa na masomo uliyokabidhiwa, na kuingiza au kuangalia matokeo ya wanafunzi.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2 text-end small">
                <div class="px-3 py-2 border rounded bg-light">
                    <div class="text-muted text-uppercase" style="font-size: 0.7rem;">Classes</div>
                    <div class="fw-bold fs-6">{{ $classesCount }}</div>
                </div>
                <div class="px-3 py-2 border rounded bg-light">
                    <div class="text-muted text-uppercase" style="font-size: 0.7rem;">Subjects</div>
                    <div class="fw-bold fs-6">{{ $subjectsCount }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Assignments list --}}
    <div class="card shadow-sm border-0">
        <div class="card-header py-2 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 small fw-semibold">
                <i class="bi bi-journal-bookmark-fill me-1 text-primary"></i>
                My Classes & Subjects
            </h6>
        </div>

        <div class="card-body p-0">
            @if($assignments->isEmpty())
                <p class="small text-muted text-center py-3 mb-0">
                    No class/subject assignments found for your account yet.
                    Please contact the administrator.
                </p>
            @else
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th style="width: 190px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $index => $assignment)
                            @php
                                $class = $assignment->class;
                                $subject = $assignment->subject;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    @if($class)
                                        {{ $class->name }} {{ $class->stream }}
                                    @else
                                        <span class="text-muted small">[No class]</span>
                                    @endif
                                </td>

                                <td>
                                    @if($subject)
                                        {{ $subject->name }} ({{ $subject->code }})
                                    @else
                                        <span class="text-muted small">[No subject]</span>
                                    @endif
                                </td>

                                <td class="text-end">

                                    {{-- Results Entry --}}
                                    @if($class && $subject)
                                        <a href="{{ route('teacher.results.create', ['class' => $class->id, 'subject' => $subject->id]) }}"
                                           class="btn btn-sm btn-outline-primary me-1"
                                           title="Enter Marks">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Enter
                                        </a>

                                        <a href="{{ route('teacher.results.show', ['class' => $class->id, 'subject' => $subject->id]) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Results">
                                            <i class="bi bi-bar-chart-line me-1"></i>
                                            View
                                        </a>
                                    @else
                                        <span class="text-muted small">Incomplete assignment</span>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
