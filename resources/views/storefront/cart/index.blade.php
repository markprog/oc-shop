@extends('layouts.storefront')

@section('title', 'Shopping Cart')

@section('content')

<h1 class="h3 mb-4">Shopping Cart</h1>

@if($items->isEmpty())
    <div class="alert alert-info">
        Your cart is empty. <a href="{{ route('home') }}">Continue shopping</a>.
    </div>
@else

<div class="row">
    <div class="col-md-8">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $item->product->image ? asset('image/' . $item->product->image) : asset('image/no-image.png') }}"
                                 style="width:60px;height:60px;object-fit:cover" class="rounded" alt="">
                            <div>
                                <a href="{{ route('product.show', $item->product_id) }}" class="text-dark">
                                    {{ $item->product->description?->name }}
                                </a>
                                @if($item->option)
                                <div class="small text-muted">
                                    @foreach($item->option as $key => $val)
                                        <span>{{ $key }}: {{ $val }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-center" style="width:130px">
                        <form action="{{ route('cart.update', $item->cart_id) }}" method="POST" class="d-flex justify-content-center">
                            @csrf @method('PATCH')
                            <div class="input-group input-group-sm" style="width:100px">
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control text-center">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>
                        </form>
                    </td>
                    <td class="text-end">${{ number_format($item->product->price, 2) }}</td>
                    <td class="text-end">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                    <td class="text-end">
                        <form action="{{ route('cart.remove', $item->cart_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Order Summary</h5>
                <table class="table table-sm">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-end">${{ number_format($total, 2) }}</td>
                    </tr>
                    <tr class="fw-bold border-top">
                        <td>Total</td>
                        <td class="text-end">${{ number_format($total, 2) }}</td>
                    </tr>
                </table>
                <a href="{{ route('checkout.shipping-address') }}" class="btn btn-primary w-100">
                    Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mt-2">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

@endif

@endsection
