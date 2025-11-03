<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Commerce Store')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600">E-Shop</a>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="/" class="text-gray-900 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Home</a>
                        <a href="/products" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Products</a>
                        <a href="/about" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">About</a>
                        <a href="/contact" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Contact</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-sm text-gray-700">Hello, {{ auth()->user()->name }}</span>
                        <a href="/profile" class="text-gray-700 hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-indigo-600 text-sm">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="text-gray-700 hover:text-indigo-600 text-sm font-medium">Login</a>
                        <a href="/register" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">E-Shop</h3>
                    <p class="text-gray-400 text-sm">Your trusted online shopping destination for quality products at great prices.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/products" class="hover:text-white">Shop</a></li>
                        <li><a href="/about" class="hover:text-white">About Us</a></li>
                        <li><a href="/contact" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Customer Service</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-white">Returns</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <p class="text-sm text-gray-400">Email: info@eshop.com</p>
                    <p class="text-sm text-gray-400">Phone: +855 123 456 789</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2025 E-Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
