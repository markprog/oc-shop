@extends('layouts.storefront')

@section('title', 'My Orders')

@section('content')

<h1 class="h3 mb-4">Order History</h1>

@if($orders->isEmpty())
    <div class="alert alert-info">You have not placed any orders yet.</div>
@else
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Status</th>
                <th class="text-end">Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->order_id }}</td>
                <td>{{ $order->date_added?->format('M d, Y') }}</td>
                <td><span class="badge bg-secondary">{{ $order->status?->name ?? 'N/A' }}</span></td>
                <td class="text-end">${{ number_format($order->total, 2) }}</td>
                <td>
                    <a href="{{ route('account.order.show', $order->order_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endif

@endsection
