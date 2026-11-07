@extends('layouts.app')

@section('title')
@if(isset($product))
Edit Product
@else
Add New Product
@endif
@endsection

@section('content')
<section class="vendor-product-form-page">
<style>
    .vendor-product-form-page .vendor-product-photo-card {
        display: grid;
        grid-template-columns: 120px minmax(0, 1fr);
        align-items: center;
        gap: 20px;
        padding: 18px;
        border: 1px solid #e3e9ee;
        border-radius: 12px;
        background: #f8fafb;
    }

    .vendor-product-form-page .vendor-product-photo-preview {
        display: grid;
        width: 120px;
        height: 120px;
        place-items: center;
        overflow: hidden;
        border: 1px dashed #cbd5df;
        border-radius: 10px;
        background: #fff;
        color: #75839a;
        text-align: center;
    }

    .vendor-product-form-page .vendor-product-photo-preview img {
        display: block;
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 100%;
        object-fit: contain;
    }

    .vendor-product-form-page .vendor-product-photo-upload .form-text {
        margin-top: 8px;
    }

    @media (max-width: 575.98px) {
        .vendor-product-form-page .vendor-product-photo-card {
            grid-template-columns: 120px minmax(0, 1fr);
            gap: 14px;
            padding: 14px;
        }
    }
</style>
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

    <div class="alert alert-danger d-none js-product-form-error" role="alert"></div>
    <form method="POST" action="{{ isset($product) ? route('vendor.product.update', $product->product_id) : route('vendor.product.store') }}" enctype="multipart/form-data" class="js-product-form">
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

        <div class="mb-4">
            <label for="photo" class="form-label">Product Photo</label>
            <div class="vendor-product-photo-card">
                <div class="vendor-product-photo-preview">
                    @if(isset($product) && $product->image_url)
                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="Current photo of {{ $product->name }}">
                    @else
                        <span class="px-3">Photo preview will appear here</span>
                    @endif
                </div>
                <div class="vendor-product-photo-upload">
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" @if(!isset($product)) required @endif>
                    <div class="form-text">Choose a clear product image (JPG, PNG, or GIF; max 2 MB).</div>
                    @if(isset($product) && $product->image_url)
                        <div class="form-text">Leave this empty to keep the current photo.</div>
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            @if(isset($product))
            Update Product
            @else
            Add Product
            @endif
        </button>
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary ms-2" data-ajax-page data-cancel-product-page>Cancel</a>
    </form>
</div>
</section>
@endsection