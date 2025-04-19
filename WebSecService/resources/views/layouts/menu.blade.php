<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="./">MyApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./even">Even Numbers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./prime">Prime Numbers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./multable">Multiplication Table</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('products_list')}}">Products</a>
                </li>
                @can('show_users')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('users')}}">Users</a>
                </li>
                @endcan
            </ul>
            <ul class="navbar-nav">
                @auth
                <li class="nav-item">
                    <span class="nav-link">Credit: ${{auth()->user()->credit}}</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile')}}">{{auth()->user()->name}}</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-danger btn-sm" href="{{route('logout')}}">Logout</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="btn btn-outline-primary btn-sm me-2" href="{{route('login')}}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary btn-sm" href="{{route('register')}}">Register</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
