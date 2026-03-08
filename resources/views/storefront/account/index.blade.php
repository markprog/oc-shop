@extends('layouts.storefront')

@section('title', 'My Account')

@section('content')

<h1 class="h3 mb-4">My Account</h1>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-bag me-2"></i>Orders</h5>
                <p class="card-text text-muted">View your order history and track shipments.</p>
                <a href="{{ route('account.order.index') }}" class="btn btn-outline-primary btn-sm">View Orders</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-geo-alt me-2"></i>Addresses</h5>
                <p class="card-text text-muted">Manage your delivery addresses.</p>
                <a href="{{ route('account.address.index') }}" class="btn btn-outline-primary btn-sm">Manage Addresses</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-heart me-2"></i>Wishlist</h5>
                <p class="card-text text-muted">View and manage your saved products.</p>
                <a href="{{ route('account.wishlist.index') }}" class="btn btn-outline-primary btn-sm">View Wishlist</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-arrow-counterclockwise me-2"></i>Returns</h5>
                <p class="card-text text-muted">Request and track product returns.</p>
                <a href="{{ route('account.return.index') }}" class="btn btn-outline-primary btn-sm">View Returns</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-download me-2"></i>Downloads</h5>
                <p class="card-text text-muted">Access your digital product downloads.</p>
                <a href="{{ route('account.download.index') }}" class="btn btn-outline-primary btn-sm">View Downloads</a>
            </div>
        </div>
    </div>
</div>

@endsection
