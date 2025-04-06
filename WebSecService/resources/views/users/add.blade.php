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

<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <form action="{{route('users_store')}}" method="post">
            {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Error!</strong> {{$error}}
            </div>
            @endforeach

            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" placeholder="Name" name="name" required>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            @can('admin_users')
            <div class="col-12 mb-2">
                <label class="form-label">Roles:</label> (<a href='#' id='clean_roles'>reset</a>)
                <select multiple class="form-select" id='roles' name="roles[]">
                    @foreach($roles as $role)
                        <option value='{{$role->name}}'>{{$role->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mb-2">
                <label class="form-label">Direct Permissions:</label> (<a href='#' id='clean_permissions'>reset</a>)
                <select multiple class="form-select" id='permissions' name="permissions[]">
                    @foreach($permissions as $permission)
                        <option value='{{$permission->name}}'>{{$permission->display_name}}</option>
                    @endforeach
                </select>
            </div>
            @endcan

            <button type="submit" class="btn btn-success">Add User</button>
        </form>
    </div>
</div>
@endsection
