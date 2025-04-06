@extends('layouts.master')
@section('title', 'Home')
@section('content')
<div class="container mt-5">
    <div class="card text-center shadow-lg border-0" style="background: linear-gradient(135deg, #007bff, #6610f2); color: white;">
        <div class="card-header border-0">
            <h2 class="fw-bold">Welcome, Dr. Mohamed Sobh!</h2>
        </div>
        <div class="card-body">
            <h4>Student: <strong>Mohamed Tarek Sayed</strong></h4>
            <h5>ID: <strong>230102535</strong></h5>
            <p class="mt-3">Here is a list of my works. Thank you for your time!</p>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('WebAuthentication.index') }}" class="btn btn-lg btn-primary shadow-lg px-5 py-3 fw-bold animate__animated animate__pulse animate__infinite">🔒 Web Authentication</a>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h3>My Assignments</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="./ass1" class="text-decoration-none">Assignment 1</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h3>LAB Participation</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="./minitest" class="text-decoration-none">miniTest Project</a></li>
                        <li class="list-group-item"><a href="./transcript" class="text-decoration-none">Transcript</a></li>
                        <li class="list-group-item"><a href="{{ route('users.index') }}" class="text-decoration-none">User CRUD</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h3>Home Testing</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="./test" class="text-decoration-none">Test Web</a></li>
                        <li class="list-group-item"><a href="{{ route('products_list') }}" class="text-decoration-none">Product Controller</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btn-primary').hover(
            function() {
                $(this).addClass('animate__rubberBand');
            },
            function() {
                $(this).removeClass('animate__rubberBand');
            }
        );
    });
</script>
@endsection