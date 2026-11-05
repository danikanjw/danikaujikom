@extends('layouts.app')

@section('title', 'Pending Vendor Approvals')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="admin-list-toolbar mb-0">
                        <div>
                            <h1>Pending Vendor Approvals</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($pendingVendors->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-list-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>City</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingVendors as $vendor)
                                        <tr>
                                            <td>{{ $vendor->name }}</td>
                                            <td>{{ $vendor->username }}</td>
                                            <td>{{ $vendor->email }}</td>
                                            <td>{{ $vendor->city->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <form action="{{ route('admin.approve_vendor', $vendor->user_id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm admin-action-primary">Approve</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm admin-action-danger" data-bs-toggle="modal" data-bs-target="#rejectVendorModal{{ $vendor->user_id }}">Reject</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No pending vendor applications.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($pendingVendors as $vendor)
    <div class="modal fade" id="rejectVendorModal{{ $vendor->user_id }}" tabindex="-1" aria-labelledby="rejectVendorModalLabel{{ $vendor->user_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.reject_vendor', $vendor->user_id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectVendorModalLabel{{ $vendor->user_id }}">Reject Vendor Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to reject the application from <strong>{{ $vendor->name }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm admin-action-danger">Reject Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
