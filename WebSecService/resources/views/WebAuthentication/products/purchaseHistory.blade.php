@extends("layouts.master2")
@section("title", "Purchase History - OneHitPoint")
@section("content")
@auth
<!-- Purchase History Header -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e); position: relative; overflow: hidden;">
  <div class="container text-center position-relative z-index-1">
    <h1 class="display-4 fw-bold animate__animated animate__pulse" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-history me-2"></i> Purchase History
    </h1>
    <p class="lead" style="color: #ffffff;">
      Your Past Purchases
    </p>
  </div>
  <div class="header-bg-effect" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle, rgba(212, 163, 115, 0.1) 0%, rgba(44, 11, 14, 0.8) 80%); opacity: 0.7;"></div>
</section>

<!-- Purchase History Table -->
<section class="py-5" style="background: #1a1a1a;">
  <div class="container">
    <div class="card bg-dark text-white border-0 shadow-lg">
      <div class="card-body">
        @if($purchases->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-3x mb-3" style="color: #d4a373;"></i>
            <h3>No Purchase History</h3>
            <p class="text-muted">You haven't made any purchases yet.</p>
            <a href="{{ route('WebAuthentication.products') }}" class="btn btn-warning btn-cool mt-3">
              <i class="fas fa-shopping-cart me-2"></i> Browse Products
            </a>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-dark table-hover">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($purchases as $purchase)
                  <tr>
                    <td>{{ $purchase->purchased_at->format('M d, Y H:i') }}</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $purchase->product->photo) }}" 
                             alt="{{ $purchase->product->name }}" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                        <div>
                          <div class="fw-bold">{{ $purchase->product->name }}</div>
                          <small class="text-muted">Model: {{ $purchase->product->model }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ number_format($purchase->price, 2) }} credits</td>
                    <td>
                      <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i> Completed
                      </span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

<!-- Custom Styles -->
<style>
.table {
  margin-bottom: 0;
}

.table th {
  border-top: none;
  color: #d4a373;
  font-weight: 600;
}

.table td {
  vertical-align: middle;
}

.badge {
  padding: 0.5em 0.8em;
  font-weight: 500;
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
</style>
@else
<h1>You are not logged in</h1>
@endauth
@endsection 