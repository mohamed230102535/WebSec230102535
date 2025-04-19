@extends('layouts.master')
@section('title', 'Welcome')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Welcome to the Home Page</h3>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">We are glad to have you here. Explore and enjoy our services!</p>
                        <a href="{{ url('/about') }}" class="btn btn-primary mt-3">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
