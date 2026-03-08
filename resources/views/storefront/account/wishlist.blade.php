@extends('layouts.storefront')

@section('title', 'Wishlist')

@section('content')

<h1 class="h3 mb-4">My Wishlist</h1>

@if($wishlist->isEmpty())
    <div class="alert alert-info">Your wishlist is empty. <a href="{{ route('home') }}">Continue shopping</a>.</div>
@else
<div class="row row-cols-1 row-cols-md-4 g-4">
    @foreach($wishlist as $item)
    <div class="col">
        <div class="card h-100 shadow-sm">
            <a href="{{ route('product.show', $item->product_id) }}">
                <img src="{{ $item->product?->image ? asset('image/' . $item->product->image) : asset('image/no-image.png') }}"
                     class="card-img-top" style="height:200px;object-fit:cover">
            </a>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">
                    <a href="{{ route('product.show', $item->product_id) }}" class="text-dark text-decoration-none">
                        {{ $item->product?->description?->name }}
                    </a>
                </h6>
                <div class="mt-auto">
                    <strong class="text-primary d-block mb-2">${{ number_format($item->product?->price ?? 0, 2) }}</strong>
                    <div class="d-flex gap-2">
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-sm btn-primary">Add to Cart</button>
                        </form>
                        <form action="{{ route('account.wishlist.remove', $item->product_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
