<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        $user = User::with('city')->find(Session::get('user_id'));
        $cities = City::all();

        return view('profile', compact('user', 'cities'));
    }

    public function update(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Session::get('user_id') . ',user_id',
            'email' => 'required|email|max:255|unique:users,email,' . Session::get('user_id') . ',user_id',
            'address' => 'nullable|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'contact_no' => 'required|string|max:20',
        ]);

        $user = User::find(Session::get('user_id'));
        $user->update($request->only(['username', 'email', 'address', 'city_id', 'contact_no']));

        return back()->with('success', 'Profile updated successfully.');
    }
}
