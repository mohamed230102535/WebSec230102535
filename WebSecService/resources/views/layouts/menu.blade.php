<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="./">
            <i class="fas fa-shield-alt"></i> WebSec
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./even">
                        <i class="fas fa-calculator"></i> Even Numbers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./prime">
                        <i class="fas fa-hashtag"></i> Prime Numbers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./multable">
                        <i class="fas fa-table"></i> Multiplication Table
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('cryptography')}}">
                        <i class="fas fa-lock"></i> Cryptography
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('products_list')}}">
                        <i class="fas fa-box"></i> Products
                    </a>
                </li>
                @can('show_users')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('users')}}">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                @endcan
            </ul>
            <ul class="navbar-nav">
                @auth
                <li class="nav-item">
                    <span class="nav-link">
                        <i class="fas fa-wallet"></i> Credit: ${{number_format(auth()->user()->credit, 2)}}
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile')}}">
                        <i class="fas fa-user"></i> {{auth()->user()->name}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('login')}}">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('register')}}">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
