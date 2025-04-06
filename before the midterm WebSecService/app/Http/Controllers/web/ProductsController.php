<?php
namespace App\Http\Controllers\Web;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class ProductsController extends Controller {
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
        
         return view("WebAuthentication.products.list", compact('products'));
        }

        public function create()
    {
        if (!auth()->user()->hasPermissionTo('create')) {
            abort(404);
            }
        return view('WebAuthentication.products.productAdd');
    }
  

    public function edit(Product $product)
    {
        if (!auth()->user()->hasPermissionTo('edit')) {
            abort(404);
            }
        return view('WebAuthentication.products.productEdit', compact('product'));
    }

  

     public function doCreate(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:50',
            'photo' => 'required|image|max:2048', 
            'description' => 'nullable|string',
        ]);

       
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $path;
        }

        Product::create($validated);

        return redirect()->route('WebAuthentication.products')->with('success', 'Product created successfully!');
    }  

    public function doEdit(Product $product, Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:50',
            'photo' => 'nullable|image|max:2048', // Optional photo update
            'description' => 'nullable|string',
        ]);

        // Handle photo update
        if ($request->hasFile('photo')) {
      
            if ($product->photo && \Storage::disk('public')->exists($product->photo)) {
                \Storage::disk('public')->delete($product->photo);
            }
            $path = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $path;
        }

        $product->update($validated);

        return redirect()->route('WebAuthentication.products')->with('success', 'Product updated successfully!');
    }
    public function delete(Product $product)
    {
        if (!auth()->user()->hasPermissionTo('delete')) {
            abort(404);
            }
        if ($product->photo && \Storage::disk('public')->exists($product->photo)) {
            \Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return redirect()->route('WebAuthentication.products')->with('success', 'Product deleted successfully!');
    }
}
