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

        // Categories
        $categories = collect([
            ['name' => 'Electronics'],
            ['name' => 'Books'],
            ['name' => 'Clothing'],
        ])->map(fn($data) => Category::firstOrCreate([
            'slug' => Str::slug($data['name'])
        ], [
            'name' => $data['name'],
            'is_active' => true,
        ]));

        // Brands
        $brands = collect([
            ['name' => 'Apple'],
            ['name' => 'Samsung'],
            ['name' => 'Nike'],
        ])->map(fn($data) => Brand::firstOrCreate([
            'slug' => Str::slug($data['name'])
        ], [
            'name' => $data['name'],
            'is_active' => true,
        ]));

        // Products
        $products = collect([
            ['name' => 'iPhone 15', 'category' => 'Electronics', 'brand' => 'Apple', 'price' => 999],
            ['name' => 'Galaxy S25', 'category' => 'Electronics', 'brand' => 'Samsung', 'price' => 899],
            ['name' => 'Air Max 2025', 'category' => 'Clothing', 'brand' => 'Nike', 'price' => 199],
        ])->map(function($data) use ($categories, $brands) {
            $category = $categories->firstWhere('name', $data['category']);
            $brand = $brands->firstWhere('name', $data['brand']);
            return Product::firstOrCreate([
                'slug' => Str::slug($data['name'])
            ], [
                'name' => $data['name'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'price' => $data['price'],
                'is_active' => true,
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
