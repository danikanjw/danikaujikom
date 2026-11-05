@extends('layouts.app')

@section('title', 'Manage Customers')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="admin-list-toolbar mb-0">
                        <div>
                            <h1>Manage Customers</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-list-table">
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
                                            <td>
                                                <span class="badge rounded-pill {{ $customer->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="{{ route('admin.edit_customer', $customer->user_id) }}" class="btn btn-outline-success btn-sm admin-action-outline">Edit</a>
                                                    <button type="button" class="btn btn-danger btn-sm admin-action-danger" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal{{ $customer->user_id }}">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No customers found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($customers as $customer)
    <div class="modal fade" id="deleteCustomerModal{{ $customer->user_id }}" tabindex="-1" aria-labelledby="deleteCustomerModalLabel{{ $customer->user_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.delete_customer', $customer->user_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCustomerModalLabel{{ $customer->user_id }}">Delete Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete customer <strong>{{ $customer->username }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm admin-action-danger">Delete Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
