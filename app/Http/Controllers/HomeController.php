<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\OrderItem;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả sản phẩm is_active cho hero_section
        $products = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        // Lấy 1 sản phẩm đầu tiên cho mỗi category
        $productsByCategory = [];
        $categories = Category::all();
        foreach ($categories as $category) {
            $product = $category->products()->with('brand')->orderByDesc('created_at')->first();
            if ($product) {
                $productsByCategory[$category->name] = $product;
            }
        }

        // Sản phẩm nổi bật (is_featured)
        $featuredProducts = Product::with(['brand'])
            ->where('is_featured', true)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        // Lấy 6 brand active để hiển thị ở phần trending trên trang home
        $brands = Brand::where('is_active', true)
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        // Category có số lượng bán nhiều nhất
        $topCategoryId = OrderItem::selectRaw('product_id, SUM(quantity) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->get()
            ->pluck('product_id')
            ->map(function($productId){
                return Product::find($productId)->category_id ?? null;
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();

        // Sản phẩm có số lượng mua nhiều nhất
        $mostBuy = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->whereIn('id', OrderItem::selectRaw('product_id, SUM(quantity) as total')
                ->groupBy('product_id')
                ->orderByDesc('total')
                ->take(6)
                ->pluck('product_id'))
            ->get();

        // Sản phẩm có lượt xem nhiều nhất
        $mostView = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->orderByDesc('views')
            ->take(6)
            ->get();

        return view('home', [
            'products' => $products,
            'productsByCategory' => $productsByCategory,
            'brands' => $brands,
            'featuredProducts' => $featuredProducts,
            'mostBuy' => $mostBuy,
            'mostView' => $mostView,
        ]);
    }
}
