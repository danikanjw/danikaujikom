<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'total_amount', 'order_date',
        'status', 'shipping_address', 'payment_method',
        'payment_prepaid', 'shipping_service_id', 'payment_account_id'
    ];

    protected $casts = [
    'order_date' => 'datetime',
];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function shippingService()
    {
        return $this->belongsTo(ShippingService::class, 'shipping_service_id');
    }

    public function userAccount()
{
    return $this->belongsTo(UserAccount::class, 'payment_account_id', 'payment_account_id');
}

}
