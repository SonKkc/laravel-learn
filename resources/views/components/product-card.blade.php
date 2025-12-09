{{-- resources/views/components/product-card.blade.php --}}
@props([
  'product' => (object) [
    'id' => 0,
    'name' => 'Sản phẩm demo',
    'slug' => 'san-pham-demo',
    'image_url' => 'https://placehold.co/400x300',
    'price' => 0,
    'old_price' => null,
    'short_description' => 'Mô tả sản phẩm mẫu.',
  ],
  'route' => null
])

<article class="glass glass-hover:lift overflow-hidden relative group">
  <a href="#" class="block">
    <div class="aspect-w-4 aspect-h-3">
      <img
        src="{{ $product->image_url }}"
        alt="{{ $product->name }}"
        loading="lazy"
        class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105"
      />
    </div>
    <div class="p-4">
      <h3 class="text-sm font-semibold text-gray-900">{{ $product->name }}</h3>
      <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $product->short_description ?? '' }}</p>
      <div class="mt-3 flex items-center justify-between">
        <div>
          <div class="text-lg font-bold text-primary">{{ number_format($product->price, 0, ',', '.') }}₫</div>
          @if(isset($product->old_price))
            <div class="text-xs line-through text-gray-400">{{ number_format($product->old_price,0,',','.') }}₫</div>
          @endif
        </div>
        <div class="opacity-0 group-hover:opacity-100 transition-opacity">
          <button
            x-data
            x-on:click.prevent="$dispatch('add-to-cart', {{ json_encode(['id'=>$product->id, 'name'=>$product->name, 'price'=>$product->price]) }})"
            class="inline-flex items-center px-3 py-2 rounded-lg bg-primary text-black text-sm shadow"
          >
            Thêm
          </button>
        </div>
      </div>
    </div>
  </a>
</article>
