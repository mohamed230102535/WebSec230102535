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
                    <a class="nav-link" href="{{route('products_list')}}">
                        <i class="fas fa-box"></i> Products
                    </a>
                </li>
                @auth
                    {{-- Permission check removed temporarily --}}
                    @php
                        $cartCount = auth()->user()->cartItems()->sum('quantity');
                        $cartItems = auth()->user()->cartItems()->with('product')->get();
                        $cartTotal = $cartItems->sum('total');
                    @endphp
                    <!-- Include Cart Widget -->
                    @include('cart.widget')
                    
                    {{-- Permission check removed temporarily --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('orders.index')}}">
                            <i class="fas fa-clipboard-list"></i> My Orders
                        </a>
                    </li>
                    
                    {{-- Permission check removed temporarily (Admin/Employee) --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('orders.index')}}">
                            <i class="fas fa-boxes"></i> All Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('orders.dashboard')}}">
                            <i class="fas fa-chart-line"></i> Order Dashboard
                        </a>
                    </li>
                @endauth
                {{-- Permission check removed temporarily --}}
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{route('users')}}">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                @endauth
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
