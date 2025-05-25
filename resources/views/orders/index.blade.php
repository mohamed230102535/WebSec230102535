@extends('layouts.master')

@section('title', 'My Orders')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ auth()->user()->can('view_all_orders') ? 'All Orders' : 'My Orders' }}</h5>
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
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        @if(auth()->user()->can('cancel_own_orders') && $order->status == 'pending' && auth()->id() == $order->user_id)
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(auth()->user()->can('cancel_orders') && $order->status != 'cancelled')
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
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
