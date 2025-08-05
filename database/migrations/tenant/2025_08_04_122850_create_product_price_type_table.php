<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_price_type', function(Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('price_type_id')->constrained('price_types')->onDelete('cascade');
            $table->foreignId('vat_id')->constrained('vat_codes')->onDelete('cascade');

            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('yearly_rental', 10, 2)->default(0.00)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_offer');
    }
};
