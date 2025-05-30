@extends('layouts.master')
@section('title', 'Products List')
@section('content')

<style>
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .product-image {
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
    }
    .search-form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn-purchase {
        background: #28a745;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        transition: all 0.3s;
    }
    .btn-purchase:hover {
        background: #218838;
        transform: scale(1.05);
    }
    .product-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }
</style>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-10">
            <h1 class="display-4 mb-0">Products</h1>
        </div>
        <div class="col-md-2">
            @can('add_products')
            <a href="{{route('products_edit')}}" class="btn btn-success w-100">
                <i class="fas fa-plus"></i> Add Product
            </a>
            @endcan
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

    <div class="search-form">
        <form>
            <div class="row g-3">
                <div class="col-md-2">
                    <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}">
                </div>
                <div class="col-md-2">
                    <input name="min_price" type="number" class="form-control" placeholder="Min Price" value="{{ request()->min_price }}">
                </div>
                <div class="col-md-2">
                    <input name="max_price" type="number" class="form-control" placeholder="Max Price" value="{{ request()->max_price }}">
                </div>
                <div class="col-md-2">
                    <select name="order_by" class="form-select">
                        <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                        <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                        <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="order_direction" class="form-select">
                        <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Order Direction</option>
                        <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>Ascending</option>
                        <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
                <div class="col-md-1">
                    <button type="reset" class="btn btn-outline-danger w-100">Reset</button>
                </div>
            </div>
        </form>
    </div>

    @foreach($products as $product)
        <div class="card product-card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{asset("images/$product->photo")}}" class="product-image w-100" alt="{{$product->name}}">
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h2 class="product-title">{{$product->name}}</h2>
                            <div class="d-flex gap-2">
                                @can('edit_products')
                                <a href="{{route('products_edit', $product->id)}}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endcan
                                @can('delete_products')
                                <a href="{{route('products_delete', $product->id)}}" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                @endcan
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr><th width="20%">Name</th><td>{{$product->name}}</td></tr>
                                <tr><th>Model</th><td>{{$product->model}}</td></tr>
                                <tr><th>Code</th><td>{{$product->code}}</td></tr>
                                <tr><th>Price</th><td>${{number_format($product->price, 2)}}</td></tr>
                                <tr><th>Stock</th><td>{{$product->stock}}</td></tr>
                                <tr><th>Description</th><td>{{$product->description}}</td></tr>
                            </table>
                        </div>

                        @auth
                        <form action="{{ route('purchase_product', $product->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-purchase">
                                <i class="fas fa-shopping-cart"></i> Purchase Now
                            </button>
                        </form>
                        @else
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-info-circle"></i> Please <a href="{{ route('login') }}" class="alert-link">log in</a> to make a purchase.
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection
