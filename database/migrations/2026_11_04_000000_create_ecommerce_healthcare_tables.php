<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('city_id');
            $table->string('city_name', 100);
            $table->string('province_name', 100);
            $table->timestamps();

            $table->unique(['city_name', 'province_name']);
        });

        Schema::create('store_categories', function (Blueprint $table) {
            $table->increments('store_category_id');
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('category_id');
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->unsignedInteger('city_id')->nullable();
            $table->string('name', 100);
            $table->string('username', 50)->unique();
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone', 20)->nullable();
            $table->string('role', 20)->default('customer');
            $table->timestamps();

            $table->foreign('city_id')
                ->references('city_id')
                ->on('cities')
                ->nullOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('shipping_services', function (Blueprint $table) {
            $table->increments('shipping_service_id');
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('stores', function (Blueprint $table) {
            $table->increments('store_id');
            $table->unsignedInteger('vendor_id')->unique();
            $table->unsignedInteger('store_category_id');
            $table->unsignedInteger('city_id');
            $table->string('store_name', 150);
            $table->text('address');
            $table->text('description')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('store_category_id')
                ->references('store_category_id')
                ->on('store_categories')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('city_id')
                ->references('city_id')
                ->on('cities')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('category_id');
            $table->string('code', 50);
            $table->string('product_name', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['store_id', 'code']);
            $table->foreign('store_id')
                ->references('store_id')
                ->on('stores')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('cart_item_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->unsignedInteger('user_id');
            $table->string('payment_method', 10);
            $table->string('status', 25)->default('pending_payment');
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('store_orders', function (Blueprint $table) {
            $table->increments('store_order_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('store_id');
            $table->string('fulfillment_type', 10);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('status', 20)->default('pending');
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->string('refund_status', 20)->default('none');
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'store_id']);
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('store_id')
                ->references('store_id')
                ->on('stores')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('item_id');
            $table->unsignedInteger('store_order_id');
            $table->unsignedInteger('product_id');
            $table->string('product_name', 150);
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2);
            $table->timestamps();

            $table->foreign('store_order_id')
                ->references('store_order_id')
                ->on('store_orders')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->unsignedInteger('order_id');
            $table->string('transaction_id', 100)->unique();
            $table->decimal('amount', 12, 2);
            $table->string('status', 10)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('shipment_id');
            $table->unsignedInteger('store_order_id')->unique();
            $table->unsignedInteger('shipping_service_id');
            $table->string('tracking_number', 100)->nullable();
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 20);
            $table->text('recipient_address');
            $table->string('recipient_city', 100);
            $table->string('recipient_postal_code', 10)->nullable();
            $table->string('status', 15)->default('processing');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->foreign('store_order_id')
                ->references('store_order_id')
                ->on('store_orders')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('shipping_service_id')
                ->references('shipping_service_id')
                ->on('shipping_services')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('invoice_id');
            $table->unsignedInteger('order_id')->unique();
            $table->string('invoice_number', 50)->unique();
            $table->string('file_path', 255)->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('feedback_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('store_order_id');
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('store_order_id')
                ->references('store_order_id')
                ->on('store_orders')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('store_id')
                ->references('store_id')
                ->on('stores')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        $checks = [
            "ALTER TABLE `users` ADD CONSTRAINT `users_role_chk` CHECK (`role` IN ('admin', 'vendor', 'customer'))",
            "ALTER TABLE `stores` ADD CONSTRAINT `stores_status_chk` CHECK (`status` IN ('pending', 'approved', 'rejected'))",
            "ALTER TABLE `products` ADD CONSTRAINT `products_price_chk` CHECK (`price` >= 0)",
            "ALTER TABLE `products` ADD CONSTRAINT `products_quantity_chk` CHECK (`quantity` >= 0)",
            "ALTER TABLE `cart_items` ADD CONSTRAINT `cart_items_quantity_chk` CHECK (`quantity` > 0)",
            "ALTER TABLE `orders` ADD CONSTRAINT `orders_payment_method_chk` CHECK (`payment_method` IN ('prepaid', 'postpaid'))",
            "ALTER TABLE `orders` ADD CONSTRAINT `orders_status_chk` CHECK (`status` IN ('pending_payment', 'processing', 'completed', 'partially_cancelled', 'cancelled'))",
            "ALTER TABLE `orders` ADD CONSTRAINT `orders_total_amount_chk` CHECK (`total_amount` >= 0)",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_fulfillment_chk` CHECK (`fulfillment_type` IN ('delivery', 'pickup'))",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_subtotal_chk` CHECK (`subtotal` >= 0)",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_shipping_cost_chk` CHECK (`shipping_cost` >= 0)",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_total_amount_chk` CHECK (`total_amount` >= 0)",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_status_chk` CHECK (`status` IN ('pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'))",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_refund_amount_chk` CHECK (`refund_amount` >= 0 AND `refund_amount` <= `total_amount`)",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_refund_status_chk` CHECK (`refund_status` IN ('none', 'pending', 'succeeded', 'failed'))",
            "ALTER TABLE `store_orders` ADD CONSTRAINT `store_orders_refund_full_chk` CHECK (`refund_status` <> 'succeeded' OR `refund_amount` = `total_amount`)",
            "ALTER TABLE `order_items` ADD CONSTRAINT `order_items_quantity_chk` CHECK (`quantity` > 0)",
            "ALTER TABLE `order_items` ADD CONSTRAINT `order_items_price_chk` CHECK (`price` >= 0)",
            "ALTER TABLE `payments` ADD CONSTRAINT `payments_amount_chk` CHECK (`amount` >= 0)",
            "ALTER TABLE `payments` ADD CONSTRAINT `payments_status_chk` CHECK (`status` IN ('pending', 'paid', 'failed', 'expired'))",
            "ALTER TABLE `shipments` ADD CONSTRAINT `shipments_status_chk` CHECK (`status` IN ('processing', 'shipped', 'delivered'))",
            "ALTER TABLE `feedbacks` ADD CONSTRAINT `feedbacks_target_chk` CHECK ((`store_id` IS NOT NULL AND `product_id` IS NULL) OR (`store_id` IS NULL AND `product_id` IS NOT NULL))",
            "ALTER TABLE `feedbacks` ADD CONSTRAINT `feedbacks_rating_chk` CHECK (`rating` BETWEEN 1 AND 5)",
        ];

        foreach ($checks as $check) {
            DB::statement($check);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('store_orders');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('products');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('shipping_services');
        Schema::dropIfExists('users');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('store_categories');
        Schema::dropIfExists('cities');
    }
};
