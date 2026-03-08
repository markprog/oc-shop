@extends('layouts.storefront')

@section('title', config('shop.name', 'Shop') . ' — Home')

@section('content')

{{-- Banner --}}
@if($banners->isNotEmpty())
<div id="bannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($banners as $i => $banner)
        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
            <img src="{{ asset('image/' . $banner->image) }}" class="d-block w-100" style="max-height:400px;object-fit:cover" alt="{{ $banner->title }}">
            @if($banner->title)
            <div class="carousel-caption d-none d-md-block">
                <h5>{{ $banner->title }}</h5>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
@endif

{{-- Featured Products --}}
<h2 class="h4 mb-3">Featured Products</h2>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mb-5">
    @foreach($featuredProducts as $product)
    <div class="col">
        <div class="card h-100 shadow-sm">
            <a href="{{ route('product.show', $product->product_id) }}">
                <img src="{{ $product->image ? asset('image/' . $product->image) : asset('image/no-image.png') }}"
                     class="card-img-top" style="height:200px;object-fit:cover"
                     alt="{{ $product->description?->name }}">
            </a>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">
                    <a href="{{ route('product.show', $product->product_id) }}" class="text-dark text-decoration-none">
                        {{ $product->description?->name }}
                    </a>
                </h6>
                <p class="text-muted small mb-1">{{ $product->description?->short_description }}</p>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                    <strong class="text-primary">${{ number_format($product->price, 2) }}</strong>
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Latest Products --}}
<h2 class="h4 mb-3">Latest Products</h2>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
    @foreach($latestProducts as $product)
    <div class="col">
        <div class="card h-100 shadow-sm">
            <a href="{{ route('product.show', $product->product_id) }}">
                <img src="{{ $product->image ? asset('image/' . $product->image) : asset('image/no-image.png') }}"
                     class="card-img-top" style="height:200px;object-fit:cover"
                     alt="{{ $product->description?->name }}">
            </a>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">
                    <a href="{{ route('product.show', $product->product_id) }}" class="text-dark text-decoration-none">
                        {{ $product->description?->name }}
                    </a>
                </h6>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                    <strong class="text-primary">${{ number_format($product->price, 2) }}</strong>
                    <a href="{{ route('product.show', $product->product_id) }}" class="btn btn-sm btn-outline-primary">
                        View
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
