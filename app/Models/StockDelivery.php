<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDelivery extends Model
{
    protected $table = 'stock_deliveries';
    protected $primaryKey = 'stock_delivery_id';

    protected $fillable = [
        'vendor_id',
        'received_by',
        'status',
        'notes',
        'submitted_at',
        'received_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'user_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(StockDeliveryItem::class, 'stock_delivery_id');
    }
}