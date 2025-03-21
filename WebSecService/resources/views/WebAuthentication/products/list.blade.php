@extends("layouts.master2")
@section("title", "Products - OneHitPoint")
@section("content")

<!-- Products Header -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-dragon me-2"></i>Products
    </h1>
    <p class="lead" style="color: #ffffff;">
      Discover Our Cutting-Edge PC Products
    </p>
  </div>
</section>

<!-- Search Form -->
<section class="py-4" style="background: #2c0b0e;">
  <div class="container">
    <form method="GET" action="{{ route('WebAuthentication.products') }}" class="row g-3 align-items-center justify-content-center">
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

<!-- Products Grid -->
<section class="py-5 flex-grow-1" style="background: #1a1a1a; min-height: calc(100vh - 300px);">
  <div class="container">
    <!-- Add Product Button -->
     @can('create')
    <div class="text-end mb-4">
    
      <a href="{{ route('WebAuthentication.products.create') }}" class="btn btn-warning btn-cool px-4">
        <i class="fas fa-plus me-2"></i>Add New Product
      </a>
    </div>
    @endcan

    @if($products->isEmpty())
      <div class="text-center text-white">
        <h3>No products available at the moment.</h3>
        <p>Check back soon for exciting new additions!</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($products as $product)
          <div class="col-md-4 col-sm-6">
            <div class="card bg-dark text-white border-0 shadow-lg product-card h-100 animate__animated animate__fadeInUp">
              <img src="{{ asset('storage/' . $product->photo) }}" class="card-img-top" alt="{{ $product->name }}" 
                   style="height: 250px; object-fit: cover;">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold" style="color: #d4a373;">{{ $product->name }}</h5>
                <p class="card-text text-muted">Model: {{ $product->model }}</p>
                <p class="card-text flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                <div class="d-flex gap-2 mt-auto">
                  @can('edit', $product)
                  <a href="{{ route('WebAuthentication.products.edit', $product->id) }}" class="btn btn-outline-warning btn-cool flex-grow-1">
                    <i class="fas fa-edit me-2"></i>Edit
                  </a>
                  @endcan
                  @can('delete', $product)
                  <a href="{{ route('WebAuthentication.products.delete', $product->id) }}" class="btn btn-outline-danger btn-cool flex-grow-1" 
                     onclick="return confirm('Are you sure you want to delete {{ $product->name }}?');">
                    <i class="fas fa-trash me-2"></i>Delete
                  </a>
                  @endcan
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

<!-- Custom Styles -->
<style>
.product-card {
  transition: all 0.3s ease;
  border: 1px solid transparent;
}

.product-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(212, 163, 115, 0.2);
  border: 1px solid #d4a373;
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

.card-img-top {
  transition: transform 0.5s ease;
}

.product-card:hover .card-img-top {
  transform: scale(1.05);
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

.form-control, .form-select, .form-control:focus, .form-select:focus {
  background-color: #2c2c2c;
  border-color: #d71818;
  color: #ffffff;
  transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
  box-shadow: 0 0 10px rgba(215, 24, 24, 0.5);
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

  // Fade in animation on scroll (already applied via class)
});
</script>
@endsection

@endsection