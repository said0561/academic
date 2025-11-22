@extends('layouts.app')

@section('title', 'New Exam')
@section('page_title', 'Create Exam')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger small">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body small">

        <form method="POST" action="{{ route('admin.exams.store') }}">
            @csrf

            <div class="mb-2">
                <label class="form-label">Exam Name</label>
                <input type="text" name="name" class="form-control form-control-sm"
                       required placeholder="Mid Term, End Term, Weekly Test...">
            </div>

            <div class="mb-2">
                <label class="form-label">Term</label>
                <select name="term" class="form-select form-select-sm" required>
                    <option value="">Select term</option>
                    <option value="1">Term 1</option>
                    <option value="2">Term 2</option>
                    <option value="3">Term 3</option>
                </select>
            </div>

            <div class="mb-2">
                <label class="form-label">Year</label>
                <input type="number" name="year" class="form-control form-control-sm"
                       required min="2000" max="2100" value="{{ date('Y') }}">
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Save Exam</button>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary btn-sm">Cancel</a>

        </form>

    </div>
</div>

@endsection
