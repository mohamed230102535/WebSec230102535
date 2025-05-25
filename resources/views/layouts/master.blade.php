<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebSec - @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('favicon.ico') }}?v={{ time() }}">
    
    <!-- Bootstrap CSS -->
    <link href="{{ url('css/bootstrap.min.css') }}?v={{ time() }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --danger-color: #e74c3c;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: white !important;
        }
        
        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .form-control {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .alert {
            border-radius: 6px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ url('js/bootstrap.bundle.min.js') }}?v={{ time() }}"></script>
    
    @yield('scripts')
</body>
</html>
