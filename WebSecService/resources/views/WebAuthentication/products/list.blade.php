@extends("layouts.master2")
@section("title", "Products - OneHitPoint")
@section("content")
@auth
<!-- Products Header -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e); position: relative; overflow: hidden;">
  <div class="container text-center position-relative z-index-1">
    <h1 class="display-4 fw-bold animate__animated animate__pulse" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7), 0 0 30px rgba(212, 163, 115, 0.4);">
      <i class="fas fa-dragon me-2"></i> Products
    </h1>
    <p class="lead" style="color: #ffffff; text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);">
      Discover Our Cutting-Edge PC Products
    </p>
    <div class="mt-3">
      <span class="badge bg-warning text-dark fs-5 me-3">
        <i class="fas fa-coins me-2"></i>Your Credit Balance: {{ number_format(Auth::user()->credit, 2) }} credits
      </span>
      <a href="{{ route('WebAuthentication.products.history') }}" class="btn btn-outline-warning btn-cool">
        <i class="fas fa-history me-2"></i> Purchase History
      </a>
    </div>
  </div>
  <div class="header-bg-effect" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle, rgba(212, 163, 115, 0.1) 0%, rgba(44, 11, 14, 0.8) 80%); opacity: 0.7;"></div>
</section>

<!-- Search Form -->
<section class="py-4" style="background: #2c0b0e;">
  <div class="container">
    <form method="GET" action="{{ route('WebAuthentication.products') }}" class="row g-3 align-items-center justify-content-center">
      <div class="col-sm-6 col-md-4">
        <input name="keywords" type="text" class="form-control bg-dark text-white border-danger glowing-input" 
              placeholder="Search by Name or Email" value="{{ request()->keywords }}">
      </div>
      <div class="col-sm-2 col-md-1">
        <button type="submit" class="btn btn-danger btn-cool w-100">
          <i class="fas fa-search me-1"></i> Search
        </button>
      </div>
    </form>
  </section>

<!-- Products Grid -->
<section class="py-5 flex-grow-1" style="background: linear-gradient(135deg, #1a1a1a 0%, #141414 100%); min-height: calc(100vh - 300px); position: relative;">
  <div class="container">
    <!-- Add Product Button -->
    @can('create')
    <div class="text-end mb-4">
      <a href="{{ route('WebAuthentication.products.create') }}" class="btn btn-warning btn-cool px-5 glowing-btn">
        <i class="fas fa-plus me-2"></i> Add New Product
      </a>
    </div>
    @endcan

    @if($products->isEmpty())
      <div class="text-center text-white">
        <h3>No products available at the moment.</h3>
        <p class="text-muted">Check back soon for exciting new additions!</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($products as $product)
          <div class="col-md-4 col-sm-6">
            <div class="card bg-dark text-white border-0 shadow-lg product-card h-100 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s;">
              <div class="card-img-wrapper">
                <img src="{{ asset('storage/' . $product->photo) }}" class="card-img-top" alt="{{ $product->name }}" 
                    style="height: 250px; object-fit: cover;">
                <div class="img-overlay"></div>
              </div>
              <div class="card-body d-flex flex-column position-relative">
                <h5 class="card-title fw-bold" style="color: #d4a373; text-shadow: 0 0 8px rgba(212, 163, 115, 0.5);">{{ $product->name }}</h5>
                <p class="card-text text-muted">Model: {{ $product->model }}</p>
                <p class="card-text flex-grow-1" style="color: rgba(255, 255, 255, 0.8);">{{ Str::limit($product->description, 100) }}</p>
                <div class="mt-2 mb-3">
                    <span class="badge bg-warning text-dark me-2">Price: {{ number_format($product->price, 2) }} credits</span>
                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                        Stock: {{ $product->stock }}
                    </span>
                </div>
                <div class="d-flex gap-2 mt-auto">
                    @if($product->stock > 0)
                        @if(Auth::user()->credit >= $product->price)
                            <form action="{{ route('WebAuthentication.products.purchase', $product->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-success btn-cool w-100 glowing-btn">
                                    <i class="fas fa-shopping-cart me-2"></i> Purchase
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-danger btn-cool w-100 glowing-btn" disabled>
                                <i class="fas fa-exclamation-circle me-2"></i> Insufficient Credit
                            </button>
                        @endif
                    @endif
                    @can('edit', $product)
                    <a href="{{ route('WebAuthentication.products.edit', $product->id) }}" class="btn btn-outline-warning btn-cool flex-grow-1 glowing-btn">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                    @endcan
                    @can('delete', $product)
                    <a href="{{ route('WebAuthentication.products.delete', $product->id) }}" class="btn btn-outline-danger btn-cool flex-grow-1 glowing-btn" 
                        onclick="return confirm('Are you sure you want to delete {{ $product->name }}?');">
                        <i class="fas fa-trash me-2"></i> Delete
                    </a>
                    @endcan
                </div>
                <!-- Neon Glow Effect -->
                <span class="glow-effect"></span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
