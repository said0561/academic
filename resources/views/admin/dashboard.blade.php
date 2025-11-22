@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Admin Dashboard')

@section('content')
    @php
        $admin = auth()->user();
    @endphp

    {{-- TOP WELCOME + SUMMARY --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-speedometer2 me-1 text-primary"></i>
                    Admin Dashboard
                </h5>
                <p class="small text-muted mb-0">
                    Karibu, {{ $admin?->name }}. Tumia ukurasa huu kusimamia madarasa, wanafunzi, walimu,
                    mitihani na matokeo ya shule.
                </p>
            </div>

            <div class="small text-muted text-end">
                <div>
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ now()->format('d M Y') }}
                </div>
                <div>
                    <i class="bi bi-building me-1"></i>
                    Ibadhi Islamic Pre &amp; Primary School
                </div>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-3">

        {{-- Total Students --}}
        <div class="col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-2 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                         style="width: 36px; height: 36px;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Students</div>
                        <div class="fw-bold">
                            {{ $totalStudents ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Classes --}}
        <div class="col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-2 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                         style="width: 36px; height: 36px;">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Classes</div>
                        <div class="fw-bold">
                            {{ $totalClasses ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Exams --}}
        <div class="col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-2 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center"
                         style="width: 36px; height: 36px;">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Exams</div>
                        <div class="fw-bold">
                            {{ $totalExams ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Teachers --}}
        <div class="col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-2 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center"
                         style="width: 36px; height: 36px;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Teachers</div>
                        <div class="fw-bold">
                            {{ $totalTeachers ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Parents --}}
        <div class="col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-2 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                         style="width: 36px; height: 36px;">
                        <i class="bi bi-house-heart-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Parents</div>
                        <div class="fw-bold">
                            {{ $totalParents ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- System Users --}}
        <div class="col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-2 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center"
                         style="width: 36px; height: 36px;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Users</div>
                        <div class="fw-bold">
                            {{ $totalUsers ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- BOTTOM ROW: LATEST EXAMS + QUICK LINKS --}}
    <div class="row g-3">
        {{-- Latest exams --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold small">
                        <i class="bi bi-clipboard-data me-1 text-primary"></i>
                        Recent Exams
                    </span>
                    <a href="{{ route('admin.exams.index') }}" class="small text-decoration-none">
                        View all &rarr;
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(isset($recentExams) && $recentExams->count())
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Term</th>
                                        <th>Year</th>
                                        <th class="text-end" style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentExams as $exam)
                                        <tr>
                                            <td>{{ $exam->name }}</td>
                                            <td>{{ $exam->term }}</td>
                                            <td>{{ $exam->year }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.exams.edit', $exam) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 small text-muted">
                            No recent exams to display.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2">
                    <span class="fw-bold small">
                        <i class="bi bi-lightning-charge-fill me-1 text-warning"></i>
                        Quick Actions
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-2 small">
                        <div class="col-6">
                            <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-primary btn-sm w-100 text-start">
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i>
                                Manage Classes
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-primary btn-sm w-100 text-start">
                                <i class="bi bi-people-fill me-1"></i>
                                Manage Students
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-primary btn-sm w-100 text-start">
                                <i class="bi bi-journal-bookmark-fill me-1"></i>
                                Subjects & Depts
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-primary btn-sm w-100 text-start">
                                <i class="bi bi-clipboard-check me-1"></i>
                                Manage Exams
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.results.index') }}" class="btn btn-outline-success btn-sm w-100 text-start">
                                <i class="bi bi-bar-chart-line-fill me-1"></i>
                                Results
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm w-100 text-start">
                                <i class="bi bi-people-gear me-1"></i>
                                System Users
                            </a>
                        </div>
                    </div>

                    <hr class="my-3">

                    <p class="small text-muted mb-1">
                        Tips:
                    </p>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Hakikisha madarasa, masomo na walimu wameunganishwa vizuri kabla ya kuingiza matokeo.</li>
                        <li>Tumia Class Exam Report kupata ripoti kamili ya darasa husika.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
