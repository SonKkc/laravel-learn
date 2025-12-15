<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function show($id)
    {
        $category = Category::with('products.brand')->findOrFail($id);
        $products = $category->products()->with('brand')->where('is_active', true)->get();
        return view('categories.detail', compact('category', 'products'));
    }
}
