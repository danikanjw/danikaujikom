<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Order;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\StockDelivery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    // Pending Vendors
    public function pendingVendors()
    {
        $pendingVendors = User::where('role', 'vendor')
            ->where('vendor_status', 'pending')
            ->where('is_active', false)
            ->get();
        return view('admin.pending_vendors', compact('pendingVendors'));
    }

    public function approveVendor($userId)
    {
        $user = User::where('role', 'vendor')
            ->where('vendor_status', 'pending')
            ->findOrFail($userId);
        $user->update(['vendor_status' => 'approved', 'is_active' => true]);
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

    // Feedback management
    public function manageFeedback()
    {
        $feedbacks = Feedback::with('user')->paginate(10);
        return view('admin.manage_feedback', compact('feedbacks'));
    }

    public function deleteFeedback($feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);
        $feedback->delete();
        return redirect()->route('admin.manage_feedback')->with('success', 'Feedback deleted successfully.');
    }

    public function manageStocks()
    {
        $stockDeliveries = StockDelivery::with(['vendor', 'items.product'])
            ->orderByRaw("CASE WHEN status = 'submitted' THEN 0 ELSE 1 END")
            ->latest('submitted_at')
            ->paginate(15);

        return view('admin.manage_stocks', compact('stockDeliveries'));
    }

    public function receiveStock(Request $request, $deliveryId)
    {
        $validated = $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0|max:4294967295',
        ]);

        DB::transaction(function () use ($validated, $deliveryId) {
            $delivery = StockDelivery::with('items')
                ->lockForUpdate()
                ->findOrFail($deliveryId);

            if ($delivery->status !== 'submitted') {
                throw ValidationException::withMessages([
                    'delivery' => 'This stock submission has already been reviewed.',
                ]);
            }

            $itemIds = $delivery->items->pluck('stock_delivery_item_id')->map(fn ($id) => (string) $id)->all();
            $quantityIds = array_map('strval', array_keys($validated['quantities']));

            if (array_diff($itemIds, $quantityIds) || array_diff($quantityIds, $itemIds)) {
                throw ValidationException::withMessages([
                    'quantities' => 'Provide a verified quantity for every product in this submission.',
                ]);
            }

            foreach ($delivery->items as $item) {
                $receivedQuantity = $validated['quantities'][$item->stock_delivery_item_id];

                $item->update(['quantity_received' => $receivedQuantity]);
                Product::where('product_id', $item->product_id)
                    ->increment('warehouse_quantity', $receivedQuantity);
            }

            $delivery->update([
                'status' => 'received',
                'received_by' => Session::get('user_id'),
                'received_at' => now(),
            ]);
        });

        return redirect()->route('admin.manage_stocks')
            ->with('success', 'Stock submission verified and received stock added to product inventory.');
    }

    public function rejectStock($deliveryId)
    {
        DB::transaction(function () use ($deliveryId) {
            $delivery = StockDelivery::lockForUpdate()->findOrFail($deliveryId);

            if ($delivery->status !== 'submitted') {
                throw ValidationException::withMessages([
                    'delivery' => 'This stock submission has already been reviewed.',
                ]);
            }

            $delivery->update([
                'status' => 'rejected',
                'received_by' => Session::get('user_id'),
            ]);
        });

        return redirect()->route('admin.manage_stocks')
            ->with('success', 'Stock submission rejected. Product inventory was not changed.');
    }
}
