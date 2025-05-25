@extends('layouts.master')
@section('title', 'Categories')
@section('content')

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-10">
            <h1 class="display-4 mb-0">Categories</h1>
        </div>
        <div class="col-md-2">
            @auth
            <a href="{{ route('categories.edit') }}" class="btn btn-success w-100">
                <i class="fas fa-plus"></i> Add Category
            </a>
            @endauth
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <span class="badge bg-primary">{{ $category->products_count }} products</span>
                        </div>
                        <p class="card-text text-muted">{{ Str::limit($category->description, 100) }}</p>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('products_list', ['category' => $category->slug]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View Products
                            </a>
                            <div>
                                @auth
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('categories.delete', $category->id) }}" class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Are you sure you want to delete this category?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No categories found. <a href="{{ route('categories.edit') }}">Create your first category</a>.
                </div>
            </div>
        @endforelse
    </div>
</div>

@endsection
