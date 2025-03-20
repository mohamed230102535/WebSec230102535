@extends("layouts.master2")
@section("title", "User Management - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-users me-2"></i>User Management
    </h1>
    <p class="lead" style="color: #ffffff;">
      Manage Your OneHitPoint Community
    </p>
  </div>
</section>

<!-- Search Form -->
<section class="py-4" style="background: #2c0b0e;">
  <div class="container">
    <form method="GET" action="{{ route('WebAuthentication.dashboard') }}" class="row g-3 align-items-center justify-content-center">
      <div class="col-sm-6 col-md-4">
        <input name="keywords" type="text" class="form-control bg-dark text-white border-danger" 
               placeholder="Search by Name or Email" value="{{ request()->keywords }}">
      </div>
      <div class="col-sm-2 col-md-1">
        <button type="submit" class="btn btn-danger btn-cool w-100">
          <i class="fas fa-search me-1"></i>Search
        </button>
      </div>
    </form>
  </div>
</section>

<!-- Users Table Section -->
<section class="py-5 flex-grow-1" style="background: #1a1a1a; min-height: calc(100vh - 300px);">
  <div class="container">
    <div class="card bg-dark text-white border-0 shadow-lg animate__animated animate__fadeIn">
      <div class="card-header d-flex justify-content-between align-items-center" style="background: #2c0b0e; border-bottom: 2px solid #d4a373;">
        <h5 class="mb-0" style="color: #d4a373;">User List</h5>
        @can('createUser')
          <a href="{{ route('WebAuthentication.createUser') }}" class="btn btn-warning btn-cool btn-sm">
            <i class="fas fa-plus me-1"></i>Add New User
          </a>
        @endcan
      </div>
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" style="background: #2c0b0e; border-color: #d4a373; color: #ffffff;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-hover table-dark">
            <thead>
              <tr style="background: #4a1a1e;">
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr class="user-row">
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    @can('editUser')
                      <a href="{{ route('WebAuthentication.editUser', $user->id) }}" class="btn btn-sm btn-outline-warning btn-cool me-1" title="Edit User">
                        <i class="fas fa-edit"></i>
                      </a>
                    @endcan
                    @can('deleteUser')
                      <a href="{{ route('WebAuthentication.deleteUser', $user->id) }}" class="btn btn-sm btn-outline-danger btn-cool" 
                         onclick="return confirm('Are you sure you want to delete {{ $user->name }}?');" title="Delete User">
                        <i class="fas fa-trash"></i>
                      </a>
                    @endcan
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">No users found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
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

.table th {
  color: #d4a373;
  border-bottom: 1px solid #d71818;
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

.btn-outline-danger {
  border-color: #d71818;
  color: #d71818;
}

.btn-outline-danger:hover {
  background-color: #d71818;
  color: #ffffff;
}

.user-row {
  transition: all 0.3s ease;
}

.user-row:hover {
  background-color: #3a3a3a;
  transform: translateY(-2px);
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

  // Fade in animation on scroll
  $(window).scroll(function() {
    $('.user-row').each(function() {
      let top_of_element = $(this).offset().top;
      let bottom_of_window = $(window).scrollTop() + $(window).height();
      
      if (bottom_of_window > top_of_element) {
        $(this).addClass('animate__animated animate__fadeInUp');
      }
    });
  });
});
</script>
@endsection

@endsection