@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Your Products</h1>
        <a href="{{ route('vendor.product.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

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

    <div class="card">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Available Stock</th>
                                <th>Stock Submission Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>{{ number_format($product->warehouse_quantity) }}</td>
                                    <td>
                                        @if($product->pending_stock_submissions_count)
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#submitStockModal{{ $product->product_id }}" @if($product->pending_stock_submissions_count) disabled title="Menunggu persetujuan admin" @endif>
                                            Submit Stock
                                        </button>
                                        <a href="{{ route('vendor.product.edit', $product->product_id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" action="{{ route('vendor.product.destroy', $product->product_id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                @if($product->stock_delivery_items_count || $product->order_items_count)
                                                    disabled title="Products with stock or order history cannot be deleted"
                                                @else
                                                    onclick="return confirm('Are you sure you want to delete this product?')"
                                                @endif
                                            >Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
            @else
                <div class="text-center py-5">
                    <p class="text-muted">You haven't added any products yet.</p>
                    <a href="{{ route('vendor.product.create') }}" class="btn btn-primary">Add Your First Product</a>
                </div>
            @endif
        </div>
    </div>

    @foreach($products as $product)
        <div class="modal fade" id="submitStockModal{{ $product->product_id }}" tabindex="-1" aria-labelledby="submitStockModalLabel{{ $product->product_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('vendor.stock.submit', $product->product_id) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="submitStockModalLabel{{ $product->product_id }}">Submit Stock for Verification</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Product: <strong>{{ $product->name }}</strong></p>
                            <p>Currently available: {{ number_format($product->warehouse_quantity) }}</p>
                            @if($product->stock_delivery_items_count)
                                <p class="text-muted">You can submit additional stock after admin has reviewed the previous submission.</p>
                            @endif
                            <div class="mb-3">
                                <label for="quantity{{ $product->product_id }}" class="form-label">Stock submitted</label>
                                <input type="number" class="form-control" id="quantity{{ $product->product_id }}" name="quantity" min="1" max="4294967295" required>
                            </div>
                            <div class="mb-3">
                                <label for="notes{{ $product->product_id }}" class="form-label">Notes (optional)</label>
                                <textarea class="form-control" id="notes{{ $product->product_id }}" name="notes" rows="2" maxlength="1000"></textarea>
                            </div>
                            <small class="text-muted">Submitted stock will not be available to customers until verified by an admin.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success btn-sm">Submit Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="mt-4">
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
@endsection
