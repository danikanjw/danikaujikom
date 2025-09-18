<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = true;

    protected $fillable = ['name', 'description', 'price', 'quantity', 'image_url', 'category_id', 'vendor_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
}
