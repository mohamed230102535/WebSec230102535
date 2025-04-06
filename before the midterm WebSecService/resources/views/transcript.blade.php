@extends('layouts.master')
@section('title', 'Transcript')
@section('content')
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Transcript</a>
        </div>
    </nav>

    <div class="card shadow-lg border-0 m-4">
    <div class="card-header bg-dark text-white text-center fw-bold">
        Student Information
    </div>
    <div class="card-body p-4">
        <p class="mb-2"><strong>Name:</strong> {{ $student['name'] }}</p>
        <p class="mb-2"><strong>ID:</strong> {{ $student['id'] }}</p>
        <p class="mb-2"><strong>Department:</strong> {{ $student['department'] }}</p>
        <p class="mb-0"><strong>GPA:</strong> 
            <span class="badge bg-success fs-6">{{ $student['GPA'] }}</span>
        </p>
    </div>
</div>

    

        </div>
    </div>
    
    <div class="text-center mt-3">
        <a href="/" class="btn btn-primary btn-lg">🏠 Back to Home</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection