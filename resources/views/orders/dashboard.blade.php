@extends('layouts.master')

@section('title', 'Order Management Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Management Dashboard</h5>
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
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Pending Orders</h6>
                                        <h3 class="mb-0">{{ $pendingOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Processing Orders</h6>
                                        <h3 class="mb-0">{{ $processingOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-cogs fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Delivered Orders</h6>
                                        <h3 class="mb-0">{{ $deliveredOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Cancelled Orders</h6>
                                        <h3 class="mb-0">{{ $cancelledOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Recent Orders</h6>
                    </div>
                    <div class="card-body">
                        @if(count($recentOrders ?? []) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders ?? [] as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>${{ number_format($order->total_amount, 2) }}</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted mb-0">No recent orders found</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Top Selling Products</h6>
                    </div>
                    <div class="card-body">
                        @if(count($topProducts ?? []) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total Sales</th>
                                            <th>Revenue</th>
                                            <th>Current Stock</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProducts ?? [] as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->total_sales ?? 0 }}</td>
                                            <td>${{ number_format($product->revenue ?? 0, 2) }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>
                                                <a href="{{ route('products_edit', $product->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted mb-0">No product sales data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
