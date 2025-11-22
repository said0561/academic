@extends('layouts.app')

@section('title', 'Users Management')
@section('page_title', 'Users Management')

@section('content')

    {{-- Header --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center py-2">
            <h5 class="fw-bold mb-0">Users by Roles</h5>

            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus-fill me-1"></i>
                Add New User
            </a>
        </div>
    </div>

    {{-- ===================== ADMINS ===================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold py-2 d-flex align-items-center">
            <i class="bi bi-shield-lock-fill me-2 text-primary"></i>
            Admins
        </div>

        <div class="card-body p-0">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 120px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted small py-2">
                                No admins found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-2">
            {{ $admins->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>


    {{-- ===================== TEACHERS ===================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold py-2 d-flex align-items-center">
            <i class="bi bi-person-badge-fill me-2 text-info"></i>
            Teachers
        </div>

        <div class="card-body p-0">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 120px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Edit Teacher">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted small py-2">
                                No teachers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-2">
            {{ $teachers->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>


    {{-- ===================== PARENTS ===================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold py-2 d-flex align-items-center">
            <i class="bi bi-people-fill me-2 text-success"></i>
            Parents
        </div>

        <div class="card-body p-0">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th style="width: 120px;" class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($parents as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Edit Parent">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted small py-2">
                                No parents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="p-2">
            {{ $parents->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>


    {{-- ===================== ACADEMIC OFFICERS ===================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold py-2 d-flex align-items-center">
            <i class="bi bi-mortarboard-fill me-2 text-warning"></i>
            Academic Officers
        </div>

        <div class="card-body p-0">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 120px;" class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($academic as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Edit Academic Officer">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted small py-2">
                                No academic users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="p-2">
            {{ $academic->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection
