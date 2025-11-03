@extends('web.layout')

@section('title', 'Home - E-Commerce Store')

@section('content')
<!-- Hero Banner Slider -->
@if($banners->count() > 0)
<div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 h-96">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
        <div class="text-white">
            <h1 class="text-5xl font-bold mb-4">{{ $banners->first()->title }}</h1>
            <p class="text-xl mb-6">Discover amazing products at unbeatable prices</p>
            <a href="/products" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Shop Now
            </a>
        </div>
    </div>
</div>
@endif

<!-- Categories -->
@if($categories->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Shop by Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach($categories as $category)
        <a href="/products?category={{ $category->id }}" class="group">
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-full flex items-center justify-center">
                    @if($category->icon)
                        <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}" class="w-10 h-10">
                    @else
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    @endif
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600">{{ $category->name }}</h3>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Featured Products</h2>
            <a href="/products" class="text-indigo-600 hover:text-indigo-700 font-medium">View All →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <a href="/products/{{ $product->id }}">
                    <div class="aspect-square bg-gray-200 relative">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        @if($product->is_featured)
                        <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">Featured</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Specials / Promotions -->
@if($specials->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Special Offers</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($specials as $special)
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg overflow-hidden shadow-lg">
            <div class="p-6 text-white">
                <h3 class="text-2xl font-bold mb-2">{{ $special->name }}</h3>
                <p class="mb-4">Limited time offer!</p>
                <a href="/products" class="inline-block bg-white text-purple-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Shop Now
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Features Section -->
<section class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-xl mb-2">Best Prices</h3>
                <p class="text-gray-600">Competitive prices on all products</p>
            </div>
            <div>
                <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-xl mb-2">Fast Delivery</h3>
                <p class="text-gray-600">Quick and reliable shipping</p>
            </div>
            <div>
                <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-xl mb-2">Secure Payment</h3>
                <p class="text-gray-600">Safe and secure transactions</p>
            </div>
        </div>
    </div>
</section>
@endsection
