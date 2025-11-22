@extends('layouts.app')

@section('title', 'Subjects')
@section('page_title', 'Subjects')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Subjects</h6>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>
            New Subject
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
                        <th style="width: 50px;">#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th style="width: 130px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $index => $subject)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->code }}</td>
                            <td>{{ $subject->department?->name ?? '—' }}</td>
                            <td>
                                @if($subject->is_active)
                                    <span class="badge bg-success-subtle text-success small">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary small">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">

                                {{-- Edit --}}
                                <a href="{{ route('admin.subjects.edit', $subject) }}"
                                   class="btn btn-sm btn-outline-primary me-1"
                                   title="Edit Subject">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.subjects.destroy', $subject) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this subject?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            title="Delete Subject">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center small text-muted py-3">
                                No subjects found. Click "New Subject" to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
