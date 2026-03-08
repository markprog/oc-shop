@extends('layouts.storefront')

@section('title', ($category->description?->name ?? 'Category') . ' — ' . config('shop.name'))

@section('content')

<h1 class="h3 mb-4">{{ $category->description?->name }}</h1>

@if($category->description?->description)
    <div class="mb-4">{!! $category->description->description !!}</div>
@endif

{{-- Subcategories --}}
@if($subcategories->isNotEmpty())
<div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
    @foreach($subcategories as $sub)
    <div class="col">
        <a href="{{ route('category.show', $sub->category_id) }}" class="text-decoration-none">
            <div class="card text-center h-100">
                @if($sub->image)
                <img src="{{ asset('image/' . $sub->image) }}" class="card-img-top" style="height:100px;object-fit:cover">
                @endif
                <div class="card-body py-2">
                    <p class="card-text small">{{ $sub->description?->name }}</p>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endif

{{-- Products --}}
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
    @forelse($products as $product)
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
                    <a href="{{ route('product.show', $product->product_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">No products found in this category.</div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
</div>

@endsection
