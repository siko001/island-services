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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
