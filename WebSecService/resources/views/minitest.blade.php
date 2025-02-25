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
            <a class="navbar-brand fw-bold" href="#">Supermarket Bill</a>
        </div>
    </nav>

    <div class="container text-center mt-4">
        <h2 class="fw-bold">Supermarket <span class="badge bg-secondary">Bill</span></h2>
    </div>

    
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
