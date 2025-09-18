<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // Pending Vendors
    public function pendingVendors()
    {
        $pendingVendors = User::where('role', null)->where('is_active', false)->get();
        return view('admin.pending_vendors', compact('pendingVendors'));
    }

    public function approveVendor($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => 'vendor', 'is_active' => true]);
        return redirect()->route('admin.pending_vendors')->with('success', 'Vendor approved successfully.');
    }

    public function rejectVendor($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete(); // Or set to rejected status
        return redirect()->route('admin.pending_vendors')->with('success', 'Vendor application rejected.');
    }

    // Categories
    public function manageCategories()
    {
        $categories = Category::all();
        return view('admin.manage_categories', compact('categories'));
    }

    public function createCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:categories,name']);
        Category::create(['name' => $request->name]);
        return redirect()->route('admin.manage_categories')->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, $categoryId)
    {
        $request->validate(['name' => 'required|string|unique:categories,name,' . $categoryId . ',category_id']);
        $category = Category::findOrFail($categoryId);
        $category->update(['name' => $request->name]);
        return redirect()->route('admin.manage_categories')->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();
        return redirect()->route('admin.manage_categories')->with('success', 'Category deleted successfully.');
    }

    // Customers
    public function manageCustomers()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.manage_customers', compact('customers'));
    }

    public function editCustomer($userId)
    {
        $customer = User::findOrFail($userId);
        return view('admin.edit_customer', compact('customer'));
    }

    public function updateCustomer(Request $request, $userId)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username,' . $userId . ',user_id',
            'email' => 'required|email|unique:users,email,' . $userId . ',user_id',
            'is_active' => 'required|boolean',
        ]);
        $customer = User::findOrFail($userId);
        $customer->update($request->only(['username', 'email', 'is_active']));
        return redirect()->route('admin.manage_customers')->with('success', 'Customer updated successfully.');
    }

    public function deleteCustomer($userId)
    {
        $customer = User::findOrFail($userId);
        $customer->delete();
        return redirect()->route('admin.manage_customers')->with('success', 'Customer deleted successfully.');
    }

    // Orders
    public function manageOrders()
    {
        $orders = Order::with('user', 'shippingService')->where('status', 'paid')->get();
        return view('admin.manage_orders', compact('orders'));
    }

    public function shipOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'shipped']);
        return redirect()->route('admin.manage_orders')->with('success', 'Order status updated to shipped.');
    }
}
