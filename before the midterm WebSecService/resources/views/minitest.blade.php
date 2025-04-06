@extends('layouts.master')
@section('title', 'Supermarket Bill')
@section('content')

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Supermarket Bill</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="bill-card">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $finalTotal = 0; @endphp
                    @foreach($bill as $item)  
                        @php 
                            $total = $item['quantity'] * $item['price'];
                            $finalTotal += $total;
                        @endphp
                        <tr>
                            <td>{{ $item['item'] }}</td> 
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td>${{ number_format($total, 2) }}</td>  
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-end">Final Total:</td>
                        <td>${{ number_format($finalTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="/" class="btn btn-primary btn-lg">🏠 Back to Home</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection