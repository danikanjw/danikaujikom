<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockDelivery;
use App\Models\StockDeliveryItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $categoryId = $request->get('category_id');

        if ($categoryId && $categoryId !== 'all') {
            $products = Product::with(['category', 'vendor'])
                ->where('category_id', $categoryId)
                ->where('warehouse_quantity', '>', 0)
                ->paginate(6);
        } else {
            $products = Product::with(['category', 'vendor'])
                ->where('warehouse_quantity', '>', 0)
                ->paginate(6);
        }

        return view('product', compact('categories', 'products', 'categoryId'));
    }

    // Vendor CRUD methods
    public function vendorIndex()
    {
        $vendorId = Session::get('user_id');
        $products = Product::where('vendor_id', $vendorId)->with('category')->paginate(10);

        return view('vendor.product_crud', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('vendor.product_form', compact('categories'));
    }

    public function store(Request $request)
    {
        \Log::info('ProductController@store called', [
            'user_id' => Session::get('user_id'),
            'role' => Session::get('role'),
            'request_data' => $request->all()
        ]);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vendorId = Session::get('user_id');

        if (!$vendorId) {
            \Log::error('No vendor ID in session');
            return redirect()->back()->withErrors(['error' => 'Session expired. Please login again.']);
        }

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('products', 'public');
            \Log::info('Image uploaded', ['path' => $imagePath]);
        }

        $code = Product::generateProductCode($request->category_id);

        $product = Product::create([
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'warehouse_quantity' => 0,
            'vendor_id' => $vendorId,
            'image_url' => $imagePath,
        ]);

        \Log::info('Product created', ['product_id' => $product->product_id]);

        return redirect()->route('vendor.product.index')->with('success', 'Product created successfully!');
    }

    public function edit($id)
    {
        $vendorId = Session::get('user_id');
        $product = Product::where('product_id', $id)->where('vendor_id', $vendorId)->firstOrFail();
        $categories = Category::all();

        return view('vendor.product_form', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vendorId = Session::get('user_id');
        $product = Product::where('product_id', $id)->where('vendor_id', $vendorId)->firstOrFail();

        $imagePath = $product->image_url;
        if ($request->hasFile('photo')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('photo')->store('products', 'public');
        }

        $code = $product->code;
        if ($request->category_id != $product->category_id) {
            $code = Product::generateProductCode($request->category_id);
        }

        $product->update([
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image_url' => $imagePath,
        ]);

        return redirect()->route('vendor.product.index')->with('success', 'Product updated successfully!');
    }

    public function submitStock(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:4294967295',
            'notes' => 'nullable|string|max:1000',
        ]);

        $product = Product::where('product_id', $productId)
            ->where('vendor_id', Session::get('user_id'))
            ->firstOrFail();

        DB::transaction(function () use ($validated, $product) {
            $delivery = StockDelivery::create([
                'vendor_id' => Session::get('user_id'),
                'status' => 'submitted',
                'notes' => $validated['notes'] ?? null,
                'submitted_at' => now(),
            ]);

            StockDeliveryItem::create([
                'stock_delivery_id' => $delivery->stock_delivery_id,
                'product_id' => $product->product_id,
                'quantity_sent' => $validated['quantity'],
            ]);
        });

        return redirect()->route('vendor.product.index')
            ->with('success', 'Stock submission sent to admin for verification.');
    }

    public function destroy($id)
    {
        $vendorId = Session::get('user_id');
        $product = Product::where('product_id', $id)->where('vendor_id', $vendorId)->firstOrFail();

        if ($product->stockDeliveryItems()->exists()) {
            return redirect()->route('vendor.product.index')
                ->withErrors(['product' => 'Products with stock submission history cannot be deleted.']);
        }

        // Delete image if exists
        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return redirect()->route('vendor.product.index')->with('success', 'Product deleted successfully!');
    }
}
