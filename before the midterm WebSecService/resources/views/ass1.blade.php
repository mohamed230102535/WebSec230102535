<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assignment Day 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="text-center mt-3">
        <a href="/" class="btn btn-primary btn-lg">🏠 Back to Home</a>
    </div>

<div class="card m-4">
    <div class="card-header">Multiplication Table</div>
    <div class="card-body">
            <div class="d-flex p-2 flex-wrap">
        @php($j = 1)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>
        @php($j = 2)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>
        @php($j = 3)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>

        </div class="">

        @php($j = 4)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>

        </div>
        @php($j = 5)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>

        @php($j = 6)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>

        @php($j = 7)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>
        @php($j = 8)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>
        @php($j = 9)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>
        @php($j = 10)
        <div class="card m-4 col-sm-2">
            <div class="card-header">{{$j}} Multiplication Table</div>
            <div class="card-body">
                <table>
                @foreach (range(1, 10) as $i)
                <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
                @endforeach
                </table>
            </div>
        </div>

        </div>
    </div>
        
</div>

<div class="card m-4">
    <div class="card-header">Even Numbers</div>
    <div class="card-body">
        @foreach (range(1,100) as $i)
            @if($i%2==0)
                <span class="badge bg-primary">{{$i}}</span>
            @else
                <span class="badge bg-secondary">{{$i}}</span>
            @endif
        @endforeach

    </div>
</div>

<div class="card m-4">
    <div class="card-header">Prime Number</div>
    <div class="card-body">
        @foreach(range(1, 50) as $num)
            @if(isPrime($num))
                <span class="badge bg-primary">{{$num}}</span>
            @else
                <span class="badge bg-secondary">{{$num}}</span>
            @endif
        @endforeach
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
