@extends('layouts.master')

@section('title', 'Orders Management')

@section('styles')
<style>
    .dropdown-item-form {
        display: block;
        width: 100%;
        padding: 0;
    }
    .dropdown-item-form button {
        text-align: left;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Orders Management</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Status Filter -->
                <div class="mb-4">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="me-2">Filter by status:</span>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}">
                            All Orders
                        </a>
                        @foreach($statuses as $statusKey => $statusLabel)
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ][$statusKey] ?? 'secondary';
                            @endphp
                            <a href="{{ route('orders.index', ['status' => $statusKey]) }}" 
                               class="btn btn-sm {{ request('status') == $statusKey ? 'btn-'.$statusClass : 'btn-outline-'.$statusClass }}">
                                {{ $statusLabel }}
                            </a>
                        @endforeach
                    </div>
                </div>
                
                @if(count($orders) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    @if(auth()->user()->can('view_all_orders'))
                                    <th>Customer</th>
                                    @endif
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    @if(auth()->user()->can('view_all_orders'))
                                    <td>{{ $order->user->name }}</td>
                                    @endif
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ][$order->status] ?? 'secondary';
                                            $textClass = $order->status == 'pending' ? 'text-dark' : '';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }} {{ $textClass }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Quick actions menu -->
                                            @php
                                                $nextStatuses = $order->getNextStatuses();
                                            @endphp
                                            
                                            @if(count($nextStatuses) > 0)
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @foreach($nextStatuses as $statusValue => $statusLabel)
                                                        <li>
                                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="dropdown-item-form">
                                                                @csrf
                                                                <input type="hidden" name="status" value="{{ $statusValue }}">
                                                                <button type="submit" class="dropdown-item" 
                                                                    @if($statusValue == 'cancelled')
                                                                        onclick="return confirm('Are you sure you want to cancel this order? Items will be returned to inventory.')"
                                                                    @endif
                                                                >
                                                                    @if($statusValue == 'cancelled')
                                                                        <i class="fas fa-times text-danger me-2"></i>
                                                                    @elseif($statusValue == 'delivered')
                                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                                    @elseif($statusValue == 'shipped')
                                                                        <i class="fas fa-shipping-fast text-primary me-2"></i>
                                                                    @elseif($statusValue == 'processing')
                                                                        <i class="fas fa-cogs text-info me-2"></i>
                                                                    @else
                                                                        <i class="fas fa-arrow-right me-2"></i>
                                                                    @endif
                                                                    {{ $statusLabel }}
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h5>No orders found</h5>
                        <p class="text-muted">You haven't placed any orders yet.</p>
                        <a href="{{ route('products_list') }}" class="btn btn-primary mt-3">
                            <i class="fa fa-shopping-cart me-1"></i> Shop Now
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
