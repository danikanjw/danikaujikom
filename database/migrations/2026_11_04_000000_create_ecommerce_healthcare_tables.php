<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->string('province_name', 100)->nullable();
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
            $table->string('password');
            $table->rememberToken();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('contact_no', 30)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('role', 20)->default('customer');
            $table->string('vendor_status', 20)->nullable();
            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->nullOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('shipping_services', function (Blueprint $table) {
            $table->increments('shipping_service_id');
            $table->string('name', 100)->unique();
            $table->unsignedSmallInteger('estimated_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->increments('payment_account_id');
            $table->string('bank_name', 100);
            $table->string('account_number', 100);
            $table->string('account_holder', 150);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('category_id');
            $table->string('name', 150);
            $table->string('code', 50)->unique();
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('warehouse_quantity')->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('cart', function (Blueprint $table) {
            $table->increments('cart_id');
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
            $table->unsignedInteger('shipping_service_id')->nullable();
            $table->string('status', 25)->default('pending_payment');
            $table->decimal('total_amount', 12, 2);
            $table->dateTime('order_date');
            $table->text('shipping_address');
            $table->string('tracking_number', 100)->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('shipping_service_id')
                ->references('shipping_service_id')
                ->on('shipping_services')
                ->nullOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('payment_account_id');
            $table->unsignedInteger('verified_by')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('status', 25)->default('pending_verification');
            $table->string('bank_name', 100);
            $table->string('account_number', 100);
            $table->string('account_holder', 150);
            $table->string('proof_path')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
            $table->foreign('payment_account_id')
                ->references('payment_account_id')
                ->on('payment_accounts')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('verified_by')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('item_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2);
            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('stock_deliveries', function (Blueprint $table) {
            $table->increments('stock_delivery_id');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('received_by')->nullable();
            $table->string('status', 20)->default('submitted');
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('received_by')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('stock_delivery_items', function (Blueprint $table) {
            $table->increments('stock_delivery_item_id');
            $table->unsignedInteger('stock_delivery_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('quantity_sent');
            $table->unsignedInteger('quantity_received')->nullable();
            $table->timestamps();

            $table->unique(['stock_delivery_id', 'product_id']);
            $table->foreign('stock_delivery_id')
                ->references('stock_delivery_id')
                ->on('stock_deliveries')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        Schema::create('warehouse_settings', function (Blueprint $table) {
            $table->increments('warehouse_id');
            $table->string('name', 150);
            $table->text('address');
            $table->string('city', 100);
            $table->string('contact_phone', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('feedback_id');
            $table->unsignedInteger('user_id');
            $table->text('message');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('warehouse_settings');
        Schema::dropIfExists('stock_delivery_items');
        Schema::dropIfExists('stock_deliveries');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('products');
        Schema::dropIfExists('payment_accounts');
        Schema::dropIfExists('shipping_services');
        Schema::dropIfExists('users');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cities');
    }
};
