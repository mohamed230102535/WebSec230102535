<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body>

   
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
