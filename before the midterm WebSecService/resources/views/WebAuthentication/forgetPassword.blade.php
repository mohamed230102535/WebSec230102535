@extends("layouts.master2")
@section("title", "Change Password - OneHitPoint")
@section("content")

<div class="d-flex justify-content-center align-items-center vh-100" style="background-color: #f8f9fa;">
  <div class="card shadow-lg p-4" style="width: 400px; border-radius: 15px;">
    <h3 class="text-center mb-4"><i class="fas fa-key me-2"></i>Reset Password</h3>
    
    @if(session('success'))
    <div class="alert alert-success">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('WebAuthentication.doResetPassword') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
        <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label"><i class="fas fa-lock me-2"></i>New Password</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
      </div>
      <div class="mb-3">
        <label for="new_password_confirmation" class="form-label"><i class="fas fa-lock me-2"></i>Confirm New Password</label>
        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-key me-2"></i>Reset Password
        </button>
      </div>
    </form>
  </div>
</div>
@endsection