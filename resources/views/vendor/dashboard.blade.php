@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Welcome to Your Vendor Dashboard</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Manage your products and grow your business.</p>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Products</h5>
                                    <p class="card-text display-4">{{ $products->total() }}</p>
                                    <!-- <a href="{{ route('vendor.product.index') }}" class="btn btn-primary">Manage Products</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Stock</h5>
                                    <p class="card-text display-4">{{ $products->sum('quantity') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Add New Product</h5>
                                    <a href="{{ route('vendor.product.create') }}" class="btn btn-success btn-lg">Create Product</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h3>Recent Products</h3>
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
                                        @foreach($products->take(5) as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name }}</td>
                                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>
<a href="{{ route('vendor.product.edit', $product->product_id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('vendor.product.index') }}" class="btn btn-secondary">View All Products</a>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted">You haven't added any products yet.</p>
                                <a href="{{ route('vendor.product.create') }}" class="btn btn-primary">Add Your First Product</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
