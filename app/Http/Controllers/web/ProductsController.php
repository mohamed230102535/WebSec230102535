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

		$query = Product::select("products.*")
			->with('category') // Eager load categories
			->withCount('reviews')
			->withAvg('reviews', 'rating');

		// Filter by category slug
		$query->when($request->category, function($q) use ($request) {
			$q->whereHas('category', function($query) use ($request) {
				$query->where('slug', $request->category);
			});
		});

		// Filter by category ID
		$query->when($request->category_id, function($q) use ($request) {
			$q->where('category_id', $request->category_id);
		});

		$query->when($request->keywords, 
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		// Get all categories for the filter dropdown
		$categories = \App\Models\Category::withCount('products')->get();

		return view('products.list', compact('products', 'categories'));
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
        'category_id' => ['nullable', 'exists:categories,id'],
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