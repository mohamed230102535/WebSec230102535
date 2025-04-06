@extends("layouts.master2")
@section("title", "My Account - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-user-circle me-2"></i>My Account
    </h1>
    <p class="lead" style="color: #ffffff;">
      Manage Your OneHitPoint Profile
    </p>
  </div>
</section>

<!-- Account Details Section -->
<section class="py-5" style="background: #1a1a1a; min-height: calc(100vh - 200px);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card bg-dark text-white border-0 shadow-lg animate__animated animate__fadeIn">
          <div class="card-header" style="background: #2c0b0e; border-bottom: 2px solid #d4a373;">
            <h4 class="text-center mb-0" style="color: #d4a373;">
              <i class="fas fa-user-shield me-2"></i>Account Details
            </h4>
          </div>
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" style="background: #2c0b0e; border-color: #d4a373; color: #ffffff;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <!-- User Info Table -->
            <table class="table table-dark table-hover">
              <tbody>
                <tr>
                  <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Name</th>
                  <td>{{ $user->name }}</td>
                </tr>
                <tr>
                  <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Email</th>
                  <td>{{ $user->email }}</td>
                </tr>
                <tr>
                  <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Roles</th>
                  <td>
                    @foreach($user->roles as $role)
                      <span class="badge" style="background: #d71818; color: #ffffff;">{{ $role->name }}</span>
                    @endforeach
                  </td>
                </tr>
                <tr>
                  <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Direct Permissions</th>
                  <td>
                    @if(auth()->user()->hasRole('user'))
                      @foreach($user->permissions as $permission)
                        <span class="badge" style="background: #d4a373; color: #2c0b0e;">{{ $permission->name }}</span>
                      @endforeach
                    @else
                      @foreach($permissions as $permission)
                        <span class="badge" style="background: #d4a373; color: #2c0b0e;">{{ $permission->name }}</span>
                      @endforeach
                    @endif
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Update Username Form -->
            <div class="mb-4">
              <h5 style="color: #d4a373;">Update Username</h5>
              <form action="{{ route('WebAuthentication.updateUsername') }}" method="POST">
                @csrf
                <input type="text" class="form-control bg-dark text-white border-danger" name="new_username" 
                       placeholder="New Username" value="{{ old('new_username') }}" required>
                @error('new_username')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                <button type="submit" class="btn btn-danger btn-cool mt-2">
                  <i class="fas fa-save me-2"></i>Update
                </button>
              </form>
            </div>

            <!-- Change Password Form -->
            <div class="mb-4">
              <h5 style="color: #d4a373;">Change Password</h5>
              <form action="{{ route('WebAuthentication.updatePassword') }}" method="POST">
                @csrf
                <input type="password" class="form-control bg-dark text-white border-danger" name="current_password" 
                       placeholder="Current Password" required>
                @error('current_password')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                <input type="password" class="form-control bg-dark text-white border-danger mt-2" name="new_password" 
                       placeholder="New Password" required>
                @error('new_password')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                <button type="submit" class="btn btn-danger btn-cool mt-2">
                  <i class="fas fa-lock me-2"></i>Change
                </button>
              </form>
            </div>

            <!-- Forgot Password -->
            <div>
              <h5 style="color: #d4a373;">Forgot Password?</h5>
              <form action="{{ route('WebAuthentication.forgotPassword') }}" method="GET">
                <button type="submit" class="btn btn-outline-warning btn-cool">
                  <i class="fas fa-key me-2"></i>Reset Password
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Custom Styles -->
<style>
.card {
  border: 1px solid #d4a373;
}

.table-dark {
  --bs-table-bg: #2c2c2c;
  --bs-table-hover-bg: #3a3a3a;
}

.table td {
  color: #ffffff;
  vertical-align: middle;
}

.form-control, .form-control:focus {
  background-color: #2c2c2c;
  border-color: #d71818;
  color: #ffffff;
  transition: all 0.3s ease;
}

.form-control:focus {
  box-shadow: 0 0 10px rgba(215, 24, 24, 0.5);
}

.btn-cool {
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.btn-cool:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(212, 163, 115, 0.3);
  color: #ffffff !important;
}

.btn-outline-warning {
  border-color: #d4a373;
  color: #d4a373;
}

.btn-outline-warning:hover {
  background-color: #d4a373;
  color: #2c0b0e;
}

.btn-danger {
  background-color: #d71818;
  border-color: #d71818;
}

.btn-danger:hover {
  background-color: #b31414;
  border-color: #b31414;
}
</style>

<!-- Scripts -->
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Button ripple effect
  $('.btn-cool').on('click', function(e) {
    let x = e.pageX - $(this).offset().left;
    let y = e.pageY - $(this).offset().top;
    
    let ripple = $('<span/>');
    ripple
      .css({
        left: x,
        top: y,
        position: 'absolute',
        background: 'rgba(215, 24, 24, 0.2)', /* Japanese red */
        'border-radius': '50%',
        transform: 'scale(0)',
        'pointer-events': 'none'
      })
      .animate({
        scale: 2,
        opacity: 0
      }, 500, function() {
        $(this).remove();
      });
      
    $(this).append(ripple);
  });
});
</script>
@endsection

@endsection