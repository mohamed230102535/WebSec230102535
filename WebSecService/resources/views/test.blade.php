@extends('layouts.master')
@section('title', 'Test')
@section('content')
<div class="text-center mt-3">
        <a href="/" class="btn btn-primary btn-lg">🏠 Back to Home</a>
    </div>

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
    
@endsection