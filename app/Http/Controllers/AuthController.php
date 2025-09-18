<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store user info in session
            Session::put('user_id', $user->user_id);
            Session::put('username', $user->username);
            Session::put('role', $user->role);

            return redirect('/product'); // sukses login
        } else {
            return back()->withErrors(['login' => 'Invalid user ID or password.']);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|unique:users,email',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'city' => 'required|string',
            'contact' => 'nullable|string',
            'paypal' => 'nullable|email',
        ]);

        $city = City::firstOrCreate(['name' => $request->city]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'date_of_birth' => $request->dob,
            'gender' => $request->gender,
            'contact_no' => $request->contact,
            'city_id' => $city->id,
            'paypal_id' => $request->paypal,
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Auto login after register
        Session::put('user_id', $user->user_id);
        Session::put('username', $user->username);
        Session::put('role', $user->role);

        return redirect('/product'); // langsung masuk product
    }

    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}
