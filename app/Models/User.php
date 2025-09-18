<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'username', 'name', 'password', 'email',
        'date_of_birth', 'gender', 'address', 'contact_no',
        'city_id', 'paypal_id', 'role', 'is_active'
    ];

    // Relasi
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'user_id');
    }

    public function accounts()
    {
        return $this->hasMany(UserAccount::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }
}
