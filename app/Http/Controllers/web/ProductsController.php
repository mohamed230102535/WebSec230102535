<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Cart;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");

		$query->when($request->keywords, 
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null) {

		if(!auth()->user()) return redirect('/');

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

public function save(Request $request, Product $product = null) {

    $this->validate($request, [
        'code' => ['required', 'string', 'max:64'],
        'name' => ['required', 'string', 'max:256'],
        'model' => ['required', 'string', 'max:128'],
        'description' => ['nullable', 'string', 'max:1024'],
        'price' => ['required', 'numeric', 'min:0'],
        'stock' => ['required', 'integer', 'min:0'],
    ]);

    $product = $product ?? new Product();
    $product->fill($request->all());
    $product->save();

    return redirect()->route('products_list');
}


	public function delete(Request $request, Product $product) {

		// Permission check removed temporarily
		// Previously checked for delete_products permission

		$product->delete();

		return redirect()->route('products_list');
	}

	public function purchaseProduct(Request $request, $productId)
	{
		// Redirect to the new cart system instead of direct purchase
		if (!auth()->check()) {
			return back()->with('error', 'You must be logged in to make a purchase.');
		}
		
		// Check if product exists
		$product = Product::find($productId);
		if (!$product) {
			return back()->with('error', 'Product not found.');
		}
		
		// Check stock
		if ($product->stock <= 0) {
			return back()->with('error', 'This product is out of stock.');
		}
		
		// Add to cart instead of direct purchase
		$existingCartItem = Cart::where('user_id', auth()->id())
			->where('product_id', $productId)
			->first();
			
		if ($existingCartItem) {
			// Update quantity if product already in cart
			$existingCartItem->quantity += 1;
			$existingCartItem->save();
		} else {
			// Add new cart item
			Cart::create([
				'user_id' => auth()->id(),
				'product_id' => $productId,
				'quantity' => 1,
				'price' => $product->price
			]);
		}
		
		// Redirect to cart page
		return redirect()->route('cart.index')
			->with('success', 'Product added to cart. Complete the checkout process to purchase.');
	}
	
}