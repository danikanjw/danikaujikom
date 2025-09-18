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
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <a href="{{ route('vendor.product.edit', $product->product_id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" action="{{ route('vendor.product.destroy', $product->product_id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
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

    <div class="mt-4">
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
@endsection
