@extends('layouts.master')
@section('title', 'Forgot Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <h5 class="mb-3">Forgot Password</h5>

      {{-- Success Message --}}
      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif

      {{-- Validation Errors --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.reset') }}">
        @csrf
        <div class="form-group mb-2">
          <label>Enter your email:</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
          @error('email')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="form-group mb-2">
          <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
