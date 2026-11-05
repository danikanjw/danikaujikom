@extends('layouts.app')

@section('title', 'Manage Feedback')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="admin-list-toolbar mb-0">
                        <div>
                            <h1>Manage Customer Feedback</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($feedbacks->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-list-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Message</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacks as $feedback)
                                        <tr>
                                            <td>{{ $feedback->user->name ?? 'N/A' }}</td>
                                            <td>{{ $feedback->message }}</td>
                                            <td>{{ $feedback->created_at ? $feedback->created_at->format('d M Y H:i') : 'N/A' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm admin-action-danger" data-bs-toggle="modal" data-bs-target="#deleteFeedbackModal{{ $feedback->feedback_id }}">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $feedbacks->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No feedback available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($feedbacks as $feedback)
    <div class="modal fade" id="deleteFeedbackModal{{ $feedback->feedback_id }}" tabindex="-1" aria-labelledby="deleteFeedbackModalLabel{{ $feedback->feedback_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.delete_feedback', $feedback->feedback_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteFeedbackModalLabel{{ $feedback->feedback_id }}">Delete Feedback</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this feedback?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm admin-action-danger">Delete Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
