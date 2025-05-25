@extends('layouts.master')

@section('title', 'Product Reviews')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Reviews for {{ $product->name }}</h5>
                <a href="{{ route('products_list') }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back to Products
                </a>
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
                
                <div class="d-flex align-items-center mb-4">
                    <div class="me-4 text-center">
                        <h1 class="display-4 mb-0">{{ number_format($product->average_rating, 1) }}</h1>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->average_rating))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted mb-0">{{ $product->reviews_count }} {{ Str::plural('review', $product->reviews_count) }}</p>
                    </div>
                    
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-2">5 <i class="far fa-star"></i></div>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                @php $fiveStarPercent = $product->reviews_count > 0 ? ($product->reviews()->where('rating', 5)->count() / $product->reviews_count) * 100 : 0; @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fiveStarPercent }}%" aria-valuenow="{{ $fiveStarPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-2">4 <i class="far fa-star"></i></div>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                @php $fourStarPercent = $product->reviews_count > 0 ? ($product->reviews()->where('rating', 4)->count() / $product->reviews_count) * 100 : 0; @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fourStarPercent }}%" aria-valuenow="{{ $fourStarPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-2">3 <i class="far fa-star"></i></div>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                @php $threeStarPercent = $product->reviews_count > 0 ? ($product->reviews()->where('rating', 3)->count() / $product->reviews_count) * 100 : 0; @endphp
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $threeStarPercent }}%" aria-valuenow="{{ $threeStarPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-2">2 <i class="far fa-star"></i></div>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                @php $twoStarPercent = $product->reviews_count > 0 ? ($product->reviews()->where('rating', 2)->count() / $product->reviews_count) * 100 : 0; @endphp
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $twoStarPercent }}%" aria-valuenow="{{ $twoStarPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-2">1 <i class="far fa-star"></i></div>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                @php $oneStarPercent = $product->reviews_count > 0 ? ($product->reviews()->where('rating', 1)->count() / $product->reviews_count) * 100 : 0; @endphp
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $oneStarPercent }}%" aria-valuenow="{{ $oneStarPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(auth()->check() && $canReview)
                    <div class="mb-4">
                        @if($userReview)
                            <a href="{{ route('reviews.edit', $userReview->id) }}" class="btn btn-primary">
                                <i class="fa fa-edit me-1"></i> Edit Your Review
                            </a>
                        @else
                            <a href="{{ route('reviews.create', $product->id) }}" class="btn btn-primary">
                                <i class="fa fa-star me-1"></i> Write a Review
                            </a>
                        @endif
                    </div>
                @endif
                
                <h5 class="mb-3">{{ count($reviews) }} {{ Str::plural('Review', count($reviews)) }}</h5>
                
                @if(count($reviews) > 0)
                    @foreach($reviews as $review)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                        <div class="text-muted small">
                                            <span>{{ $review->created_at->format('F d, Y') }}</span>
                                            @if($review->is_verified)
                                                <span class="badge bg-success ms-1">Verified Purchase</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                
                                <p class="mb-0">{{ $review->comment }}</p>
                                
                                @if(auth()->check() && (auth()->id() == $review->user_id || auth()->user()->can('moderate_reviews')))
                                    <div class="mt-2 text-end">
                                        @if(auth()->id() == $review->user_id)
                                            <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('reviews.destroy', $review->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="mt-3">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-comment-slash fa-3x text-muted mb-3"></i>
                        <h5>No Reviews Yet</h5>
                        <p class="text-muted">Be the first to review this product.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $product->name }}</h5>
            </div>
            <div class="card-body">
                @if($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="img-fluid mb-3 rounded">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="fa fa-image fa-3x text-muted"></i>
                    </div>
                @endif
                
                <p class="mb-1"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                <p class="mb-1"><strong>Model:</strong> {{ $product->model }}</p>
                <p class="mb-3"><strong>In Stock:</strong> {{ $product->stock }}</p>
                
                <p>{{ $product->description }}</p>
                
                @if(auth()->check())
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" placeholder="Quantity">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-shopping-cart me-1"></i> Add to Cart
                        </button>
                    </div>
                </form>
                @endif
                
                <a href="{{ route('products_list') }}" class="btn btn-outline-secondary w-100">
                    <i class="fa fa-arrow-left me-1"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
