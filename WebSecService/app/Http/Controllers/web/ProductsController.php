<?php
namespace App\Http\Controllers\Web;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Purchase;


class ProductsController extends Controller {
    use HasRoles;
    
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
        if (!Auth::user()->hasPermissionTo('create')) {
            abort(404);
            }
        return view('WebAuthentication.products.productAdd');
    }
  

    public function edit(Product $product)
    {
        if (!Auth::user()->hasPermissionTo('edit')) {
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
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
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
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        // Handle photo update
        if ($request->hasFile('photo')) {
      
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }
            $path = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $path;
        }

        $product->update($validated);

        return redirect()->route('WebAuthentication.products')->with('success', 'Product updated successfully!');
    }
    public function delete(Product $product)
    {
        if (!Auth::user()->hasPermissionTo('delete')) {
            abort(404);
            }
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return redirect()->route('WebAuthentication.products')->with('success', 'Product deleted successfully!');
    }

    public function purchase(Product $product)
    {
        $user = Auth::user();
        
        // Basic validation checks
        if ($user->credit < $product->price) {
            return redirect()->back()->with('error', 'Not enough credits to buy this product.');
        }

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'This product is out of stock.');
        }

        try {
            DB::beginTransaction();

            // Update user credit
            DB::table('onehitpoint')
                ->where('id', $user->id)
                ->update(['credit' => $user->credit - $product->price]);

            // Update product stock
            $product->stock = $product->stock - 1;
            $product->save();

            // Record the purchase
            Purchase::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'purchased_at' => now()
            ]);

            DB::commit();

            // Refresh user data to get updated credit
            $user->refresh();

            return redirect()->back()->with('success', 'Purchase successful! Your new balance is ' . number_format($user->credit, 2) . ' credits.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function purchaseHistory()
    {
        $user = Auth::user();
        $purchases = Purchase::with('product')
            ->where('user_id', $user->id)
            ->orderBy('purchased_at', 'desc')
            ->get();

        return view('WebAuthentication.products.purchaseHistory', compact('purchases'));
    }
}
