@extends('layouts.app')

@section('title', 'Manage Stocks')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0">Manage Stocks</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($stockDeliveries->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-list-table align-middle">
                                <thead>
                                    <tr>
                                        <th>Submitted</th>
                                        <th>Vendor</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockDeliveries as $delivery)
                                        <tr>
                                            <td>{{ optional($delivery->submitted_at)->format('d M Y H:i') ?? $delivery->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $delivery->vendor->name }}<br><small class="text-muted">{{ $delivery->vendor->username }}</small></td>
                                            <td>
                                                @foreach($delivery->items as $item)
                                                    <div>{{ $item->product->name }}: {{ number_format($item->quantity_sent) }} submitted
                                                        @if($item->quantity_received !== null)
                                                            / {{ number_format($item->quantity_received) }} received
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'submitted' => 'text-bg-warning',
                                                        'received' => 'text-bg-success',
                                                        'rejected' => 'text-bg-secondary',
                                                    ][$delivery->status] ?? 'text-bg-secondary';
                                                @endphp
                                                <span class="badge rounded-pill {{ $statusClass }}">{{ ucfirst($delivery->status) }}</span>
                                            </td>
                                            <td>
                                                @if($delivery->status === 'submitted')
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#receiveStockModal{{ $delivery->stock_delivery_id }}">Verify Stock</button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectStockModal{{ $delivery->stock_delivery_id }}">Reject</button>
                                                    </div>
                                                @else
                                                    <span class="text-muted">
                                                        @if($delivery->status === 'received')
                                                            Received {{ optional($delivery->received_at)->format('d M Y H:i') }}
                                                        @else
                                                            Rejected
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $stockDeliveries->links() }}
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No stock submissions found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($stockDeliveries as $delivery)
    @if($delivery->status === 'submitted')
        <div class="modal fade" id="receiveStockModal{{ $delivery->stock_delivery_id }}" tabindex="-1" aria-labelledby="receiveStockModalLabel{{ $delivery->stock_delivery_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.receive_stock', $delivery->stock_delivery_id) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="receiveStockModalLabel{{ $delivery->stock_delivery_id }}">Verify Stock Submission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Vendor: <strong>{{ $delivery->vendor->name }}</strong> ({{ $delivery->vendor->username }})</p>
                            @if($delivery->notes)
                                <p>Vendor notes: {{ $delivery->notes }}</p>
                            @endif
                            <p class="text-muted">Enter the actual quantity received for each product. Verified quantities are added to the current available stock.</p>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Submitted</th>
                                            <th>Current Stock</th>
                                            <th>Final Received</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($delivery->items as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ number_format($item->quantity_sent) }}</td>
                                                <td>{{ number_format($item->product->warehouse_quantity) }}</td>
                                                <td>
                                                    <input type="number" class="form-control" name="quantities[{{ $item->stock_delivery_item_id }}]" value="{{ $item->quantity_sent }}" min="0" required>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success btn-sm">Confirm Received Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectStockModal{{ $delivery->stock_delivery_id }}" tabindex="-1" aria-labelledby="rejectStockModalLabel{{ $delivery->stock_delivery_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.reject_stock', $delivery->stock_delivery_id) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectStockModalLabel{{ $delivery->stock_delivery_id }}">Reject Stock Submission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Reject stock submitted by <strong>{{ $delivery->vendor->username }}</strong>?
                            Rejected quantities will not be added to product inventory.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm">Reject Submission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
