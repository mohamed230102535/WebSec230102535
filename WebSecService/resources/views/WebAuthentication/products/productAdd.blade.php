@extends("layouts.master2")
@section("title", "Add Product - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-plus-circle me-2"></i>Add Product
    </h1>
    <p class="lead" style="color: #ffffff;">
      Create a New Cutting-Edge PC Product
    </p>
  </div>
</section>

<!-- Add Product Form -->
<section class="py-5" style="background: #1a1a1a;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card bg-dark text-white border-0 shadow-lg animate__animated animate__fadeIn">
          <div class="card-body">
            @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form action="{{ route('WebAuthentication.products.doCreate') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <!-- Code -->
              <div class="mb-3">
                <label for="code" class="form-label" style="color: #d4a373;">Product Code</label>
                <input type="text" class="form-control bg-dark text-white border-danger" id="code" name="code" 
                       value="{{ old('code') }}" maxlength="10" required>
                @error('code')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Name -->
              <div class="mb-3">
                <label for="name" class="form-label" style="color: #d4a373;">Product Name</label>
                <input type="text" class="form-control bg-dark text-white border-danger" id="name" name="name" 
                       value="{{ old('name') }}" maxlength="255" required>
                @error('name')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Model -->
              <div class="mb-3">
                <label for="model" class="form-label" style="color: #d4a373;">Model</label>
                <input type="text" class="form-control bg-dark text-white border-danger" id="model" name="model" 
                       value="{{ old('model') }}" maxlength="50" required>
                @error('model')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Photo -->
              <div class="mb-3">
                <label for="photo" class="form-label" style="color: #d4a373;">Product Photo</label>
                <input type="file" class="form-control bg-dark text-white border-danger" id="photo" name="photo" 
                       accept="image/*" required>
                @error('photo')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Description -->
              <div class="mb-3">
                <label for="description" class="form-label" style="color: #d4a373;">Description</label>
                <textarea class="form-control bg-dark text-white border-danger" id="description" name="description" 
                          rows="4">{{ old('description') }}</textarea>
                @error('description')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Price -->
              <div class="mb-3">
                <label for="price" class="form-label" style="color: #d4a373;">Price (credits)</label>
                <input type="number" class="form-control bg-dark text-white border-danger" id="price" name="price" 
                       value="{{ old('price') }}" step="0.01" min="0" required>
                @error('price')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Stock -->
              <div class="mb-3">
                <label for="stock" class="form-label" style="color: #d4a373;">Stock Quantity</label>
                <input type="number" class="form-control bg-dark text-white border-danger" id="stock" name="stock" 
                       value="{{ old('stock') }}" min="0" required>
                @error('stock')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Submit Button -->
              <div class="text-end">
                <button type="submit" class="btn btn-danger btn-cool px-4">
                  <i class="fas fa-save me-2"></i>Save Product
                </button>
                <a href="{{ route('WebAuthentication.products') }}" class="btn btn-outline-warning btn-cool px-4">
                  <i class="fas fa-arrow-left me-2"></i>Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Custom Styles -->
<style>
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

.card {
  border: 1px solid #d4a373;
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
});
</script>
@endsection

@endsection