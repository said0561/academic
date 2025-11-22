@extends('layouts.app')

@section('title', 'Import Students')
@section('page_title', 'Bulk Upload Students')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h6 class="fw-bold mb-3">Bulk Upload Students by Class</h6>
                <a href="{{ route('admin.students.import.template') }}"
                class="btn btn-outline-secondary btn-sm">
                  Download CSV Template
              </a>

            @if ($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success small">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('admin.students.import.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="small">

                @csrf

                {{-- Class --}}
                <div class="mb-3">
                    <label for="class_id" class="form-label fw-semibold">Class</label>
                    <select name="class_id" id="class_id"
                            class="form-select form-select-sm @error('class_id') is-invalid @enderror"
                            required>
                        <option value="">-- Select class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }} {{ $class->stream }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- File --}}
                <div class="mb-3">
                    <label for="file" class="form-label fw-semibold">CSV File</label>
                    <input type="file"
                           name="file"
                           id="file"
                           accept=".csv,text/csv"
                           class="form-control form-control-sm @error('file') is-invalid @enderror"
                           required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror>
                    <div class="form-text">
                        Upload a CSV file with the following columns (first row as header):<br>
                        <code>first_name,middle_name,last_name,gender,dob</code><br>
                        Example:<br>
                        <code>Ali,Hassan,Omar,M,2015-03-12</code><br>
                        <code>Asha,,Said,F,2014-11-05</code>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.students.index') }}"
                       class="btn btn-outline-secondary btn-sm">
                        &larr; Back to Students
                    </a>

                    <button type="submit" class="btn btn-primary btn-sm">
                        Upload &amp; Import
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
