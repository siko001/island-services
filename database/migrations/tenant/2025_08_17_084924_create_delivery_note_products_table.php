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
        Schema::create('delivery_note_products', function(Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_note_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('price_type_id')->constrained('price_types')->onDelete('cascade');
            $table->integer('quantity')->default(1);

            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            $table->decimal('deposit_price', 10, 2)->nullable();
            $table->decimal('total_deposit_price', 10, 2)->nullable();

            $table->decimal('bcrs_deposit', 10, 2)->nullable();
            $table->decimal('total_bcrs_deposit', 10, 2)->nullable();

            $table->foreignId('vat_code_id')->constrained('vat_codes')->onDelete('cascade');

            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_note_products');
    }
};
