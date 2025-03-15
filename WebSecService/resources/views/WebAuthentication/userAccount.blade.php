@extends("layouts.master2")
@section("title", "OneHitPoint")
@section("content")
<style>
  .navbar {
    background: linear-gradient(135deg, #1f1c2c, #928dab);
  }
  .card {
    border-radius: 12px;
  }
  .btn-custom {
    background: #6a11cb;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
    transition: 0.3s;
  }
  .btn-custom:hover {
    background: linear-gradient(135deg, #2575fc, #6a11cb);
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">OneHitPoint</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('WebAuthentication.index') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Services</a>
        </li>
      </ul>

      @auth
      <div class="dropdown">
        <a href="#" class="text-light text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
          <i class="fas fa-user-circle fs-4 me-2"></i>
          {{ Auth::user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('WebAuthentication.userAccount') }}"><i class="fas fa-user-cog me-2"></i> My Account</a></li>
          <li><form action="{{ route('WebAuthentication.doLogout') }}" method="POST"> @csrf <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Logout</button></form></li>
        </ul>
      </div>
      @else
      <a href="{{ route('WebAuthentication.login') }}" class="btn btn-outline-light px-4 me-2">Login</a>
      <a href="{{ route('WebAuthentication.register') }}" class="btn btn-custom px-4">Register</a>
      @endauth
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header bg-dark text-white text-center">
          <h4><i class="fas fa-user-circle me-2"></i> My Account</h4>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <tr><th>Name</th><td>{{$user->name}}</td></tr>
            <tr><th>Email</th><td>{{$user->email}}</td></tr>
            <tr>
          <th>Roles</th>
          <td>
              @foreach($user->roles as $role) 
                  <span class="badge bg-primary">{{ $role->name }}</span> 
              @endforeach
          </td>
      </tr>
      <tr>
          <th>Direct Permissions</th>
          @if(auth()->user()->hasRole('user'))
          <td>
              @foreach($user->permissions as $permission) 
                  <span class="badge bg-success">{{ $permission->name }}</span> 
              @endforeach
          </td>
          @else
            <td>
                @foreach($permissions as $permission) 
                    <span class="badge bg-success">{{ $permission->name }}</span> 
                @endforeach
            </td>
          @endif

      </tr>

          </table>

          <div class="mb-4">
            <h5>Update Username</h5>
            <form action="{{ route('WebAuthentication.updateUsername') }}" method="POST">
              @csrf
              <input type="text" class="form-control" name="new_username" placeholder="New Username" required>
              <button type="submit" class="btn btn-custom mt-2">Update</button>
            </form>
          </div>

          <div class="mb-4">
            <h5>Change Password</h5>
            <form action="{{ route('WebAuthentication.updatePassword') }}" method="POST">
              @csrf
              <input type="password" class="form-control" name="current_password" placeholder="Current Password" required>
              <input type="password" class="form-control mt-2" name="new_password" placeholder="New Password" required>
              <button type="submit" class="btn btn-custom mt-2">Change</button>
            </form>
          </div>

          <div>
            <h5>Forgot Password?</h5>
            <form action="{{ route('WebAuthentication.forgotPassword') }}" method="get">
              <button type="submit" class="btn btn-warning">Reset Password</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
