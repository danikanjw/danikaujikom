@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Welcome to Admin Dashboard</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Manage users, products, and orders across the platform.</p>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <p class="card-text display-4">{{ $totalUsers }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Products</h5>
                                    <p class="card-text display-4">{{ $totalProducts }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text display-4">{{ $totalOrders }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Feedback</h5>
                                    <p class="card-text display-4">{{ $recentFeedback->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 d-flex justify-content-center">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="text-center">Admin Actions</h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('admin.manage_customers') }}" class="btn btn-primary">Manage Customers</a>
                                    <a href="{{ route('admin.pending_vendors') }}" class="btn btn-warning me-2">Pending Vendor Approvals</a>
                                    <a href="{{ route('admin.manage_categories') }}" class="btn btn-info me-2">Manage Categories</a>
                                    <a href="{{ route('admin.manage_orders') }}" class="btn btn-success">Manage Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h3>Recent Feedback</h3>
                        @if($recentFeedback->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentFeedback as $feedback)
                                    <tr>
                                        <td>{{ $feedback->user->username ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($feedback->message, 50) }}</td>
                                        <td>{{ $feedback->created_at->format('d M Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.delete_feedback', $feedback->feedback_id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <p class="text-muted">No feedback yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection