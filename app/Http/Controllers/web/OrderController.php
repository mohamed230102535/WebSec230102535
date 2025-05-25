<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web');
        // Permission middleware removed temporarily
    }

    /**
     * Display a listing of user orders.
     */
    public function index()
    {
        // If user has permission to view all orders, show all orders
        if (auth()->user()->can('view_all_orders')) {
            $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
            return view('orders.index', compact('orders'));
        }
        
        // Otherwise, show only the user's orders
        if (auth()->user()->can('view_own_orders')) {
            $orders = auth()->user()->orders()->orderBy('created_at', 'desc')->get();
            return view('orders.index', compact('orders'));
        }
        
        return redirect('/')->with('error', 'You do not have permission to view orders.');
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Check if user has permission to view this order
        if (auth()->id() === $order->user_id && auth()->user()->can('view_own_orders')) {
            return view('orders.show', compact('order'));
        } else if (auth()->user()->can('view_all_orders')) {
            return view('orders.show', compact('order'));
        }
        
        return redirect('/')->with('error', 'You do not have permission to view this order.');
    }

    /**
     * Display the order management dashboard.
     */
    public function dashboard()
    {
        // Get order counts by status
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $processingOrdersCount = Order::where('status', 'processing')->count();
        $deliveredOrdersCount = Order::where('status', 'delivered')->count();
        $cancelledOrdersCount = Order::where('status', 'cancelled')->count();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get top selling products
        $topProducts = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.stock', 
                'products.photo',
                DB::raw('COUNT(order_items.id) as total_sales'),
                DB::raw('SUM(order_items.total) as revenue')
            )
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->where(function ($query) {
                $query->whereNull('orders.status')
                    ->orWhere('orders.status', '!=', 'cancelled');
            })
            ->groupBy('products.id', 'products.name', 'products.price', 'products.stock', 'products.photo')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();
        
        return view('orders.dashboard', compact(
            'pendingOrdersCount', 
            'processingOrdersCount', 
            'deliveredOrdersCount',
            'cancelledOrdersCount',
            'recentOrders',
            'topProducts'
        ));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled']
        ]);
        
        $order = Order::findOrFail($id);
        
        // Check permissions
        if (!auth()->user()->can('manage_orders') && 
            !($order->user_id === auth()->id() && 
              $request->status === 'cancelled' && 
              $order->status === 'pending' && 
              auth()->user()->can('cancel_own_orders'))) {
            return back()->with('error', 'You do not have permission to update this order.');
        }
        
        // If order is being cancelled and it was previously not cancelled, restore product stock
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->stock += $item->quantity;
                $product->save();
            }
            
            // If order was paid with credit system, refund the credit
            if ($order->payment_method === 'credit_system') {
                $user = $order->user;
                $user->credit += $order->total_amount;
                $user->save();
            }
        }
        
        $order->status = $request->status;
        $order->save();
        
        return back()->with('success', 'Order status updated successfully.');
    }
}
