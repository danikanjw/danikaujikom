<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Session;

class FeedbackController extends Controller
{
    public function showForm()
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        return view('feedback_form');
    }

    public function store(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => Session::get('user_id'),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Feedback submitted successfully.');
    }
}
