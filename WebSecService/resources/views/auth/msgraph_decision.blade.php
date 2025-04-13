@extends('layouts.master')
@section('title', 'Microsoft Sign-In')
@section('content')

<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <h4 class="text-center mb-4">Microsoft Sign-In</h4>
      <p class="text-center">Welcome {{ session('msgraph_user')['displayName'] ?? 'User' }}!</p>
      <p class="text-muted text-center">Would you like to log in or register?</p>

      <div class="d-grid gap-2">
        <form method="POST" action="{{ route('msgraph.login') }}">
          @csrf
          <button class="btn btn-success w-100">Log In</button>
        </form>

        <form method="POST" action="{{ route('msgraph.register') }}">
          @csrf
          <button class="btn btn-secondary w-100">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
