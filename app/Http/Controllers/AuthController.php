<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $validated['username'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['login' => 'Invalid username or password.']);
        }

        if (!$user->is_active) {
            return back()->withErrors(['login' => 'Account is not active. Please wait for admin approval.']);
        }

        return $this->startUserSession($user);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'name' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|max:255|unique:users,email',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'city' => 'required|string|exists:cities,name',
            'contact' => 'nullable|string|max:30',
        ]);

        $city = City::where('name', $validated['city'])->firstOrFail();

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'password' => Hash::make($validated['password']),
            'email' => $validated['email'],
            'date_of_birth' => $validated['dob'],
            'gender' => $validated['gender'],
            'address' => $validated['address'] ?? null,
            'contact_no' => $validated['contact'] ?? null,
            'city_id' => $city->id,
            'role' => 'customer',
            'is_active' => true,
        ]);

        return $this->startUserSession($user);
    }

    public function registerVendor(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'name' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|max:255|unique:users,email',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'city' => 'required|string|exists:cities,name',
            'contact' => 'nullable|string|max:30',
        ]);

        $city = City::where('name', $validated['city'])->firstOrFail();

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'password' => Hash::make($validated['password']),
            'email' => $validated['email'],
            'date_of_birth' => $validated['dob'],
            'gender' => $validated['gender'],
            'address' => $validated['address'] ?? null,
            'contact_no' => $validated['contact'] ?? null,
            'city_id' => $city->id,
            'role' => 'vendor',
            'vendor_status' => 'pending',
            'is_active' => false,
        ]);

        return redirect('/login')->with('success', 'Pendaftaran vendor berhasil diajukan. Tunggu persetujuan admin.');
    }

    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect('/');
    }

    private function startUserSession(User $user)
    {
        Auth::login($user);
        Session::put([
            'user_id' => $user->user_id,
            'username' => $user->username,
            'role' => $user->role,
        ]);

        if ($user->role === 'vendor') {
            return redirect('/vendor/dashboard');
        }

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/product');
    }
}
