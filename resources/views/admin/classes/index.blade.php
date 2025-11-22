@extends('layouts.app')

@section('title', 'Classes')
@section('page_title', 'Classes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Classes</h6>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>
            New Class
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success py-2 small">
            {{ session('status') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Class Name</th>
                        <th>Stream</th>
                        <th style="width: 160px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $index => $class)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->stream ?? '—' }}</td>
                            <td class="text-end">

                                {{-- Edit class --}}
                                <a href="{{ route('admin.classes.edit', $class) }}"
                                   class="btn btn-sm btn-outline-primary me-1"
                                   title="Edit Class">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Subjects & Teachers assignment --}}
                                <a href="{{ route('admin.classes.subjects.edit', $class) }}"
                                   class="btn btn-sm btn-outline-info me-1"
                                   title="Subjects & Teachers">
                                    <i class="bi bi-journal-bookmark"></i>
                                </a>

                                {{-- Class Exam Report --}}
                                <a href="{{ route('admin.classes.exam-report', $class->id) }}"
                                   class="btn btn-sm btn-outline-success me-1"
                                   title="Exam Report">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.classes.destroy', $class) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this class?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete Class">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center small text-muted py-3">
                                No classes found. Click "New Class" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
