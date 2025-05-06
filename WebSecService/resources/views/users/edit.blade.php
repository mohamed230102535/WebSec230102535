@extends('layouts.master')
@section('title', 'Edit User')
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
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit User: {{$user->name}}</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('users_save', $user->id)}}" method="post">
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
                                <input type="text" class="form-control" placeholder="Enter user's full name" name="name" required value="{{$user->name}}">
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
                                    <option value='{{$role->name}}' {{$role->taken?'selected':''}}>{{$role->name}}</option>
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
                                        <option value='{{$permission->name}}' {{$permission->taken?'selected':''}}>{{$permission->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endcan

                        @can('charge_customer_credit')
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-credit-card me-2"></i>Charge Credit:</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="charge_credit" name="charge_credit" placeholder="Amount to charge" min="0" required>
                                </div>
                            </div>
                        </div>
                        @endcan

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
