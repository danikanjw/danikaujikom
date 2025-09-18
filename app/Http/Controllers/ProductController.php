<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $categoryId = $request->get('category_id');

        if ($categoryId && $categoryId !== 'all') {
            $products = Product::with('category')->where('category_id', $categoryId)->paginate(6);
        } else {
            $products = Product::with('category')->paginate(6);
        }

        return view('product', compact('categories', 'products', 'categoryId'));
    }
}
