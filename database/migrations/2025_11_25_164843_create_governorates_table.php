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
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->string('name_en')->unique(); // English name
            $table->string('name_ar')->unique(); // Arabic name
            $table->decimal('shipping_fee', 8, 2)->default(0); // Shipping cost
            $table->boolean('active')->default(true); // Enable/disable
            $table->integer('delivery_days')->default(2); // Estimated delivery days
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governorates');
    }
};
