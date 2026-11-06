@extends('layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="admin-list-toolbar mb-0">
                        <div>
                            <h1>Manage Orders</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-list-table">
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
                                            <td>
                                                <span class="badge rounded-pill {{ $order->status === 'shipped' ? 'text-bg-success' : 'text-bg-warning' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->shippingService->name ?? 'N/A' }}</td>
                                            <td>{{ $order->order_date ? $order->order_date->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                @if($order->status === 'paid')
                                                    <form action="{{ route('admin.ship_order', $order->order_id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm admin-action-primary">Mark as Shipped</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">No action needed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No orders to manage.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
