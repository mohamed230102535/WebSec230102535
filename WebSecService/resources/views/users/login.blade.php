@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
       <div class="mb-4">
        <a href="{{ route('connect') }}" class="btn btn-outline-primary w-100 py-2">
          <img src="https://img.icons8.com/color/24/000000/microsoft.png" class="me-2">
          Continue with Microsoft
        </a>
      </div>
      <form action="{{ route('do_login') }}" method="post">
        {{ csrf_field() }}


     {{-- Success Message --}}
      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif

        <div class="form-group">
          @foreach($errors->all() as $error)
          <div class="alert alert-danger">
            <strong>Error!</strong> {{ $error }}
          </div>
          @endforeach
        </div>
        <div class="form-group mb-2">
          <label>Email:</label>
          <input type="email" class="form-control" placeholder="email" name="email" required>
        </div>
        <div class="form-group mb-2">
          <label>Password:</label>
          <input type="password" class="form-control" placeholder="password" name="password" required>
        </div>

        
        <div class="mb-2">
          <a href="{{ route('password.request') }}">Forgot Password?</a>
        </div>

        <div class="form-group mb-2">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
