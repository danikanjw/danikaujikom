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
            if (!$user->is_active) {
                return back()->withErrors(['login' => 'Account is not active. Please wait for admin approval.']);
            }
            // Store user info in session
            Session::put('user_id', $user->user_id);
            Session::put('username', $user->username);
            Session::put('role', $user->role);

            // Redirect based on role
            if ($user->role === 'vendor') {
                return redirect('/vendor/dashboard');
            } elseif ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/product');
            }
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

        // Redirect based on role
        if ($user->role === 'vendor') {
            return redirect('/vendor/dashboard');
        } elseif ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/product');
        }
    }

    public function registerVendor(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|unique:users,email',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'contact' => 'nullable|string',
            'paypal' => 'nullable|email',
        ]);

        $city = City::firstOrCreate(['name' => $request->city]);

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'date_of_birth' => $request->dob,
            'gender' => $request->gender,
            'address' => $request->address,
            'contact_no' => $request->contact,
            'city_id' => $city->id,
            'paypal_id' => $request->paypal,
            'role' => null, // Pending approval
            'is_active' => false, // Inactive until approved
        ]);

        return redirect('/login')->with('success', 'Pendaftaran vendor berhasil diajukan. Tunggu persetujuan admin.');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}
