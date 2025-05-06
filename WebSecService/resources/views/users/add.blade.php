@extends('layouts.master')
@section('title', 'Add User')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#clean_permissions").click(function(){
    $('#permissions').val([]);
  });
  $("#clean_roles").click(function(){
    $('#roles').val([]);
  });
});
</script>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add New User</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('users_store')}}" method="post">
                        {{ csrf_field() }}
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{$error}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endforeach

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-user me-2"></i>Name:</label>
                                <input type="text" class="form-control" placeholder="Enter user's full name" name="name" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-envelope me-2"></i>Email:</label>
                                <input type="email" class="form-control" placeholder="Enter user's email address" name="email" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Password:</label>
                                <input type="password" class="form-control" placeholder="Enter password" name="password" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Confirm Password:</label>
                                <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" required>
                            </div>
                        </div>

                        @can('admin_users')
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-user-tag me-2"></i>Roles:</label>
                                <div class="d-flex align-items-center mb-2">
                                    <a href='#' id='clean_roles' class="text-decoration-none">
                                        <i class="fas fa-undo me-1"></i>Reset Selection
                                    </a>
                                </div>
                                <select multiple class="form-select" id='roles' name="roles[]">
                                    @foreach($roles as $role)
                                        <option value='{{$role->name}}'>{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-key me-2"></i>Direct Permissions:</label>
                                <div class="d-flex align-items-center mb-2">
                                    <a href='#' id='clean_permissions' class="text-decoration-none">
                                        <i class="fas fa-undo me-1"></i>Reset Selection
                                    </a>
                                </div>
                                <select multiple class="form-select" id='permissions' name="permissions[]">
                                    @foreach($permissions as $permission)
                                        <option value='{{$permission->name}}'>{{$permission->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endcan

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
