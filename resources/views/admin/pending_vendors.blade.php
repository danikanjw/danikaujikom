@extends('layouts.app')

@section('title', 'Pending Vendor Approvals')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Pending Vendor Approvals</h1>
                </div>
                <div class="card-body">
                    @if($pendingVendors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                                <form action="{{ route('admin.approve_vendor', $vendor->user_id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.reject_vendor', $vendor->user_id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">No pending vendor applications.</p>
                        </div>
                    @endif
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
