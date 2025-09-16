<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('user_id', $request->user_id)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store user info in session
            Session::put('user_id', $user->user_id);
            Session::put('username', $user->username);
            Session::put('role', $user->role);

            return redirect('/dashboard'); // Redirect to dashboard or home
        } else {
            return back()->withErrors(['login' => 'Invalid user ID or password.']);
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
