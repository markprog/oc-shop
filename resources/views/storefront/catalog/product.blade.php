@extends('layouts.storefront')

@section('title', $product->description?->name . ' — ' . config('shop.name'))

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if($product->categories->first())
        <li class="breadcrumb-item">
            <a href="{{ route('category.show', $product->categories->first()->category_id) }}">
                {{ $product->categories->first()->description?->name }}
            </a>
        </li>
        @endif
        <li class="breadcrumb-item active">{{ $product->description?->name }}</li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    {{-- Images --}}
    <div class="col-md-5">
        <div class="text-center mb-3">
            <img src="{{ $product->image ? asset('image/' . $product->image) : asset('image/no-image.png') }}"
                 class="img-fluid rounded shadow" style="max-height:400px;object-fit:contain"
                 id="mainImage" alt="{{ $product->description?->name }}">
        </div>
        @if($product->images->isNotEmpty())
        <div class="d-flex gap-2 flex-wrap justify-content-center">
            <img src="{{ asset('image/' . $product->image) }}"
                 class="img-thumbnail" style="width:80px;height:80px;object-fit:cover;cursor:pointer"
                 onclick="document.getElementById('mainImage').src=this.src">
            @foreach($product->images as $img)
            <img src="{{ asset('image/' . $img->image) }}"
                 class="img-thumbnail" style="width:80px;height:80px;object-fit:cover;cursor:pointer"
                 onclick="document.getElementById('mainImage').src=this.src">
            @endforeach
        </div>
        @endif
    </div>

    {{-- Details --}}
    <div class="col-md-7">
        <h1 class="h3">{{ $product->description?->name }}</h1>

        <div class="mb-2">
            @if($product->manufacturer)
                <span class="text-muted">Brand:</span>
                <a href="{{ route('manufacturer.show', $product->manufacturer_id) }}" class="ms-1">{{ $product->manufacturer->name }}</a>
            @endif
            <span class="ms-3 text-muted small">Model: {{ $product->model }}</span>
        </div>

        {{-- Rating stars --}}
        <div class="mb-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $product->rating >= $i ? '-fill text-warning' : ' text-muted' }}"></i>
            @endfor
            <span class="small text-muted ms-1">({{ $product->reviews_count ?? 0 }} reviews)</span>
        </div>

        <hr>

        <div class="h4 text-primary mb-3">${{ number_format($product->price, 2) }}</div>

        {{-- Stock --}}
        <p class="mb-2">
            <strong>Availability:</strong>
            @if($product->quantity > 0)
                <span class="text-success">In Stock</span>
            @else
                <span class="text-danger">{{ $product->stockStatus?->name ?? 'Out of Stock' }}</span>
            @endif
        </p>

        {{-- Add to cart form --}}
        <form action="{{ route('cart.add') }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">

            {{-- Options --}}
            @foreach($product->options as $option)
            <div class="mb-3">
                <label class="form-label fw-bold">
                    {{ $option->description?->name }}
                    @if($option->required) <span class="text-danger">*</span> @endif
                </label>
                @if(in_array($option->type ?? '', ['select', 'radio']))
                    <select name="options[{{ $option->product_option_id }}]" class="form-select"
                        {{ $option->required ? 'required' : '' }}>
                        <option value="">--- Please select ---</option>
                        @foreach($option->values as $value)
                        <option value="{{ $value->product_option_value_id }}">
                            {{ $value->optionValue?->description?->name }}
                            @if($value->price_prefix && $value->price)
                                ({{ $value->price_prefix }}${{ number_format($value->price, 2) }})
                            @endif
                        </option>
                        @endforeach
                    </select>
                @endif
            </div>
            @endforeach

            <div class="d-flex align-items-center gap-3">
                <div class="input-group" style="width:120px">
                    <button type="button" class="btn btn-outline-secondary" onclick="this.nextElementSibling.stepDown()">−</button>
                    <input type="number" name="quantity" value="{{ max(1, $product->minimum ?? 1) }}"
                           min="{{ $product->minimum ?? 1 }}" class="form-control text-center">
                    <button type="button" class="btn btn-outline-secondary" onclick="this.previousElementSibling.stepUp()">+</button>
                </div>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                </button>
                @auth('web')
                <form action="{{ route('account.wishlist.add', $product->product_id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-heart"></i>
                    </button>
                </form>
                @endauth
            </div>
        </form>

        {{-- Short description --}}
        @if($product->description?->description)
        <div class="border-top pt-3">
            {!! $product->description->description !!}
        </div>
        @endif
    </div>
</div>

{{-- Tabs: Description, Attributes, Reviews --}}
<ul class="nav nav-tabs" id="productTabs">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Description</button>
    </li>
    @if($product->attributes->isNotEmpty())
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-attrs">Specification</button>
    </li>
    @endif
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-reviews">
            Reviews ({{ $product->reviews->count() }})
        </button>
    </li>
</ul>

<div class="tab-content border border-top-0 p-3 mb-5">
    <div class="tab-pane fade show active" id="tab-desc">
        {!! $product->description?->description ?? '<p>No description available.</p>' !!}
    </div>

    @if($product->attributes->isNotEmpty())
    <div class="tab-pane fade" id="tab-attrs">
        <table class="table table-bordered table-sm">
            @foreach($product->attributes as $attr)
            <tr>
                <th style="width:30%">{{ $attr->attribute?->description?->name }}</th>
                <td>{{ $attr->text }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <div class="tab-pane fade" id="tab-reviews">
        {{-- Reviews list --}}
        @foreach($product->reviews->where('status', true) as $review)
        <div class="border-bottom pb-3 mb-3">
            <strong>{{ $review->author }}</strong>
            <div>
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $review->rating >= $i ? '-fill text-warning' : ' text-muted' }}"></i>
                @endfor
            </div>
            <p class="mb-0">{{ $review->text }}</p>
            <small class="text-muted">{{ $review->date_added?->format('M d, Y') }}</small>
        </div>
        @endforeach

        {{-- Write review form --}}
        <h5 class="mt-4">Write a Review</h5>
        <form action="{{ route('product.review.store', $product->product_id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select" required>
                    <option value="">Select rating</option>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Review (min 25 characters)</label>
                <textarea name="text" class="form-control" rows="4" required minlength="25">{{ old('text') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</div>

@endsection
