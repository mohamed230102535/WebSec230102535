@extends('layouts.master')
@section('title', 'Users')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-users me-2"></i>Users Management</h4>
            @can('admin_users')
            <a class="btn btn-light" href="{{ route('users_create') }}">
                <i class="fas fa-user-plus me-2"></i>Add User
            </a>
            @endcan
        </div>
        <div class="card-body">
            <form class="mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input name="keywords" type="text" class="form-control" placeholder="Search users..." value="{{ request()->keywords }}" />
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Roles</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle fa-lg text-primary me-2"></i>
                                    {{$user->name}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope fa-sm text-muted me-2"></i>
                                    {{$user->email}}
                                </div>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary me-1">{{$role->name}}</span>
                                @endforeach
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    @can('edit_users')
                                    <a class="btn btn-sm btn-outline-primary" href='{{route('users_edit', [$user->id])}}' title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('admin_users')
                                    <a class="btn btn-sm btn-outline-info" href='{{route('edit_password', [$user->id])}}' title="Change Password">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    @endcan
                                    @can('delete_users')
                                    <a class="btn btn-sm btn-outline-danger" href='{{route('users_delete', [$user->id])}}' title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
