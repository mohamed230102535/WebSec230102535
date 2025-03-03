<?php
namespace App\Http\Controllers\Web;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class ProductsController extends Controller {
    public function list(Request $request) {
         $products = Product::all();
         return view("products.list", compact('products'));
        }
          
}
