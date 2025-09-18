<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingService;
use App\Models\UserAccount;
use App\Models\User;
use App\Models\City;
use App\Mail\SendReportMail;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Cek login
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $userId = Session::get('user_id');
        $productId = $request->product_id;
        $quantity = $request->quantity;

        // Cek stok produk
        $product = Product::find($productId);
        if ($quantity > $product->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock.');
        }

        // Cek apakah item sudah ada di cart
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Buat entry baru
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('product')->with('success', 'Product added to cart successfully.');
    }

    public function getCart()
    {
        // Cek login
        if (!Session::has('user_id')) {
            return response()->json(['error' => 'Please login first.'], 401);
        }

        $userId = Session::get('user_id');

        // Ambil cart items dengan product relation
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        $total = 0;
        $items = [];

        foreach ($cartItems as $item) {
            $subtotal = $item->product->price * $item->quantity;
            $total += $subtotal;

            $items[] = [
                'cart_id' => $item->cart_id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'subtotal' => $subtotal,
            ];
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function removeFromCart($cart_id)
    {
        // Cek login
        if (!Session::has('user_id')) {
            return response()->json(['error' => 'Please login first.'], 401);
        }

        $userId = Session::get('user_id');

        // Cari cart item
        $cartItem = Cart::where('cart_id', $cart_id)->where('user_id', $userId)->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        }

        // Hapus item
        $cartItem->delete();

        return response()->json(['success' => 'Item removed from cart.']);
    }

    public function checkout()
    {
        // Cek login
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $userId = Session::get('user_id');

        // Ambil data user, city, cart items
        $user = User::with('city')->find($userId);
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        // Hitung total cart
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        // Ambil shipping services dan user accounts
        $shippingServices = ShippingService::all();
        $userAccounts = UserAccount::all();

        // Buat array account numbers untuk JavaScript
        $accountNumbers = [];
        foreach ($userAccounts as $account) {
            $accountNumbers[$account->payment_account_id] = $account->account_number;
        }

        return view('checkout', compact('user', 'cartItems', 'total', 'shippingServices', 'userAccounts', 'accountNumbers'));
    }

    public function processCheckout(Request $request)
    {
        // Cek login
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $userId = Session::get('user_id');

        // Validasi input
        $request->validate([
            'shipping_service_id' => 'required|exists:shipping_services,shipping_service_id',
            'payment_method' => 'required|in:postpaid,prepaid',
            'payment_account_id' => 'required_if:payment_method,prepaid|exists:user_accounts,payment_account_id',
            'account_number' => 'required_if:payment_method,prepaid',
            'phone_number' => 'required_if:payment_method,postpaid',
        ]);

        // Cek kondisi khusus
        if ($request->payment_method === 'postpaid' && empty($request->phone_number)) {
            return redirect()->back()->with('error', 'Phone number is required for postpaid.');
        }

        if ($request->payment_method === 'prepaid' && empty($request->account_number)) {
            return redirect()->back()->with('error', 'Account number is required for prepaid.');
        }

        // Ambil cart
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Cart is empty.');
        }

        // Cek stok
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                return redirect()->back()->with('error', 'Insufficient stock for ' . $item->product->name);
            }
        }

        // Hitung total amount
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->product->price * $item->quantity;
        }


        // Create order
        $order = new Order();
        $order->user_id = $userId;
        $order->total_amount = $totalAmount;
        $order->order_date = now();
        $order->status = 'paid';
        $order->shipping_service_id = $request->shipping_service_id;
        $order->payment_method = $request->payment_method;
        $order->payment_prepaid = $request->payment_method === 'prepaid' ? 'bank_transfer' : 'paypal';
        // $order->payment_account_id = $request->payment_account_id ? UserAccount::where('bank_name', $request->payment_account_id)->value('payment_account_id') : null;
        $order->payment_account_id = $request->payment_account_id ?? null;

        $order->shipping_address = User::find($userId)->address;
        $order->save();

        // Create order items and update product stock
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->order_id;
            $orderItem->product_id = $item->product_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->product->price;
            $orderItem->save();

            // Update product stock
            $product = $item->product;
            $product->quantity -= $item->quantity;
            $product->save();
        }


        // Generate PDF invoice
        $pdf = Pdf::loadView('pdf.report', compact('order'));

        // Kirim email
        $pdfContent = $pdf->output();
        Mail::to($order->user->email)->send(new SendReportMail($pdfContent));

        // Kosongkan cart
        Cart::where('user_id', $userId)->delete();

        DB::commit();

        return redirect()->route('product')->with('success', 'Order processed successfully.');
    }
}
