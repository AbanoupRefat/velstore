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
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('usage_limit')->nullable()->after('expires_at');
            $table->integer('usage_count')->default(0)->after('usage_limit');
            $table->integer('per_user_limit')->default(1)->after('usage_count');
            $table->decimal('min_order_amount', 10, 2)->nullable()->after('per_user_limit');
            $table->decimal('max_discount', 10, 2)->nullable()->after('min_order_amount');
            $table->timestamp('starts_at')->nullable()->after('max_discount');
            $table->boolean('is_active')->default(true)->after('starts_at');
            $table->text('description')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'usage_limit',
                'usage_count',
                'per_user_limit',
                'min_order_amount',
                'max_discount',
                'starts_at',
                'is_active',
                'description'
            ]);
        });
    }
};
