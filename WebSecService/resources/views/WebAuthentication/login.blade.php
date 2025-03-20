@extends("layouts.master2")
@section("title", "Login - OneHitPoint")
@section("content")

<div class="d-flex justify-content-center align-items-center vh-100" style="background-color: #f8f9fa;">
    <div class="card shadow-lg p-4" style="width: 350px; border-radius: 15px;">
        <h3 class="text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Login</h3>
        <form action="{{ route('WebAuthentication.doLogin') }}" method="POST">
        {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-circle me-2"></i>{{$error}}
            </div>
            @endforeach
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </div>
        </form>
        <div class="mt-4 text-center">
            <p class="text-muted">Don't have an account?</p>
            <a href="{{ route('WebAuthentication.register') }}" class="btn btn-outline-secondary">
              <i class="fas fa-user-plus me-2"></i>Register
            </a>
        </div>
    </div>
</div>
@endsection