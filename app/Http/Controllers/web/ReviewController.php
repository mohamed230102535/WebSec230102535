<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ReviewController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web')->except(['index']);
    }

    /**
     * Display reviews for a product.
     */
    public function index($productId)
    {
        $product = Product::with(['reviews.user'])->findOrFail($productId);
        $reviews = $product->reviews()->with('user')->orderBy('created_at', 'desc')->paginate(10);
        
        $userReview = null;
        $canReview = false;
        
        if (auth()->check()) {
            $userReview = Review::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->first();
                
            // Permission check removed temporarily
            $canReview = (auth()->user()->hasPurchased($productId) || !$userReview);
        }
        
        return view('reviews.index', compact('product', 'reviews', 'userReview', 'canReview'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create($productId)
    {
        // Permission check removed temporarily
        
        $product = Product::findOrFail($productId);
        
        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();
            
        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this product. You can edit your review.');
        }
        
        return view('reviews.create', compact('product'));
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request, $productId)
    {
        // Permission check removed temporarily
        
        $this->validate($request, [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000']
        ]);
        
        $product = Product::findOrFail($productId);
        
        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();
            
        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this product. You can edit your review.');
        }
        
        // Check if review is verified (user has purchased the product)
        $isVerified = auth()->user()->hasPurchased($productId);
        
        $review = new Review();
        $review->user_id = auth()->id();
        $review->product_id = $productId;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->is_verified = $isVerified;
        $review->save();
        
        return redirect()->route('reviews.index', $productId)
            ->with('success', 'Review submitted successfully.');
    }

    /**
     * Show the form for editing a review.
     */
    public function edit($id)
    {
        $review = Review::with('product')->findOrFail($id);
        
        // Permission check removed temporarily
        // Now only checking if review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            return redirect()->route('products_list')
                ->with('error', 'You can only edit your own reviews.');
        }
        
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        // Permission check removed temporarily
        // Now only checking if review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            return redirect()->route('products_list')
                ->with('error', 'You can only update your own reviews.');
        }
        
        $this->validate($request, [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000']
        ]);
        
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        
        return redirect()->route('reviews.index', $review->product_id)
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $productId = $review->product_id;
        
        // Permission check removed temporarily
        // Now only checking if review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            return redirect()->route('products_list')
                ->with('error', 'You can only delete your own reviews.');
        }
        
        $review->delete();
        
        return redirect()->route('reviews.index', $productId)
            ->with('success', 'Review deleted successfully.');
    }
}
