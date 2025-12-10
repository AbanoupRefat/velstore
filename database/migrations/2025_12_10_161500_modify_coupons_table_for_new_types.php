<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL to avoid doctrine/dbal dependency issues
        // Modify 'type' to accept any string (removing enum constraint)
        DB::statement("ALTER TABLE coupons MODIFY COLUMN type VARCHAR(50) NOT NULL");
        
        // Modify 'discount' to be nullable
        DB::statement("ALTER TABLE coupons MODIFY COLUMN discount DECIMAL(10, 2) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'discount' to not null (warning: will fail if nulls exist)
        // We can't easily guarantee reversion without data loss/cleanup, so we just try best effort or leave as is.
        // DB::statement("ALTER TABLE coupons MODIFY COLUMN discount DECIMAL(10, 2) NOT NULL");
        
        // Revert 'type' to enum
        // DB::statement("ALTER TABLE coupons MODIFY COLUMN type ENUM('percentage', 'fixed') NOT NULL");
    }
};
