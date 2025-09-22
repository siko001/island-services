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
        Schema::create('order_types', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->boolean('short_period_type')->default(false);
            $table->boolean('is_direct_sale')->default(false);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('spare_parts', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->decimal('cost', 8, 2);
            $table->integer('on_order')->default(0);
            $table->integer('in_stock')->default(0);
            $table->timestamp('purchase_date')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation');
            $table->decimal('cost', 5, 2);
            $table->timestamps();
        });

        Schema::create('complaints', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('department');
            $table->timestamps();
        });

        Schema::create('vat_codes', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('abbreviation');
            $table->boolean('is_default')->default(false);
            $table->decimal('percentage', 5, 2)->default(0.00);
        });

        Schema::create('document_controls', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('department');
            $table->string('file_path')->nullable();
        });

        Schema::create('monetory_values', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->decimal('value', 10, 2);
        });

        Schema::create('offers', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_types');
        Schema::dropIfExists('spare_parts');
        Schema::dropIfExists('services');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('vat_codes');
        Schema::dropIfExists('document_controls');
        Schema::dropIfExists('monetory_values');
        Schema::dropIfExists('offers');
    }
};
