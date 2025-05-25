<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CartController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web');
        // Permission middleware removed temporarily
    }

    /**
     * Display the cart contents.
     */
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, $productId)
    {
        $this->validate($request, [
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $product = Product::findOrFail($productId);
        
        // Check if product is in stock
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }
        
        // Check if product already in cart
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();
            
        if ($cartItem) {
            // Update quantity if product already in cart
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Add new cart item
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }
        
        return back()->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'quantity' => ['required', 'integer', 'min:1']
        ]);
        
        $cartItem = Cart::findOrFail($id);
        
        // Check if cart item belongs to authenticated user
        if ($cartItem->user_id !== auth()->id()) {
            return back()->with('error', 'You do not have permission to update this cart item.');
        }
        
        // Check if product is in stock
        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        
        // Check if cart item belongs to authenticated user
        if ($cartItem->user_id !== auth()->id()) {
            return back()->with('error', 'You do not have permission to remove this cart item.');
        }
        
        $cartItem->delete();
        
        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        // Delete all cart items for the authenticated user
        Cart::where('user_id', auth()->id())->delete();
        
        return back()->with('success', 'Cart cleared successfully.');
    }
}
