@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="row">
    <div class="m-4 col-sm-6">
        <table class="table table-striped">
            <tr>
                <th>Name</th><td>{{$user->name}}</td>
            </tr>
            <tr>
                <th>Email</th><td>{{$user->email}}</td>
            </tr>
            <tr>
                <th>Roles</th>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Permissions</th>
                <td>
                    @foreach($permissions as $permission)
                        <span class="badge bg-success">{{$permission->display_name}}</span>
                    @endforeach
                </td>
            </tr>
        </table>

        <div class="row">
            <div class="col col-6">
            </div>
            @if(auth()->check() && (auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id))
            <div class="col col-4">
                <a class="btn btn-primary" href='{{route('edit_password', $user->id)}}'>Change Password</a>
            </div>
            @else
            <div class="col col-4">
            </div>
            @endif
            @if(auth()->user()->hasPermissionTo('edit_users')||auth()->id()==$user->id)
            <div class="col col-2">
                <a href="{{route('users_edit', $user->id)}}" class="btn btn-success form-control">Edit</a>
            </div>
            @endif
        </div>
    </div>
    <div class="container">
     
     <h3>Purchased Products</h3>
        @if($purchasedProducts->isEmpty())
            <p>No products purchased yet.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price at Purchase</th>
                        <th>Date Purchased</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasedProducts as $purchase)
                        <tr>
                            <td>{{ $purchase->product->name }}</td>
                            <td>${{ $purchase->price_at_purchase }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
</div>
</div>
@endsection
