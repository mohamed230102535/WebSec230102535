@extends('layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> WebSec Admin Dashboard</h5>
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
                
                <!-- Overall Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total Orders</h6>
                                        <h3 class="mb-0">{{ $totalOrdersCount }}</h3>
                                    </div>
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total Revenue</h6>
                                        <h3 class="mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                                    </div>
                                    <i class="fas fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total Products</h6>
                                        <h3 class="mb-0">{{ $totalProductsCount }}</h3>
                                    </div>
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total Users</h6>
                                        <h3 class="mb-0">{{ $totalUsersCount }}</h3>
                                    </div>
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center border-end">
                                        <h5>{{ $totalCategoriesCount }}</h5>
                                        <p class="text-muted mb-0">Categories</p>
                                    </div>
                                    <div class="col-md-3 text-center border-end">
                                        <h5>{{ round(($totalProductsCount > 0) ? ($totalRevenue / $totalProductsCount) : 0, 2) }}</h5>
                                        <p class="text-muted mb-0">Avg. Revenue per Product</p>
                                    </div>
                                    <div class="col-md-3 text-center border-end">
                                        <h5>{{ round(($totalUsersCount > 0) ? ($totalOrdersCount / $totalUsersCount) : 0, 2) }}</h5>
                                        <p class="text-muted mb-0">Avg. Orders per User</p>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <h5>{{ date('Y-m-d H:i') }}</h5>
                                        <p class="text-muted mb-0">Current Date/Time</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Status Cards -->
                <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-shopping-cart me-2"></i> Order Status Overview</h5>
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card bg-warning text-dark h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Pending</h6>
                                        <h3 class="mb-0">{{ $pendingOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-warning bg-opacity-25 py-1 text-center">
                                <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="small text-dark">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Processing</h6>
                                        <h3 class="mb-0">{{ $processingOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-cogs fa-2x"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-info bg-opacity-25 py-1 text-center">
                                <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="small text-white">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Shipped</h6>
                                        <h3 class="mb-0">{{ $shippedOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-shipping-fast fa-2x"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-primary bg-opacity-25 py-1 text-center">
                                <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="small text-white">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Delivered</h6>
                                        <h3 class="mb-0">{{ $deliveredOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-success bg-opacity-25 py-1 text-center">
                                <a href="{{ route('orders.index', ['status' => 'delivered']) }}" class="small text-white">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-danger text-white h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Cancelled</h6>
                                        <h3 class="mb-0">{{ $cancelledOrdersCount ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-danger bg-opacity-25 py-1 text-center">
                                <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="small text-white">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-secondary text-white h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">All Orders</h6>
                                        <h3 class="mb-0">{{ $totalOrdersCount }}</h3>
                                    </div>
                                    <i class="fas fa-list fa-2x"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-secondary bg-opacity-25 py-1 text-center">
                                <a href="{{ route('orders.index') }}" class="small text-white">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders and Top Products -->
                <div class="row mb-4">
                    <!-- Recent Orders -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i> Recent Orders</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Customer</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders as $order)
                                            <tr>
                                                <td>{{ $order->order_number ?? $order->id }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'shipped' => 'primary',
                                                            'delivered' => 'success',
                                                            'cancelled' => 'danger'
                                                        ][$order->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                                </td>
                                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No recent orders found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light text-end">
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">View All Orders</a>
                            </div>
                        </div>
                    </div>
                
                    <!-- Top Products -->                
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-star me-2"></i> Top Selling Products</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Sales</th>
                                                <th>Revenue</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topProducts as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('images/' . $product->photo) }}" alt="{{ $product->name }}" class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                        <div>{{ $product->name }}</div>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($product->price, 2) }}</td>
                                                <td>{{ $product->total_sales ?? 0 }}</td>
                                                <td>${{ number_format($product->revenue ?? 0, 2) }}</td>
                                                <td>
                                                    @if($product->stock < 10)
                                                    <span class="text-danger">{{ $product->stock }}</span>
                                                    @else
                                                    {{ $product->stock }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No product sales data available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light text-end">
                                <a href="{{ route('products_list') }}" class="btn btn-sm btn-primary">View All Products</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Low Stock Products and Recent Users -->
                <div class="row mb-4">
                    <!-- Low Stock Products -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-warning"></i> Low Stock Products</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($lowStockProducts as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('images/' . $product->photo) }}" alt="{{ $product->name }}" class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                        <div>{{ $product->name }}</div>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($product->price, 2) }}</td>
                                                <td><span class="badge bg-danger">{{ $product->stock }}</span></td>
                                                <td>
                                                    <a href="{{ route('products_edit', $product->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No low stock products found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Users -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-users me-2"></i> Recent Users</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Joined</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentUsers as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No users found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
