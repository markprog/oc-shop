@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ number_format($stats['total_orders']) }}</div>
                    <div class="small">Total Orders</div>
                </div>
                <i class="bi bi-bag fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ number_format($stats['total_customers']) }}</div>
                    <div class="small">Customers</div>
                </div>
                <i class="bi bi-people fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-info">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ number_format($stats['total_products']) }}</div>
                    <div class="small">Products</div>
                </div>
                <i class="bi bi-box-seam fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ number_format($stats['pending_reviews']) }}</div>
                    <div class="small">Pending Reviews</div>
                </div>
                <i class="bi bi-star fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Orders</h5>
        <a href="{{ route('admin.sale.order.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>#{{ $order->order_id }}</td>
                        <td>{{ $order->firstname }} {{ $order->lastname }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->status?->name ?? 'N/A' }}</span>
                        </td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->date_added?->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.sale.order.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
