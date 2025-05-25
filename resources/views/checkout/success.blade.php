@extends('layouts.master')

@section('title', 'Order Placed Successfully')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fa fa-check fa-3x"></i>
                    </div>
                    <h3>Thank You for Your Order!</h3>
                    <p class="text-muted">Your order has been placed successfully and is being processed.</p>
                    <div class="alert alert-info mt-3">
                        <strong>Order Number:</strong> {{ $order->order_number }}
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 text-md-start">
                                <h6>Order Details</h6>
                                <p class="mb-1"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                                <p class="mb-1"><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                                <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">{{ ucfirst($order->status) }}</span></p>
                            </div>
                            <div class="col-md-6 text-md-start">
                                <h6>Delivery Information</h6>
                                <p class="mb-1"><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->delivery_method)) }}</p>
                                @if($order->delivery_method == 'home_delivery')
                                    <p class="mb-0"><strong>Shipping Address:</strong><br>{{ $order->shipping_address }}</p>
                                @else
                                    <p class="mb-0"><strong>Note:</strong> Please bring your order confirmation when picking up your order.</p>
                                @endif
                            </div>
                        </div>
                        
                        <h6>Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">${{ number_format($order->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
                        <i class="fa fa-file-alt me-1"></i> View Order Details
                    </a>
                    <a href="{{ route('products_list') }}" class="btn btn-secondary">
                        <i class="fa fa-shopping-bag me-1"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
