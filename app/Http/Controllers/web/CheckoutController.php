<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web');
        // Permission middleware removed temporarily
    }

    /**
     * Show the checkout page.
     */
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process the checkout and create an order.
     */
    public function process(Request $request)
    {
        // Validate request - modified to include all new form fields
        $this->validate($request, [
            'delivery_method' => ['required', 'in:home_delivery,store_pickup'],
            'payment_method' => ['required', 'in:credit_system,credit_card,paypal'],
            
            // Home delivery fields - only required when delivery method is home_delivery
            'first_name' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:100'],
            'last_name' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:100'],
            'phone' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:20'],
            'email' => ['required_if:delivery_method,home_delivery', 'nullable', 'email'],
            'street_address' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:100'],
            'state' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:50'],
            'zip' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:20'],
            'delivery_notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();
        
        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        // Calculate order total
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        // Check for stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Not enough stock for {$item->product->name}.");
            }
        }
        
        // Check if using credit system and has enough credit
        if ($request->payment_method === 'credit_system') {
            if ($user->credit < $total) {
                return back()->with('error', 'You do not have enough credit to complete this purchase.');
            }
        }
        
        // Process the order
        try {
            DB::beginTransaction();
            
            // Create the order
            $order = new Order();
            $order->order_number = Order::generateOrderNumber();
            $order->user_id = $user->id;
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->payment_method = $request->payment_method;
            $order->delivery_method = $request->delivery_method;
            
            // Format shipping address based on delivery method
            if ($request->delivery_method === 'home_delivery') {
                // Format the full address from individual fields
                $shippingAddress = "{$request->first_name} {$request->last_name}\n";
                $shippingAddress .= "{$request->street_address}\n";
                
                if (!empty($request->address_line2)) {
                    $shippingAddress .= "{$request->address_line2}\n";
                }
                
                $shippingAddress .= "{$request->city}, {$request->state} {$request->zip}\n";
                $shippingAddress .= "Phone: {$request->phone}\n";
                $shippingAddress .= "Email: {$request->email}";
                
                if (!empty($request->delivery_notes)) {
                    $shippingAddress .= "\n\nDelivery Notes: {$request->delivery_notes}";
                }
                
                $order->shipping_address = $shippingAddress;
            } else {
                // Store pickup - no shipping address needed
                $order->shipping_address = 'Store Pickup at WebSec Service Headquarters';
            }
            
            $order->save();
            
            // Create order items and update product stock
            foreach ($cartItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->product_id;
                $orderItem->quantity = $item->quantity;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->quantity;
                $orderItem->save();
                
                // Update product stock
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }
            
            // If using credit system, deduct from user's credit
            if ($request->payment_method === 'credit_system') {
                $user->credit -= $total;
                $user->save();
            }
            
            // Clear the cart
            Cart::where('user_id', $user->id)->delete();
            
            DB::commit();
            
            return redirect()->route('checkout.success', ['order' => $order->id]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    /**
     * Display the checkout success page.
     */
    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // Check if order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('orders.index')->with('error', 'You do not have permission to view this order.');
        }
        
        return view('checkout.success', compact('order'));
    }
}
