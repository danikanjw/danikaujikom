@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<style>
    .admin-category-page {
        color: #172b4d;
    }

    .admin-category-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .admin-category-toolbar p {
        margin: 0;
        color: #75839a;
        font-size: 14px;
    }

    .admin-category-add {
        border: 0;
        border-radius: 8px;
        background: #1bb89a;
        color: #fff;
        padding: 5px 10px;
        font-size: 14px;
        /* font-weight: 650; */
    }

    .admin-category-add:hover,
    .admin-category-submit:hover {
        background: #159d83;
        color: #fff;
    }

    .admin-category-edit,
    .admin-category-delete {
        padding: 3px 8px;
        font-size: 14px;
    }

    .admin-category-table {
        margin-bottom: 0;
        color: #35445c;
        font-size: 14px;
    }

    .admin-category-table thead th {
        padding: 12px 14px;
        border-bottom: 1px solid #e6edf0;
        background: #f5f8f7;
        color: #75839a;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .admin-category-table tbody td {
        padding: 14px;
        border-color: #edf1f2;
        background: #fff;
        vertical-align: middle;
    }

    .admin-category-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .admin-category-edit {
        border-color: #b7e9dc;
        color: #138c76;
    }

    .admin-category-edit:hover {
        border-color: #1bb89a;
        background: #e8f8f4;
        color: #107762;
    }

    .admin-category-submit {
        border: 0;
        background: #1bb89a;
        color: #fff;
        padding: 5px 10px;
        font-size: 14px;
        /* font-weight: 650; */
    }

    .admin-category-modal .btn {
        /* padding: 5px 10px; */
        font-size: 14px;
    }

    @media (max-width: 575.98px) {
        .admin-category-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="container admin-category-page">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="admin-category-toolbar">
                        <h1>Manage Categories</h1>
                        <button class="btn btn-sm admin-category-add" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add New Category</button>
                    </div>
                </div>
                <div class="card-body">

                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-category-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>{{ $category->category_id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <button class="btn btn-outline-success btn-sm admin-category-edit" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->category_id }}">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm admin-category-delete" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $category->category_id }}">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">No categories found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade admin-category-modal" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.create_category') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm admin-category-submit">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modals -->
@foreach($categories as $category)
<div class="modal fade admin-category-modal" id="editCategoryModal{{ $category->category_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.update_category', $category->category_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name{{ $category->category_id }}" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name{{ $category->category_id }}" name="name" value="{{ $category->name }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm admin-category-submit">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Delete Category Confirmation Modals -->
@foreach($categories as $category)
<div class="modal fade admin-category-modal" id="deleteCategoryModal{{ $category->category_id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel{{ $category->category_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.delete_category', $category->category_id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel{{ $category->category_id }}">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the category <strong>{{ $category->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
