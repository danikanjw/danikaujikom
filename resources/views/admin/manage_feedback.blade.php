@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Customer Feedback</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($feedbacks->count() > 0)
        <table class="table table-bordered">
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
                    <td>{{ $feedback->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.delete_feedback', $feedback->feedback_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $feedbacks->links() }}
    @else
        <p>No feedback available.</p>
    @endif
</div>
@endsection
