@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="admin-list-toolbar mb-0">
                        <div>
                            <h1>Manage Users</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="GET" action="{{ route('admin.manage_users') }}" class="row g-2 align-items-end mb-4">
                        <div class="col-sm-4 col-md-3">
                            <label for="role" class="form-label">Filter by role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="" {{ $roleFilter === '' ? 'selected' : '' }}>All users</option>
                                <option value="customer" {{ $roleFilter === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="vendor" {{ $roleFilter === 'vendor' ? 'selected' : '' }}>Vendor</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-list-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ ucfirst($user->role) }}</td>
                                            <td>
                                                <span class="badge rounded-pill {{ $user->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="button" class="btn btn-outline-success btn-sm admin-action-outline" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->user_id }}">Edit Status</button>
                                                    <button type="button" class="btn btn-danger btn-sm admin-action-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->user_id }}">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No users found for this role.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- edit status user --}}
@foreach($users as $user)
    <div class="modal fade" id="editUserModal{{ $user->user_id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->user_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.update_user', $user->user_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role_filter" value="{{ $roleFilter }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel{{ $user->user_id }}">Update User Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="is_active{{ $user->user_id }}" class="form-label">Status</label>
                        <select class="form-select" id="is_active{{ $user->user_id }}" name="is_active" required>
                            <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteUserModal{{ $user->user_id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->user_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.delete_user', $user->user_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="role_filter" value="{{ $roleFilter }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel{{ $user->user_id }}">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete user <strong>{{ $user->username }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm admin-action-danger">Delete User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
