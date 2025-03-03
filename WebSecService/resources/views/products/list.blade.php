@extends('layouts.master')

@section('title', 'Product List')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Product List</h2>
    <table class="table table-bordered">
        <thead class="bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Model</th>
                <th>Photo</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->model }}</td>
                <td><img src="{{ asset('images/' . $product->photo) }}" width="100"></td>
                <td>{{ $product->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
