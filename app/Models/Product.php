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

    protected $fillable = ['name', 'code', 'description', 'price', 'quantity', 'image_url', 'category_id', 'vendor_id'];

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

    public static function generateProductCode($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return null;
        }

        // Generate abbreviation: first letter of each word, uppercase
        $words = explode(' ', $category->name);
        $abbr = '';
        foreach ($words as $word) {
            $abbr .= strtoupper(substr($word, 0, 1));
        }

        // Count existing products in category +1
        $count = self::where('category_id', $categoryId)->count() + 1;

        // Pad to 3 digits
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);

        return $abbr . '-' . $number;
    }
}
