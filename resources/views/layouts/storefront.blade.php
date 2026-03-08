<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('shop.name', 'Shop'))</title>
    <meta name="description" content="@yield('meta_description', '')">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    @stack('styles')
</head>
<body>

{{-- Top bar --}}
<div class="bg-dark text-light py-1">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="small">
            @auth('web')
                Welcome, {{ auth('web')->user()->firstname }}!
                <a href="{{ route('account.index') }}" class="text-light ms-2">My Account</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm text-light p-0">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-light">Login</a>
                <a href="{{ route('register') }}" class="text-light ms-2">Register</a>
            @endauth
        </div>
        <div class="small">
            <a href="#" class="text-light" id="currency-toggle">
                {{ session('currency', config('shop.currency', 'USD')) }}
            </a>
        </div>
    </div>
</div>

{{-- Header --}}
<header class="bg-white shadow-sm py-3">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="text-decoration-none">
            <h1 class="h4 mb-0 text-dark">{{ config('shop.name', 'Shop') }}</h1>
        </a>

        {{-- Search --}}
        <form action="{{ route('search') }}" method="GET" class="d-flex w-50">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="bi bi-search"></i>
            </button>
        </form>

        {{-- Cart icon --}}
        <a href="{{ route('cart.index') }}" class="btn btn-outline-dark position-relative">
            <i class="bi bi-cart3"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                0
            </span>
        </a>
    </div>
</header>

{{-- Navigation --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                @foreach($navCategories ?? [] as $cat)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('category.show', $cat->category_id) }}">
                        {{ $cat->description?->name }}
                    </a>
                </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog.index') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Flash messages --}}
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

{{-- Main content --}}
<main class="container py-4">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h6 class="text-uppercase fw-bold">Information</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('contact') }}" class="text-light">Contact Us</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-light">Blog</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-uppercase fw-bold">My Account</h6>
                <ul class="list-unstyled">
                    @auth('web')
                        <li><a href="{{ route('account.index') }}" class="text-light">Account</a></li>
                        <li><a href="{{ route('account.order.index') }}" class="text-light">Orders</a></li>
                        <li><a href="{{ route('account.wishlist.index') }}" class="text-light">Wishlist</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-light">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-light">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="mb-0 text-center small">
            &copy; {{ date('Y') }} {{ config('shop.name', 'Shop') }}. All rights reserved.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/storefront.js') }}"></script>
@stack('scripts')
</body>
</html>
