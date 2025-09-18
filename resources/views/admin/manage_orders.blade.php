@extends('layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Manage Orders</h1>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Shipping Service</th>
                                        <th>Order Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->order_id }}</td>
                                            <td>{{ $order->user->username ?? 'N/A' }}</td>
                                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($order->status) }}</td>
                                            <td>{{ $order->shippingService->name ?? 'N/A' }}</td>
                                            <td>{{ $order->order_date->format('d M Y') }}</td>
                                            <td>
                                                @if($order->status === 'paid')
                                                    <form action="{{ route('admin.ship_order', $order->order_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">Mark as Shipped</button>
                                                    </form>
                                                @else
                                                    <span class="text-success">Shipped</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">No orders to manage.</p>
                        </div>
                    @endif
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
