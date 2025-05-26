@extends('layouts.master')

@section('title', 'Checkout')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<!-- Font Awesome for improved icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
      crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Store location container styling */
    .store-info-container {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border-radius: 0.25rem;
    }
    .store-info-container:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Map styling */
    #store-map {
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }
    
    /* Small map styling - improved for reliability */
    .small-map {
        height: 200px;
        width: 100%;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
        position: relative;
        z-index: 1;
        background-color: #f8f9fa;
    }
    
    .map-container {
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* Fallback styling in case map doesn't load */
    .map-fallback {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        z-index: 0;
    }
    
    /* Map marker popup styling */
    .map-marker-info {
        padding: 12px;
        max-width: 220px;
        line-height: 1.6;
    }
    
    /* Store address styling */
    .store-address {
        line-height: 1.7;
        color: #333;
    }
    
    /* Store badge and icon styling */
    .store-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.65rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 0.5rem;
    }
    
    .badge-icon {
        margin-right: 5px;
    }
    
    .store-hours {
        border-left: 3px solid #28a745;
        padding-left: 10px;
        margin: 15px 0;
    }
    
    .directions-btn {
        transition: all 0.2s ease;
    }
    
    .directions-btn:hover {
        transform: translateY(-2px);
    }
    
    /* Responsive styling */
    @media (max-width: 767px) {
        #store-map {
            height: 280px !important;
            border-radius: 0 0 0.25rem 0.25rem;
        }
        
        .store-info-section {
            padding: 1.25rem !important;
        }
    }
    
    /* Payment method styling */
    .payment-method-option {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }
    
    .payment-method-option:hover,
    .payment-method-option.selected {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.04);
    }
    
    .delivery-method-option {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .delivery-method-option:hover,
    .delivery-method-option.selected {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.04);
    }