@else
<h1>your not loged in</h1>
@endauth

<!-- Custom Styles -->
<style>
/* Cool Theme Enhancements */
.z-index-1 {
  position: relative;
  z-index: 1;
}

/* Product Card */
.product-card {
  background: linear-gradient(135deg, #2c2c2c 0%, #1f1f1f 100%);
  transition: all 0.4s ease;
  border: 1px solid transparent;
  border-radius: 12px;
  position: relative;
  overflow: hidden;
}
.product-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 15px 30px rgba(212, 163, 115, 0.3), 0 0 20px rgba(215, 24, 24, 0.2);
  border: 1px solid #d4a373;
}

/* Image Wrapper */
.card-img-wrapper {
  position: relative;
  overflow: hidden;
}
.card-img-top {
  transition: transform 0.5s ease;
}
.product-card:hover .card-img-top {
  transform: scale(1.1);
}
.img-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(44, 11, 14, 0) 0%, rgba(44, 11, 14, 0.6) 100%);
  transition: opacity 0.3s ease;
}
.product-card:hover .img-overlay {
  opacity: 0.8;
}

/* Glow Effect */
.glow-effect {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(212, 163, 115, 0.2) 0%, transparent 70%);
  opacity: 0;
  transition: opacity 0.5s ease;
  pointer-events: none;
}
.product-card:hover .glow-effect {
  opacity: 0.5;
}

/* Cool Buttons */
.btn-cool {
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  font-weight: bold;
}
.btn-cool:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(212, 163, 115, 0.5);
  color: #ffffff !important;
}

/* Glowing Buttons */
.glowing-btn {
  border: 2px solid transparent;
}
.glowing-btn:hover {
  border-color: #d4a373;
  box-shadow: 0 0 15px rgba(212, 163, 115, 0.7), inset 0 0 10px rgba(212, 163, 115, 0.3);
}

/* Specific Button Styles */
.btn-outline-warning {
  border-color: #d4a373;
  color: #d4a373;
}
.btn-outline-warning:hover {
  background: #d4a373;
  color: #2c0b0e;
}
.btn-outline-danger {
  border-color: #d71818;
  color: #d71818;
}
.btn-outline-danger:hover {
  background: #d71818;
  color: #ffffff;
}
.btn-danger {
  background: #d71818;
  border: none;
}
.btn-danger:hover {
  background: #b31414;
  box-shadow: 0 0 15px rgba(215, 24, 24, 0.7);
}
.btn-warning {
  background: linear-gradient(45deg, #d4a373, #ffcc00);
  border: none;
  color: #2c0b0e;
}
.btn-warning:hover {
  background: linear-gradient(45deg, #b3884f, #e6b800);
}

/* Form Elements */
.glowing-input, .glowing-input:focus {
  background: #2c2c2c;
  border-color: #d71818;
  color: #ffffff;
  transition: all 0.3s ease;
}
.glowing-input:focus {
  border-color: #d4a373;
  box-shadow: 0 0 15px rgba(212, 163, 115, 0.5);
}

/* Text Muted */
.text-muted {
  color: #aaaaaa;
}
</style>

<!-- Scripts -->

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
        background: 'rgba(212, 163, 115, 0.3)', /* Gold ripple */
        'border-radius': '50%',
        transform: 'scale(0)',
        'pointer-events': 'none',
        width: '100px',
        height: '100px',
        'margin-left': '-50px',
        'margin-top': '-50px'
      })
      .animate({
        scale: 2.5,
        opacity: 0
      }, 400, function() {
        $(this).remove();
      });
      
    $(this).append(ripple);
  });
});
</script>


@endsection