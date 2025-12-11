<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SeederDemo extends Seeder
{
    public function run(): void
    {
        // User
        $user = User::firstOrCreate([
            'email' => 'demo@example.com',
        ], [
            'name' => 'Demo User',
            'password' => Hash::make('password'),
        ]);

        // Categories (tối thiểu 10)
        $categoryNames = [
            'Electronics', 'Books', 'Clothing', 'Shoes', 'Home', 'Toys', 'Beauty', 'Sports', 'Automotive', 'Garden',
        ];
        $categories = collect($categoryNames)->map(fn($name) => Category::firstOrCreate([
            'slug' => Str::slug($name)
        ], [
            'name' => $name,
            'is_active' => true,
        ]));

        // Brands (tối thiểu 5)
        $brandNames = [
            'Apple', 'Samsung', 'Nike', 'Adidas', 'Sony',
        ];
        $brands = collect($brandNames)->map(fn($name) => Brand::firstOrCreate([
            'slug' => Str::slug($name)
        ], [
            'name' => $name,
            'is_active' => true,
        ]));

        // Products (tối thiểu 20)
        $productSeed = [
            ['iPhone 15', 'Electronics', 'Apple', 999],
            ['Galaxy S25', 'Electronics', 'Samsung', 899],
            ['Air Max 2025', 'Clothing', 'Nike', 199],
            ['PlayStation 6', 'Electronics', 'Sony', 599],
            ['MacBook Pro 2025', 'Electronics', 'Apple', 1999],
            ['Kindle Paperwhite', 'Books', 'Amazon', 129],
            ['Ultraboost 2025', 'Shoes', 'Adidas', 180],
            ['Smart TV 8K', 'Electronics', 'Samsung', 1200],
            ['Running Shoes', 'Shoes', 'Nike', 150],
            ['Bluetooth Speaker', 'Electronics', 'Sony', 99],
            ['Garden Chair', 'Garden', 'Adidas', 60],
            ['Soccer Ball', 'Sports', 'Nike', 30],
            ['Lipstick', 'Beauty', 'Apple', 25],
            ['Toy Car', 'Toys', 'Samsung', 40],
            ['Cookware Set', 'Home', 'Sony', 200],
            ['Car Vacuum', 'Automotive', 'Samsung', 80],
            ['Yoga Mat', 'Sports', 'Adidas', 35],
            ['Novel Book', 'Books', 'Apple', 20],
            ['Garden Tools', 'Garden', 'Sony', 70],
            ['T-shirt', 'Clothing', 'Nike', 25],
        ];
        $products = collect($productSeed)->map(function($item, $idx) use ($categories, $brands) {
            [$name, $catName, $brandName, $price] = $item;
            $category = $categories->firstWhere('name', $catName);
            $brand = $brands->firstWhere('name', $brandName) ?? $brands->first();
            $description = 'Sản phẩm ' . $name . ' là lựa chọn tuyệt vời trong danh mục ' . $catName . ' của hãng ' . $brandName . '. Với thiết kế hiện đại, chất lượng đảm bảo và giá thành hợp lý, sản phẩm này đáp ứng tốt nhu cầu sử dụng hàng ngày cũng như các mục đích chuyên dụng. Được sản xuất bởi thương hiệu uy tín, ' . $name . ' mang lại trải nghiệm vượt trội cho người dùng. Hãy khám phá ngay để tận hưởng những tính năng nổi bật và ưu đãi hấp dẫn.';
            return Product::firstOrCreate([
                'slug' => Str::slug($name)
            ], [
                'name' => $name,
                'category_id' => $category ? $category->id : $categories->first()->id,
                'brand_id' => $brand->id,
                'price' => $price,
                'is_active' => true,
                'description' => $description,
            ]);
        });

        // Orders + OrderItems + Addresses
        for ($i = 1; $i <= 3; $i++) {
            $order = Order::create([
                'user_id' => $user->id,
                'grand_total' => $products->sum('price'),
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'status' => 'new',
                'currency' => 'USD',
                'shipping_amount' => 10,
                'shipping_method' => 'standard',
                'notes' => 'Demo order #' . $i,
            ]);
            foreach ($products as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ]);
            }
            Address::create([
                'order_id' => $order->id,
                'first_name' => 'Demo',
                'last_name' => 'User',
                'phone' => '0123456789',
                'street_address' => '123 Demo St',
                'city' => 'Demo City',
                'state' => 'Demo State',
                'zip_code' => '12345',
            ]);
        }
    }
}
