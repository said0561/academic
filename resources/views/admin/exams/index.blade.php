@extends('layouts.app')

@section('title', 'Exams')
@section('page_title', 'Exams')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h6 class="fw-bold mb-0">All Exams</h6>

    <a href="{{ route('admin.exams.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>
        New Exam
    </a>
</div>

@if(session('status'))
    <div class="alert alert-success small py-2">
        {{ session('status') }}
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Term</th>
                    <th>Year</th>
                    <th style="width: 120px;" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                <tr>
                    <td>{{ $exam->name }}</td>
                    <td>{{ $exam->term }}</td>
                    <td>{{ $exam->year }}</td>

                    <td class="text-end">

                        {{-- Edit --}}
                        <a href="{{ route('admin.exams.edit', $exam) }}"
                           class="btn btn-sm btn-outline-primary me-1"
                           title="Edit Exam">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.exams.destroy', $exam) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete this exam?');">
                            @csrf 
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    title="Delete Exam">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted small py-2">
                        No exams found. Click "New Exam" to create one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-2">
    {{ $exams->links() }}
</div>

@endsection
