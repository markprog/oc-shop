<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('shop.name', 'Shop') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f0f2f5; }
        .sidebar { min-height: 100vh; background: #343a40; }
        .sidebar .nav-link { color: rgba(255,255,255,.75); }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); border-radius: 4px; }
        .sidebar .nav-link i { width: 20px; }
        .sidebar-heading { font-size: .75rem; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.4); }
        .main-content { padding: 1.5rem; }
    </style>
    @stack('styles')
</head>
<body>

<div class="d-flex">
    {{-- Sidebar --}}
    <div class="sidebar col-md-2 d-none d-md-flex flex-column p-3">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <i class="bi bi-shop me-2"></i>
            <span class="fw-bold">{{ config('shop.name', 'Admin') }}</span>
        </a>
        <hr class="text-secondary">

        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item mt-2">
                <span class="sidebar-heading">Catalog</span>
            </li>
            <li><a class="nav-link" href="{{ route('admin.catalog.product.index') }}"><i class="bi bi-box-seam"></i> Products</a></li>
            <li><a class="nav-link" href="{{ route('admin.catalog.category.index') }}"><i class="bi bi-folder"></i> Categories</a></li>
            <li><a class="nav-link" href="{{ route('admin.catalog.manufacturer.index') }}"><i class="bi bi-building"></i> Manufacturers</a></li>
            <li><a class="nav-link" href="{{ route('admin.catalog.option.index') }}"><i class="bi bi-sliders"></i> Options</a></li>
            <li><a class="nav-link" href="{{ route('admin.catalog.attribute.index') }}"><i class="bi bi-tags"></i> Attributes</a></li>
            <li><a class="nav-link" href="{{ route('admin.catalog.review.index') }}"><i class="bi bi-star"></i> Reviews</a></li>
            <li><a class="nav-link" href="{{ route('admin.catalog.download.index') }}"><i class="bi bi-download"></i> Downloads</a></li>

            <li class="nav-item mt-2">
                <span class="sidebar-heading">Sales</span>
            </li>
            <li><a class="nav-link" href="{{ route('admin.sale.order.index') }}"><i class="bi bi-bag"></i> Orders</a></li>
            <li><a class="nav-link" href="{{ route('admin.sale.return.index') }}"><i class="bi bi-arrow-counterclockwise"></i> Returns</a></li>
            <li><a class="nav-link" href="{{ route('admin.sale.subscription.index') }}"><i class="bi bi-repeat"></i> Subscriptions</a></li>

            <li class="nav-item mt-2">
                <span class="sidebar-heading">Customers</span>
            </li>
            <li><a class="nav-link" href="{{ route('admin.customer.customer.index') }}"><i class="bi bi-people"></i> Customers</a></li>
            <li><a class="nav-link" href="{{ route('admin.customer.customer-group.index') }}"><i class="bi bi-person-badge"></i> Groups</a></li>

            <li class="nav-item mt-2">
                <span class="sidebar-heading">Marketing</span>
            </li>
            <li><a class="nav-link" href="{{ route('admin.marketing.coupon.index') }}"><i class="bi bi-ticket-perforated"></i> Coupons</a></li>
            <li><a class="nav-link" href="{{ route('admin.marketing.affiliate.index') }}"><i class="bi bi-share"></i> Affiliates</a></li>

            <li class="nav-item mt-2">
                <span class="sidebar-heading">Design</span>
            </li>
            <li><a class="nav-link" href="{{ route('admin.design.banner.index') }}"><i class="bi bi-image"></i> Banners</a></li>
            <li><a class="nav-link" href="{{ route('admin.design.layout.index') }}"><i class="bi bi-layout-text-sidebar"></i> Layouts</a></li>
            <li><a class="nav-link" href="{{ route('admin.design.seo-url.index') }}"><i class="bi bi-link-45deg"></i> SEO URLs</a></li>

            <li class="nav-item mt-2">
                <span class="sidebar-heading">System</span>
            </li>
            <li><a class="nav-link" href="{{ route('admin.setting.general') }}"><i class="bi bi-gear"></i> Settings</a></li>
            <li><a class="nav-link" href="{{ route('admin.user.user.index') }}"><i class="bi bi-person-lock"></i> Users</a></li>
            <li><a class="nav-link" href="{{ route('admin.tool.error-log') }}"><i class="bi bi-journal-x"></i> Error Log</a></li>
        </ul>
    </div>

    {{-- Main content --}}
    <div class="flex-grow-1">
        {{-- Top nav --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-2">
            <span class="navbar-brand fw-bold">@yield('title', 'Dashboard')</span>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-muted small">{{ auth('admin')->user()?->firstname }} {{ auth('admin')->user()?->lastname }}</span>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Logout</button>
                </form>
            </div>
        </nav>

        {{-- Alerts --}}
        <div class="main-content">
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

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
