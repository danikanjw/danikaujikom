<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    use HasFactory;

    protected $table = 'shipping_services';
    protected $primaryKey = 'shipping_service_id';
    public $timestamps = true;

    protected $fillable = ['name', 'estimated_days'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_service_id');
    }
}
