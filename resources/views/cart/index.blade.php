@extends('layouts.master')

@section('title', 'Shopping Cart')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Shopping Cart</h5>
                @if(count($cartItems) > 0)
                <div>
                    <a href="{{ route('cart.clear') }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                        <i class="fa fa-trash me-1"></i> Clear Cart
                    </a>
                </div>
                @endif
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
                
                @if(count($cartItems) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th width="120">Quantity</th>
                                    <th class="text-end">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->photo)
                                            <img src="{{ asset('storage/' . $item->product->photo) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fa fa-box text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control">
                                                <button class="btn btn-outline-primary" type="submit">
                                                    <i class="fa fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('cart.remove', $item->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this item?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end">${{ number_format($total, 2) }}</th>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('products_list') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-arrow-left me-1"></i> Continue Shopping
                        </a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                            Proceed to Checkout <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5>Your cart is empty</h5>
                        <p class="text-muted">Add some products to your cart and they will appear here.</p>
                        <a href="{{ route('products_list') }}" class="btn btn-primary mt-3">
                            <i class="fa fa-arrow-left me-1"></i> Continue Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
