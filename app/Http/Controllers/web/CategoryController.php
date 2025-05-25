<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CategoryController extends Controller
{
    use ValidatesRequests;
    
    public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }
    
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
    }
    
    /**
     * Show the form for creating/editing a category.
     */
    public function edit(Category $category = null)
    {
        $category = $category ?? new Category();
        return view('categories.edit', compact('category'));
    }
    
    /**
     * Store a newly created or update an existing category.
     */
    public function save(Request $request, Category $category = null)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        
        $category = $category ?? new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        
        // Generate slug if this is a new category or the name has changed
        if (!$category->exists || $category->isDirty('name')) {
            $category->slug = $this->generateUniqueSlug($request->name, $category->id);
        }
        
        $category->save();
        
        return redirect()->route('categories.index')
            ->with('success', 'Category saved successfully.');
    }
    
    /**
     * Remove the specified category.
     */
    public function delete(Category $category)
    {
        // Update associated products to have null category_id (already set up in migration)
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
    
    /**
     * Generate a unique slug.
     */
    private function generateUniqueSlug($name, $exceptId = null)
    {
        $slug = Str::slug($name);
        $count = 1;
        
        // Check if slug exists
        while (Category::where('slug', $slug)
                ->when($exceptId, function($query) use ($exceptId) {
                    return $query->where('id', '!=', $exceptId);
                })
                ->exists()) {
            $slug = Str::slug($name) . '-' . $count++;
        }
        
        return $slug;
    }
}