</style>
@endsection

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
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="delivery-method-option selected" id="home_delivery_container">
                                    <div class="d-flex align-items-start">
                                        <input class="form-check-input mt-1 me-2" type="radio" name="delivery_method" id="home_delivery" value="home_delivery" checked>
                                        <div>
                                            <label class="form-check-label fw-bold" for="home_delivery">
                                                Home Delivery
                                            </label>
                                            <p class="text-muted small mb-0 mt-1">We'll deliver to your doorstep</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="delivery-method-option" id="store_pickup_container">
                                    <div class="d-flex align-items-start">
                                        <input class="form-check-input mt-1 me-2" type="radio" name="delivery_method" id="store_pickup" value="store_pickup">
                                        <div>
                                            <label class="form-check-label fw-bold" for="store_pickup">
                                                Store Pickup
                                            </label>
                                            <p class="text-muted small mb-0 mt-1">Pick up at our store location</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Store Location (only for store pickup) -->
                    <div class="mb-4 d-none" id="store_location_container">
                        <h6 class="mb-3">Store Pickup Location</h6>
                        <div class="card store-info-container">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="store-info-section">
                                            <h5 class="text-primary mb-3">
                                                <i class="fas fa-building me-2"></i> WebSec Service Headquarters
                                            </h5>
                                            <div class="d-flex mb-3">
                                                <i class="fas fa-map-marker-alt me-2 mt-1 text-danger"></i>
                                                <div>
                                                    <strong>Crystal Tech Tower</strong>, Suite #405<br>
                                                    1250 Innovation Avenue<br>
                                                    Silicon Valley, CA 94043
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-phone-alt me-2 text-primary"></i>
                                                <div>(555) 123-4567</div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-envelope me-2 text-primary"></i>
                                                <div>store@websecservice.com</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <span class="badge bg-success me-2">
                                                    <i class="fas fa-clock me-1"></i> Open Today
                                                </span>
                                                <span class="text-muted">9:00 AM - 6:00 PM</span>
                                            </div>
                                            
                                            <div class="alert alert-info p-2 small mb-0">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Please bring your ID for order pickup
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Small map container with fallback -->
                                        <div class="map-container">
                                            <div id="store-map" class="small-map">
                                                <!-- Fallback content if map fails to load -->
                                                <div class="map-fallback" id="map-fallback">
                                                    <i class="fas fa-map-marked-alt fa-2x text-muted mb-2"></i>
                                                    <p class="text-muted small mb-0">Map Loading...</p>
                                                </div>
                                            </div>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=1250+Innovation+Avenue,+Silicon+Valley,+CA+94043" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary mt-2 w-100" 
                                               id="get-directions-btn">
                                                <i class="fas fa-directions me-1"></i> Get Directions
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address (only for home delivery) -->
                    <div class="mb-4" id="shipping_address_container">
                        <h6 class="mb-3">Shipping Information</h6>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}">
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="(123) 456-7890" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <hr class="my-3">
                                <h6 class="mb-3">Delivery Address</h6>
                                
                                <div class="mb-3">
                                    <label for="street_address" class="form-label">Street Address</label>
                                    <input type="text" name="street_address" id="street_address" class="form-control @error('street_address') is-invalid @enderror" placeholder="1234 Main St" value="{{ old('street_address') }}">
                                    @error('street_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address_line2" class="form-label">Apartment, Suite, etc. (optional)</label>
                                    <input type="text" name="address_line2" id="address_line2" class="form-control @error('address_line2') is-invalid @enderror" placeholder="Apartment or suite" value="{{ old('address_line2') }}">
                                    @error('address_line2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-5">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="state" class="form-label">State</label>
                                        <select name="state" id="state" class="form-select @error('state') is-invalid @enderror">
                                            <option value="">Choose...</option>
                                            <option value="AL" {{ old('state') == 'AL' ? 'selected' : '' }}>Alabama</option>
                                            <option value="AK" {{ old('state') == 'AK' ? 'selected' : '' }}>Alaska</option>
                                            <option value="AZ" {{ old('state') == 'AZ' ? 'selected' : '' }}>Arizona</option>
                                            <option value="CA" {{ old('state') == 'CA' ? 'selected' : '' }}>California</option>
                                            <!-- Add more states as needed -->
                                        </select>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="zip" class="form-label">Zip</label>
                                        <input type="text" name="zip" id="zip" class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip') }}">
                                        @error('zip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="delivery_notes" class="form-label">Delivery Notes (optional)</label>
                                    <textarea name="delivery_notes" id="delivery_notes" rows="2" class="form-control @error('delivery_notes') is-invalid @enderror" placeholder="Special instructions for delivery">{{ old('delivery_notes') }}</textarea>
                                    @error('delivery_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>
<script>
    // Enhanced checkout page functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Delivery method elements
        const homeDeliveryRadio = document.getElementById('home_delivery');
        const storePickupRadio = document.getElementById('store_pickup');
        const homeDeliveryContainer = document.getElementById('home_delivery_container');
        const storePickupContainer = document.getElementById('store_pickup_container');
        const shippingAddressContainer = document.getElementById('shipping_address_container');
        const storeLocationContainer = document.getElementById('store_location_container');
        
        // Map control elements
        const zoomInBtn = document.getElementById('zoom-in-btn');
        const zoomOutBtn = document.getElementById('zoom-out-btn');
        const resetMapBtn = document.getElementById('reset-map-btn');
        const getDirectionsBtn = document.getElementById('get-directions-btn');
        
        // Map initialization variables
        let storeMap;
        let storeMarker;
        let mapInitialized = false;
        const storeLocation = [37.387474, -122.057543]; // Silicon Valley coordinates (example)
        const defaultZoom = 15;
        
        /**
         * Initialize the Leaflet map with store location - with error handling
         */
        function initializeMap() {
            // Don't reinitialize if already done
            if (mapInitialized) return;
            
            // Hide the fallback as we attempt to load the map
            const fallback = document.getElementById('map-fallback');
            if (fallback) fallback.style.display = 'none';
            
            try {
                // Initialize map with custom options for small map
                storeMap = L.map('store-map', {
                    center: storeLocation,
                    zoom: 13, // Slightly smaller zoom for better context in small map
                    zoomControl: true, // Show default zoom controls
                    scrollWheelZoom: false, // Prevent accidental zooming on page scroll
                    dragging: true,
                    tap: true,
                    attributionControl: false // Hide attribution on small map
                });
                
                // Add OpenStreetMap tile layer with error handling
                const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    errorTileUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png'
                }).addTo(storeMap);
                
                // Create a custom icon for the marker
                const storeIcon = L.icon({
                    iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                });
                
                // Add marker for store location
                storeMarker = L.marker(storeLocation, {
                    icon: storeIcon,
                    title: 'WebSec Service Headquarters'
                }).addTo(storeMap);
                
                // Add a simplified popup for the smaller map
                const popupContent = `
                    <div class="map-marker-info">
                        <strong>WebSec Service HQ</strong><br>
                        1250 Innovation Avenue<br>
                        Silicon Valley, CA
                    </div>
                `;
                
                storeMarker.bindPopup(popupContent);
                
                // Show the popup immediately to make the marker more noticeable
                setTimeout(() => {
                    storeMarker.openPopup();
                }, 500);
                
                // Refresh map when container becomes visible
                storeMap.invalidateSize();
                mapInitialized = true;
                
                // Map loaded successfully - hide the fallback completely
                if (fallback) fallback.style.display = 'none';
                
            } catch (error) {
                console.error('Error initializing map:', error);
                // Show the fallback content if map fails to initialize
                if (fallback) {
                    fallback.style.display = 'flex';
                    fallback.querySelector('p').textContent = 'Map unavailable. Please use the Get Directions button below.';
                }
            }
        }
        
        /**
         * Toggle between delivery methods and update UI
         */
        function toggleDeliveryMethod() {
            // Update container styling
            if (homeDeliveryRadio.checked) {
                homeDeliveryContainer.classList.add('selected');
                storePickupContainer.classList.remove('selected');
                shippingAddressContainer.classList.remove('d-none');
                storeLocationContainer.classList.add('d-none');
                
                // Make shipping fields required when home delivery is selected
                const requiredFields = ['first_name', 'last_name', 'phone', 'email', 'street_address', 'city', 'state', 'zip'];
                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.setAttribute('required', 'required');
                    }
                });
            } else {
                homeDeliveryContainer.classList.remove('selected');
                storePickupContainer.classList.add('selected');
                shippingAddressContainer.classList.add('d-none');
                storeLocationContainer.classList.remove('d-none');
                
                // Make shipping fields not required when store pickup is selected
                const requiredFields = ['first_name', 'last_name', 'phone', 'email', 'street_address', 'city', 'state', 'zip'];
                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.removeAttribute('required');
                    }
                });
                
                // Initialize map when store pickup is selected with a slight delay
                // to ensure the container is visible
                setTimeout(() => {
                    try {
                        initializeMap();
                        if (storeMap) {
                            storeMap.invalidateSize();
                            // Open popup after map is fully loaded
                            setTimeout(() => {
                                if (storeMarker) storeMarker.openPopup();
                            }, 300);
                        }
                    } catch (err) {
                        console.error('Error in map display:', err);
                        // Show fallback if map fails
                        const fallback = document.getElementById('map-fallback');
                        if (fallback) fallback.style.display = 'flex';
                    }
                }, 100);
            }
        }
        
        // Add click event listeners to delivery method containers
        homeDeliveryContainer.addEventListener('click', function() {
            homeDeliveryRadio.checked = true;
            toggleDeliveryMethod();
        });
        
        storePickupContainer.addEventListener('click', function() {
            storePickupRadio.checked = true;
            toggleDeliveryMethod();
        });
        
        // Add change event listeners to delivery method radio buttons
        homeDeliveryRadio.addEventListener('change', toggleDeliveryMethod);
        storePickupRadio.addEventListener('change', toggleDeliveryMethod);
        
        // Payment method toggle functionality
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
        toggleDeliveryMethod();
        togglePaymentForms();
        
        // Handle window resize to ensure map displays correctly
        window.addEventListener('resize', function() {
            if (storeMap && !storeLocationContainer.classList.contains('d-none')) {
                storeMap.invalidateSize();
            }
        });
    });
</script>
@endsection
