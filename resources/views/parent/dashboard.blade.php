@extends('layouts.app')

@section('title', 'Parent Dashboard')
@section('page_title', 'Parent Dashboard')

@section('content')
    @php
        $parent = auth()->user();
        $childrenCount = $children->count();
        $classes = $children->pluck('class')->filter();
        $uniqueClasses = $classes->unique('id')->values();
    @endphp

    {{-- Welcome + summary --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-house-heart-fill me-1 text-success"></i>
                    Karibu, {{ $parent->name }}
                </h5>
                <p class="small text-muted mb-1">
                    Huu ni ukurasa wako wa mzazi kuona maendeleo ya watoto wako shuleni.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2 text-end small">
                <div class="px-3 py-2 border rounded bg-light">
                    <div class="text-muted text-uppercase" style="font-size: 0.7rem;">Children</div>
                    <div class="fw-bold fs-6">{{ $childrenCount }}</div>
                </div>
                <div class="px-3 py-2 border rounded bg-light">
                    <div class="text-muted text-uppercase" style="font-size: 0.7rem;">Classes</div>
                    <div class="fw-bold fs-6">{{ $uniqueClasses->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Children list --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
            <span class="fw-bold small">
                <i class="bi bi-people-fill me-1 text-success"></i>
                My Children
            </span>
        </div>

        <div class="card-body">
            @if($childrenCount === 0)
                <p class="text-muted small mb-0">
                    Hakuna wanafunzi waliounganishwa na akaunti yako bado.
                    Tafadhali wasiliana na ofisi ya shule ili uunganishwe na mtoto wako.
                </p>
            @else
                <div class="row g-3">
                    @foreach($children as $child)
                        @php
                            $fullName = collect([
                                $child->first_name,
                                $child->middle_name,
                                $child->last_name,
                            ])->filter()->join(' ');
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded p-3 h-100 d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2">
                                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                                             style="width: 36px; height: 36px;">
                                            <span class="fw-bold text-success">
                                                {{ strtoupper(mb_substr($child->first_name ?? 'S', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $fullName }}</div>
                                        <div class="small text-muted">
                                            @if($child->class)
                                                Class: {{ $child->class->name }} {{ $child->class->stream }}
                                            @else
                                                <span class="text-muted">No class linked</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto d-flex justify-content-between gap-2">
                                    {{-- View Results --}}
                                    <a href="{{ route('parent.results.show', $child) }}"
                                       class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="bi bi-bar-chart-line me-1"></i>
                                        View Results
                                    </a>

                                    {{-- Report Card (only if route exists) --}}
                                    @if (Route::has('parent.results.report-card'))
                                        <a href="{{ route('parent.results.report-card', ['student' => $child->id]) }}"
                                           class="btn btn-sm btn-outline-success flex-fill">
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            Report Card
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

@endsection
