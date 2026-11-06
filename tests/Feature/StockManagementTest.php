<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockDelivery;
use App\Models\StockDeliveryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_verification_adds_only_verified_stock_to_available_inventory(): void
    {
        $admin = $this->createUser('admin', 'admin-user');
        $vendor = $this->createUser('vendor', 'vendor-user');
        $product = $this->createProduct($vendor, 4);
        $delivery = $this->createDelivery($vendor, $product, 10);

        $response = $this->withSession(['user_id' => $admin->user_id, 'role' => 'admin'])
            ->post(route('admin.receive_stock', $delivery->stock_delivery_id), [
                'quantities' => [$delivery->items->first()->stock_delivery_item_id => 7],
            ]);

        $response->assertRedirect(route('admin.manage_stocks'));
        $this->assertDatabaseHas('products', [
            'product_id' => $product->product_id,
            'warehouse_quantity' => 11,
        ]);
        $this->assertDatabaseHas('stock_delivery_items', [
            'stock_delivery_item_id' => $delivery->items->first()->stock_delivery_item_id,
            'quantity_received' => 7,
        ]);
        $this->assertDatabaseHas('stock_deliveries', [
            'stock_delivery_id' => $delivery->stock_delivery_id,
            'status' => 'received',
            'received_by' => $admin->user_id,
        ]);
    }

    public function test_rejected_stock_submission_does_not_change_product_inventory(): void
    {
        $admin = $this->createUser('admin', 'admin-user');
        $vendor = $this->createUser('vendor', 'vendor-user');
        $product = $this->createProduct($vendor, 4);
        $delivery = $this->createDelivery($vendor, $product, 10);

        $response = $this->withSession(['user_id' => $admin->user_id, 'role' => 'admin'])
            ->post(route('admin.reject_stock', $delivery->stock_delivery_id));

        $response->assertRedirect(route('admin.manage_stocks'));
        $this->assertDatabaseHas('products', [
            'product_id' => $product->product_id,
            'warehouse_quantity' => 4,
        ]);
        $this->assertDatabaseHas('stock_deliveries', [
            'stock_delivery_id' => $delivery->stock_delivery_id,
            'status' => 'rejected',
        ]);
    }

    public function test_vendor_stock_submission_is_saved_for_admin_review(): void
    {
        $vendor = $this->createUser('vendor', 'vendor-user');
        $product = $this->createProduct($vendor, 0);

        $response = $this->withSession(['user_id' => $vendor->user_id, 'role' => 'vendor'])
            ->post(route('vendor.stock.submit', $product->product_id), [
                'quantity' => 12,
                'notes' => 'Initial shipment',
            ]);

        $response->assertRedirect(route('vendor.product.index'));
        $this->assertDatabaseHas('stock_deliveries', [
            'vendor_id' => $vendor->user_id,
            'status' => 'submitted',
            'notes' => 'Initial shipment',
        ]);
        $this->assertDatabaseHas('stock_delivery_items', [
            'product_id' => $product->product_id,
            'quantity_sent' => 12,
            'quantity_received' => null,
        ]);
        $this->assertDatabaseHas('products', [
            'product_id' => $product->product_id,
            'warehouse_quantity' => 0,
        ]);
    }

    private function createUser(string $role, string $username): User
    {
        return User::create([
            'name' => ucfirst($role) . ' User',
            'username' => $username,
            'email' => $username . '@example.com',
            'password' => Hash::make('password'),
            'role' => $role,
            'is_active' => true,
        ]);
    }

    private function createProduct(User $vendor, int $warehouseQuantity): Product
    {
        $category = Category::create(['name' => 'Medical Supplies']);

        return Product::create([
            'vendor_id' => $vendor->user_id,
            'category_id' => $category->category_id,
            'name' => 'Test Product',
            'code' => 'MS-001',
            'description' => 'Test stock item',
            'price' => 10000,
            'warehouse_quantity' => $warehouseQuantity,
        ]);
    }

    private function createDelivery(User $vendor, Product $product, int $quantity): StockDelivery
    {
        $delivery = StockDelivery::create([
            'vendor_id' => $vendor->user_id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        StockDeliveryItem::create([
            'stock_delivery_id' => $delivery->stock_delivery_id,
            'product_id' => $product->product_id,
            'quantity_sent' => $quantity,
        ]);

        return $delivery->load('items');
    }
}
