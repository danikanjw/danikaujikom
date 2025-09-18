@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Customer</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update_customer', $customer->user_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ $customer->username }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Active</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1" {{ $customer->is_active ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$customer->is_active ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Customer</button>
                        <a href="{{ route('admin.manage_customers') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
