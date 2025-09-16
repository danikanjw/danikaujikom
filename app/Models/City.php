<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class, 'city_id', 'id');
    }
}