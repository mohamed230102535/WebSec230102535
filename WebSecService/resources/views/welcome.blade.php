@extends('layouts.master')
@section('title', 'Home')
@section('content')
<div class="container mt-4">

    <div class="card text-center shadow-lg">
        <div class="card-header bg-primary text-white">
            <h2>Welcome, Dr.Mohamed Sobh!</h2>
        </div>
        <div class="card-body">
            <h4 class="text-secondary">Student: <strong>Mohamed Tarek Sayed</strong></h4>
            <h5 class="text-secondary">ID: <strong>230102535</strong></h5>
            <p class="mt-3">Here is a list of my works. Thank you for your time!</p>
        </div>
    </div>


    <div class="card mt-4 shadow">
        <div class="card-header bg-info text-white">
            <h3>My Assignments</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><a href="./ass1" class="text-decoration-none">Assignment 1</a></li>

            </ul>
        </div>
    </div>
    <div class="card mt-4 shadow">
        <div class="card-header bg-success text-white">
            <h3>LAB Participation</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><a href="./minitest" class="text-decoration-none">miniTest Project</a></li>
                <li class="list-group-item"><a href="./transcript" class="text-decoration-none">Transcript</a></li>
                <li class="list-group-item">
                    <a href="{{ route('users.index') }}" class="text-decoration-none">User CRUD</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card mt-4 shadow">
        <div class="card-header text-primary-emphasis bg-primary-subtle border ">
            <h3>Home testing</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><a href="./test" class="text-decoration-none">Test web</a></li>
                <li class="list-group-item">
            <a href="{{ route('products_list') }}" class="text-decoration-none">Product Controller</a>
                </li>
               

                <li class="list-group-item">
            <a href="{{ route('WebAuthentication.index') }}" class="text-decoration-none">Web Authentication</a>
                </li>
              
            </ul>
        </div>
    </div>
</div>
@endsection