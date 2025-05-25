<!-- Cart Widget Dropdown -->
@auth
    {{-- Permission check removed temporarily --}}
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-shopping-cart"></i>
            <span class="badge bg-danger ms-1 cart-count">{{ count($cartItems ?? []) }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="cartDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
            <div class="p-3 border-bottom">
                <h6 class="mb-0">Your Cart ({{ count($cartItems ?? []) }})</h6>
            </div>
            
            @if(count($cartItems ?? []) > 0)
                <div class="p-2">
                    @foreach($cartItems ?? [] as $item)
                        <div class="d-flex align-items-center p-2 border-bottom">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('images/' . $item->product->photo) }}" alt="{{ $item->product->name }}" 
                                     width="50" height="50" class="rounded" 
                                     onerror="this.onerror=null; this.src='https://placehold.co/50x50?text=Product';">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-truncate" style="max-width: 180px;">{{ $item->product->name }}</h6>
                                <small class="text-muted">{{ $item->quantity }} x ${{ number_format($item->product->price, 2) }}</small>
                            </div>
                            <div class="ms-auto">
                                <span class="fw-bold">${{ number_format($item->total, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="p-3 border-bottom bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold">${{ number_format($cartTotal ?? 0, 2) }}</span>
                    </div>
                </div>
                
                <div class="p-3 d-grid gap-2">
                    <a href="{{ route('cart.index') }}" class="btn btn-primary">View Cart</a>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success">Checkout</a>
                </div>
            @else
                <div class="p-4 text-center">
                    <p class="text-muted mb-2">Your cart is empty</p>
                    <a href="{{ route('products_list') }}" class="btn btn-sm btn-primary">Shop Now</a>
                </div>
            @endif
        </div>
    </li>
@endauth
