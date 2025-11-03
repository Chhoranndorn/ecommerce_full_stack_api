@extends('web.layout')

@section('title', $product->name . ' - E-Commerce Store')

@section('content')
<div class="bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-8">
            <a href="/" class="text-indigo-600 hover:text-indigo-700">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="/products" class="text-indigo-600 hover:text-indigo-700">Products</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-600">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div>
                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                @if($product->category)
                <div class="mb-4">
                    <a href="/products?category={{ $product->category->id }}" class="inline-block bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $product->category->name }}
                    </a>
                </div>
                @endif

                <div class="mb-6">
                    <span class="text-4xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                </div>

                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-2">Description</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Add to Cart Section -->
                <div class="border-t border-gray-200 pt-8">
                    <div class="flex items-center gap-4 mb-6">
                        <label class="font-semibold">Quantity:</label>
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button class="px-4 py-2 hover:bg-gray-100" onclick="decrementQty()">-</button>
                            <input type="number" id="quantity" value="1" min="1" class="w-16 text-center border-0 focus:ring-0">
                            <button class="px-4 py-2 hover:bg-gray-100" onclick="incrementQty()">+</button>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button class="flex-1 bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Add to Cart
                        </button>
                        <button class="bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <h3 class="font-semibold mb-4">Product Information</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>SKU:</span>
                            <span class="font-medium text-gray-900">{{ $product->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Availability:</span>
                            <span class="font-medium text-green-600">In Stock</span>
                        </div>
                        @if($product->category)
                        <div class="flex justify-between">
                            <span>Category:</span>
                            <span class="font-medium text-gray-900">{{ $product->category->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="/products/{{ $related->id }}">
                        <div class="aspect-square bg-gray-200 relative">
                            @if($related->image)
                                <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $related->name }}</h3>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-indigo-600">${{ number_format($related->price, 2) }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function incrementQty() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value) + 1;
}

function decrementQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endsection
