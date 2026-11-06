<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDeliveryItem extends Model
{
    protected $table = 'stock_delivery_items';
    protected $primaryKey = 'stock_delivery_item_id';

    protected $fillable = [
        'stock_delivery_id',
        'product_id',
        'quantity_sent',
        'quantity_received',
    ];

    public function delivery()
    {
        return $this->belongsTo(StockDelivery::class, 'stock_delivery_id', 'stock_delivery_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
