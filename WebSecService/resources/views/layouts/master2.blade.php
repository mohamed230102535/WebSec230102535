<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assignment Portal - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    

</head>
<body>
   <nav class="navbar navbar-expand-lg navbar-dark shadow-lg sticky-top" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container">
    <a class="navbar-brand fw-bold text-white animate__animated animate__pulse animate__infinite" href="{{ route('WebAuthentication.index') }}">
      <i class="fas fa-dragon me-2 text-danger"></i>OneHitPoint
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
    
        <li class="nav-item">
          <a class="nav-link nav-link-glow" href="{{ route('WebAuthentication.products') }}">
            <i class="fas fa-shopping-bag me-1"></i>Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-glow" href="{{ route('WebAuthentication.quiz') }}">
            <i class="fas fa-scroll me-1"></i>Quizs
          </a>
        </li>
      </ul>

      <!-- Login Button -->
      @auth
      <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
          <a href="#" class="text-white text-decoration-none dropdown-toggle user-profile" id="userDropdown" 
             data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-ninja fs-4 me-2 text-danger"></i>
            <span class="badge bg-dark text-warning rounded-pill">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end animate__animated animate__fadeIn" 
              aria-labelledby="userDropdown" style="background: #2c0b0e; border: 1px solid #d4a373;">
            @can("dashboard")
            <li><a class="dropdown-item dropdown-item-cool" href="{{ route('WebAuthentication.dashboard') }}">
              <i class="fas fa-tachometer-alt me-2"></i>User Management</a></li>
            <li><hr class="dropdown-divider bg-danger"></li>
            @endcan
            <li><a class="dropdown-item dropdown-item-cool" href="{{ route('WebAuthentication.userAccount') }}">
              <i class="fas fa-user-cog me-2"></i>My Account</a></li>
            <li><hr class="dropdown-divider bg-danger"></li>
            <li>
              <form action="{{ route('WebAuthentication.doLogout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item dropdown-item-cool text-warning">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
              </form>
            </li>
          </ul>
        </div>  
      </div>
      @else
      <div class="d-flex gap-2">
        <a href="{{ route('WebAuthentication.login') }}" 
           class="btn btn-outline-danger btn-cool px-4">
          <i class="fas fa-sign-in-alt me-2"></i>Login
        </a>
        <a href="{{ route('WebAuthentication.register') }}" 
           class="btn btn-dark btn-cool px-4" style="border-color: #d4a373; color: #d4a373;">
          <i class="fas fa-user-plus me-2"></i>Register
        </a>
      </div>
      @endauth
    </div>
  </div>
</nav>

<!-- Updated styles -->
<style>
.nav-link-glow {
    transition: all 0.3s ease;
    position: relative;
    color: #ffffff !important;
}

.nav-link-glow:hover {
    color: #d4a373 !important; /* Japanese gold */
    transform: translateY(-2px);
}

.nav-link-glow::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -5px;
    left: 0;
    background-color: #d4a373;
    transition: width 0.3s ease;
}

.nav-link-glow:hover::after {
    width: 100%;
}

.btn-cool {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-cool:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 163, 115, 0.3);
}

.user-profile {
    transition: all 0.3s ease;
}

.user-profile:hover {
    transform: scale(1.05);
}

.dropdown-item-cool {
    transition: all 0.3s ease;
    color: #ffffff;
}

.dropdown-item-cool:hover {
    background-color: #d4a373 !important; /* Japanese gold */
    color: #2c0b0e !important;
    padding-left: 25px;
}
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Navbar hover effects
    $('.nav-link-glow').hover(
        function() {
            $(this).addClass('animate__animated animate__pulse');
        },
        function() {
            $(this).removeClass('animate__animated animate__pulse');
        }
    );

    // Button ripple effect with Japanese red
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

    // Dropdown animation
    $('.dropdown').on('show.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(200);
    });

    $('.dropdown').on('hide.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });
});
</script>

<div class="content">
    @yield('content')
   </div>

</body>
</html>

