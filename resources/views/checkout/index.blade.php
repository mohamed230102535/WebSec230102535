@extends('layouts.master')

@section('title', 'Checkout')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Checkout</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    
                    <!-- Delivery Method -->
                    <div class="mb-4">
                        <h6 class="mb-3">Delivery Method</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="delivery_method" id="home_delivery" value="home_delivery" checked>
                            <label class="form-check-label" for="home_delivery">
                                Home Delivery
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_method" id="store_pickup" value="store_pickup">
                            <label class="form-check-label" for="store_pickup">
                                Store Pickup
                            </label>
                        </div>
                    </div>
                    
                    <!-- Shipping Address (only for home delivery) -->
                    <div class="mb-4" id="shipping_address_container">
                        <h6 class="mb-3">Shipping Address</h6>
                        <div class="mb-3">
                            <textarea name="shipping_address" id="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror" placeholder="Enter your complete shipping address">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="mb-4">
                        <h6 class="mb-3">Payment Method</h6>
                        
                        <!-- Credit System -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="credit_system" value="credit_system" checked>
                            <label class="form-check-label" for="credit_system">
                                Credit System (Available: ${{ number_format(auth()->user()->credit, 2) }})
                            </label>
                        </div>
                        
                        <!-- Credit Card -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card">
                            <label class="form-check-label" for="credit_card">
                                Credit Card
                            </label>
                        </div>
                        
                        <!-- PayPal -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                PayPal
                            </label>
                        </div>
                    </div>
                    
                    <!-- Credit Card Form (conditionally displayed) -->
                    <div id="credit_card_form" class="mb-4 d-none">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">Credit Card Details</h6>
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="expiry" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="expiry" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" placeholder="123">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="card_name" class="form-label">Name on Card</label>
                                    <input type="text" class="form-control" id="card_name" placeholder="John Doe">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- PayPal Button (conditionally displayed) -->
                    <div id="paypal_form" class="mb-4 d-none">
                        <div class="card">
                            <div class="card-body text-center py-4">
                                <button type="button" class="btn btn-primary" disabled>
                                    <i class="fab fa-paypal me-2"></i> Pay with PayPal
                                </button>
                                <p class="text-muted mt-2 mb-0">
                                    <small>Click 'Place Order' to continue</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back to Cart
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Place Order <i class="fa fa-check ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal ({{ count($cartItems) }} items)</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total</strong>
                    <strong>${{ number_format($total, 2) }}</strong>
                </div>
                
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Items in Cart</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($cartItems as $item)
                        <li class="list-group-item py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Toggle shipping address based on delivery method
    document.addEventListener('DOMContentLoaded', function() {
        const homeDeliveryRadio = document.getElementById('home_delivery');
        const storePickupRadio = document.getElementById('store_pickup');
        const shippingAddressContainer = document.getElementById('shipping_address_container');
        
        function toggleShippingAddress() {
            if (homeDeliveryRadio.checked) {
                shippingAddressContainer.style.display = 'block';
            } else {
                shippingAddressContainer.style.display = 'none';
            }
        }
        
        homeDeliveryRadio.addEventListener('change', toggleShippingAddress);
        storePickupRadio.addEventListener('change', toggleShippingAddress);
        
        // Payment method toggle
        const creditSystemRadio = document.getElementById('credit_system');
        const creditCardRadio = document.getElementById('credit_card');
        const paypalRadio = document.getElementById('paypal');
        const creditCardForm = document.getElementById('credit_card_form');
        const paypalForm = document.getElementById('paypal_form');
        
        function togglePaymentForms() {
            creditCardForm.classList.add('d-none');
            paypalForm.classList.add('d-none');
            
            if (creditCardRadio.checked) {
                creditCardForm.classList.remove('d-none');
            } else if (paypalRadio.checked) {
                paypalForm.classList.remove('d-none');
            }
        }
        
        creditSystemRadio.addEventListener('change', togglePaymentForms);
        creditCardRadio.addEventListener('change', togglePaymentForms);
        paypalRadio.addEventListener('change', togglePaymentForms);
        
        // Initialize forms
        toggleShippingAddress();
        togglePaymentForms();
    });
</script>
@endsection
