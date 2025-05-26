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
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // If user has permission to view all orders, show all orders
        // For now, we're removing the permission check as requested
        $orders = $query->get();
        
        // Get all possible statuses for filtering
        $statuses = Order::getStatuses();
        
        return view('orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display a single order with details
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Since we're temporarily removing permission checks as requested,
        // we'll proceed directly with enhanced order view
        
        // Get available next statuses for the order status workflow
        $nextStatuses = $order->getNextStatuses();
        
        return view('orders.show', compact('order', 'nextStatuses'));
    }
    
    /**
     * Update order status
     * 
     * @param Request $request
     * @param Order|int $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $order)
    {
        // If $order is an ID, find the order
        if (!($order instanceof Order)) {
            $order = Order::findOrFail($order);
        }
        
        // Validate the request
        $this->validate($request, [
            'status' => 'required|string|in:' . implode(',', array_keys(Order::getStatuses())),
        ]);
        
        // Check permissions
        if (!auth()->user()->can('manage_orders') && 
            !($order->user_id === auth()->id() && 
              $request->status === 'cancelled' && 
              $order->status === 'pending' && 
              auth()->user()->can('cancel_own_orders'))) {
            return back()->with('error', 'You do not have permission to update this order.');
        }
        
        // Check if the requested status is a valid next status
        $nextStatuses = $order->getNextStatuses();
        if (!array_key_exists($request->status, $nextStatuses) && $request->status !== $order->status) {
            return back()->with('error', 'Invalid status transition');
        }
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();
        
        // If the order is cancelled, return items to inventory
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
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
        
        return back()->with('success', 'Order status updated successfully');
    }

    /**
     * Display the order management dashboard.
     */
    public function dashboard()
    {
        // Get order counts by status
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $processingOrdersCount = Order::where('status', 'processing')->count();
        $shippedOrdersCount = Order::where('status', 'shipped')->count();
        $deliveredOrdersCount = Order::where('status', 'delivered')->count();
        $cancelledOrdersCount = Order::where('status', 'cancelled')->count();
        
        // Overall stats
        $totalOrdersCount = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalUsersCount = \App\Models\User::count();
        $totalProductsCount = \App\Models\Product::count();
        $totalCategoriesCount = \App\Models\Category::count();
        
        // Monthly sales data for chart
        $monthlySales = Order::where('status', '!=', 'cancelled')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as order_count, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Format monthly data for charts
        $monthlyLabels = [];
        $monthlyData = [];
        $monthlyRevenue = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('M', mktime(0, 0, 0, $i, 1));
            $monthlyLabels[] = $monthName;
            
            $monthData = $monthlySales->firstWhere('month', $i);
            $monthlyData[] = $monthData ? $monthData->order_count : 0;
            $monthlyRevenue[] = $monthData ? $monthData->revenue : 0;
        }
        
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
            
        // Get low stock products
        $lowStockProducts = \App\Models\Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();
        
        // Get recent users
        $recentUsers = \App\Models\User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('orders.dashboard', compact(
            'pendingOrdersCount', 
            'processingOrdersCount',
            'shippedOrdersCount',
            'deliveredOrdersCount',
            'cancelledOrdersCount',
            'totalOrdersCount',
            'totalRevenue',
            'totalUsersCount',
            'totalProductsCount',
            'totalCategoriesCount',
            'recentOrders',
            'topProducts',
            'lowStockProducts',
            'recentUsers',
            'monthlyLabels',
            'monthlyData',
            'monthlyRevenue'
        ));
    }

    // Second updateStatus method was removed to fix the 'Cannot redeclare' error
}
