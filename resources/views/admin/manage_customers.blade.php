@extends('layouts.app')

@section('title', 'Manage Customers')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Manage Customers</h1>
                </div>
                <div class="card-body">
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->username }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->is_active ? 'Yes' : 'No' }}</td>
                                            <td>
                                                <a href="{{ route('admin.edit_customer', $customer->user_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('admin.delete_customer', $customer->user_id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">No customers found.</p>
                        </div>
                    @endif
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
