<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = ['username', 'password', 'email', 'date_of_birth', 'gender', 'contact_no', 'city_id', 'paypal_id', 'role', 'created_at', 'is_active'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'user_id', 'user_id');
    }
}