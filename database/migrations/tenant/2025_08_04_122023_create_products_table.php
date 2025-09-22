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
        Schema::create('products', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->decimal("product_price", 8, 2);
            $table->integer('stock')->nullable();

            $table->string("image_path")->nullable();
            $table->json('gallery')->nullable();
            $table->text("short_description")->nullable();
            $table->longText('detailed_description')->nullable();

            $table->integer('stock_new')->nullable();
            $table->integer('stock_used')->nullable();
            $table->integer('stock_available')->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->decimal('deposit', 8, 2)->nullable();
            $table->string('packing_details')->nullable();
            $table->integer('on_order')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('last_service_date')->nullable();
            $table->boolean('requires_sanitization')->default(false);
            $table->integer('sanitization_period')->nullable();
            $table->integer('min_amount')->default(0)->nullable();
            $table->integer('max_amount')->default(0)->nullable();
            $table->integer('reorder_amount')->default(0)->nullable();
            $table->boolean('is_spare_part')->default(false);
            $table->boolean('is_retail_product')->default(false);
            $table->decimal('spare_part_cost', 8, 2)->default(0)->nullable();
            $table->integer('qty_per_palette')->default(0)->nullable();
            $table->boolean('is_accessory')->default(false);
            $table->decimal('bcrs_deposit', 8, 2)->default(0.00)->nullable();
            $table->decimal('eco_tax', 8, 2)->default(0.00)->nullable();
            $table->json('driver_commissions')->nullable();
            $table->timestamps();
        });

        Schema::create('price_types', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->boolean('is_rental')->default(false);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('product_price_types', function(Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('price_type_id')->constrained('price_types')->onDelete('cascade');
            $table->foreignId('vat_id')->constrained('vat_codes')->onDelete('cascade');

            $table->decimal('unit_price', 10, 2)->default(0.00)->nullable();
            $table->decimal('yearly_rental', 10, 2)->default(0.00)->nullable();
            $table->timestamps();

        });


        Schema::create('offer_products', function(Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('price_type_id')->nullable()->constrained('price_types')->onDelete('set null');
            $table->foreignId('vat_code_id')->nullable()->constrained('vat_codes')->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->decimal('bcrs_deposit', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('price_types');
        Schema::dropIfExists('product_price_types');
        Schema::dropIfExists('offer_products');
    }
};
