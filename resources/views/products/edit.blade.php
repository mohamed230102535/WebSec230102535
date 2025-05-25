@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title mb-4">
                    <i class="fas fa-edit"></i> {{ isset($product->id) ? 'Edit Product' : 'Add New Product' }}
                </h2>

                @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{$error}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endforeach

                <form action="{{route('products_save', $product->id ?? '')}}" method="post">
                    {{ csrf_field() }}
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="code" name="code" placeholder="Code" required value="{{$product->code ?? ''}}">
                                <label for="code">Code</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="model" name="model" placeholder="Model" required value="{{$product->model ?? ''}}">
                                <label for="model">Model</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="{{$product->name ?? ''}}">
                                <label for="name">Name</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Select a category (optional)</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ (isset($product->category_id) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="category_id">Category</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Price" required value="{{$product->price ?? ''}}">
                                <label for="price">Price</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="photo" name="photo" placeholder="Photo" required value="{{$product->photo ?? ''}}">
                                <label for="photo">Photo</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="description" name="description" placeholder="Description" style="height: 100px" required>{{$product->description ?? ''}}</textarea>
                                <label for="description">Description</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock" required value="{{$product->stock ?? ''}}">
                                <label for="stock">Stock</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('products_list') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
