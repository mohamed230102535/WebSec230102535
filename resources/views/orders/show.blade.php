@extends('layouts.master')

@section('title', 'Order Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Details</h5>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back to Orders
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
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Order Information</h6>
                                <p class="mb-1"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('F d, Y g:i A') }}</p>
                                <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                <p class="mb-1">
                                    <strong>Status:</strong>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->status == 'shipped')
                                        <span class="badge bg-primary">Shipped</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Delivery Information</h6>
                                <p class="mb-1"><strong>Delivery Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->delivery_method)) }}</p>
                                
                                @if($order->delivery_method == 'home_delivery')
                                    <p class="mb-0"><strong>Shipping Address:</strong><br>{{ $order->shipping_address }}</p>
                                @else
                                    <p class="mb-0"><strong>Note:</strong> Please bring your order confirmation when picking up your order from our store.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Order Items</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->photo)
                                                <img src="{{ asset('storage/' . $item->product->photo) }}" alt="{{ $item->product->name ?? 'Product' }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fa fa-box text-muted"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name ?? 'Product Unavailable' }}</h6>
                                                    <small class="text-muted">{{ $item->product->code ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->total, 2) }}</td>
                                        <td class="text-end">
                                            @if($item->product && auth()->user()->can('write_reviews'))
                                                <a href="{{ route('reviews.create', $item->product->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-star me-1"></i> Review
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">${{ number_format($order->total_amount, 2) }}</th>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                @if(auth()->user()->can('manage_orders') && $order->status != 'cancelled')
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Update Order Status</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="d-flex">
                            @csrf
                            <select name="status" class="form-select me-2">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled">Cancel Order</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>
                @elseif(auth()->user()->can('cancel_own_orders') && $order->status == 'pending' && auth()->id() == $order->user_id)
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="fa fa-times me-1"></i> Cancel Order
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
