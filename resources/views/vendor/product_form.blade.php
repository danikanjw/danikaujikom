@extends('layouts.app')

@section('title')
@if(isset($product))
Edit Product
@else
Add New Product
@endif
@endsection

@section('content')
<div class="container mt-5">
    <h1>
        @if(isset($product))
        Edit Product
        @else
        Add New Product
        @endif
    </h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($product) ? route('vendor.product.update', $product->product_id) : route('vendor.product.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
        @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (Rp)</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->category_id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->category_id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>

                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Stock</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity ?? '') }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" @if(!isset($product)) required @endif>
            @if(isset($product) && $product->image_url)
            <img src="{{ asset('storage/' . $product->image_url) }}" alt="Current Photo" style="max-width: 200px; margin-top: 10px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">
            @if(isset($product))
            Update Product
            @else
            Add Product
            @endif
        </button>
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection