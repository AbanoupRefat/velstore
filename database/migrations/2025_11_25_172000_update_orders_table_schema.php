<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rename total_amount to total_price if it exists
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total_amount', 'total_price');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            // Create total_price if it didn't exist (and wasn't renamed)
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0);
            }

            // Add missing columns
            $table->decimal('shipping_cost', 8, 2)->default(0)->after('total_price');
            $table->text('shipping_address')->nullable()->after('customer_id');
            $table->text('billing_address')->nullable()->after('shipping_address');
            $table->string('payment_method')->nullable()->after('billing_address');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('tracking_number')->nullable()->after('status');
            $table->timestamp('order_date')->useCurrent()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('total_price', 'total_amount');
            $table->dropColumn([
                'shipping_cost',
                'shipping_address',
                'billing_address',
                'payment_method',
                'payment_status',
                'tracking_number',
                'order_date'
            ]);
        });
    }
};
