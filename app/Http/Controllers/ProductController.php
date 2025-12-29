<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\Category;
use \App\Models\Brand;
class ProductController extends Controller {
    public function index(Request $request) {
        $query = Product::with(['brand', 'category'])->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$term}%"));
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Sort
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'best_seller':
                $query->withCount(['orderItems as total_sold' => function($q) { $q->select(DB::raw('SUM(quantity)')); }])
                      ->orderByDesc('total_sold');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }
    public function show(Product $product) {
        $product->increment('views');
        return view('products.show', compact('product'));
    }
}
