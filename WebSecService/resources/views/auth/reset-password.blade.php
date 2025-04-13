@extends('layouts.master')
@section('title', 'Reset Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <h5 class="mb-3">Reset Password</h5>
      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        
        <input type="hidden" name="token" value="{{ request()->get('token') }}">
        <div class="form-group mb-2">
          <label>New Password:</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group mb-2">
          <label>Confirm Password:</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group mb-2">
          <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
