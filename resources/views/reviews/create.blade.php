@extends('layouts.master')

@section('title', 'Write a Review')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Write a Review for {{ $product->name }}</h5>
                <a href="{{ route('reviews.index', $product->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back to Reviews
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
                
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">Rating</label>
                        <div class="d-flex">
                            <div class="rate">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5" {{ old('rating') == 5 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="rating5">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        (5 - Excellent)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="rate">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4" {{ old('rating') == 4 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating4">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        (4 - Very Good)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="rate">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3" {{ old('rating') == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating3">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        (3 - Good)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="rate">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2" {{ old('rating') == 2 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating2">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        (2 - Fair)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="rate">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" {{ old('rating') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        (1 - Poor)
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('rating')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="comment" class="form-label">Your Review</label>
                        <textarea name="comment" id="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" placeholder="Write your review here...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Your review will help other customers make better purchasing decisions.</div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('reviews.index', $product->id) }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
