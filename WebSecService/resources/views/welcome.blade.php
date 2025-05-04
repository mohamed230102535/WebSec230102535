@extends('layouts.master')
@section('title', 'Welcome')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h1 class="display-4 mb-3">Welcome to WebSec</h1>
                    <p class="lead text-muted">Your secure web application platform</p>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Secure Platform</h5>
                                <p class="card-text text-muted">Built with security in mind</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-box fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Product Management</h5>
                                <p class="card-text text-muted">Manage your products efficiently</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-info mb-3"></i>
                                <h5 class="card-title">User Management</h5>
                                <p class="card-text text-muted">Control user access and permissions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
