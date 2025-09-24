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
        Schema::create('prepaid_offers', function(Blueprint $table) {
            $table->id();
            $table->string('prepaid_offer_number')->unique();
            $table->date('order_date')->nullable();

            $table->foreignId('salesman_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_type_id')->constrained('order_types')->onDelete('cascade');

            $table->string('delivery_instructions')->nullable();
            $table->string('delivery_directions')->nullable();
            $table->text('remarks')->nullable();

            $table->boolean('status')->default(false);
            $table->boolean('terminated')->default(false);
            $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');

            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('customer_account_number')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('days_for_delivery')->nullable();

            $table->foreignId('customer_area')->constrained('areas')->onDelete('cascade');
            $table->foreignId('customer_location')->constrained('locations')->onDelete('cascade');

            $table->string('customer_address')->nullable();
            $table->string('delivery_days')->nullable();

            $table->decimal('balance_on_delivery', 10, 2)->default(0.00);
            $table->decimal('balance_on_deposit', 10, 2)->default(0.00);

            $table->decimal('net', 10, 2)->default(0.00);
            $table->decimal('eco', 10, 2)->default(0.00);
            $table->decimal('vat', 10, 2)->default(0.00);
            $table->decimal('bcrs', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);

            $table->date('last_delivery_date')->nullable();

            $table->date('processed_at')->nullable();
            $table->date('terminated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('prepaid_offer_products', function(Blueprint $table) {
            $table->id();
            $table->foreignId('prepaid_offer_id')->constrained('prepaid_offers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('offer_id')->constrained('offers');
            $table->foreignId('price_type_id')->nullable()->constrained('price_types');
            $table->foreignId('vat_code_id')->nullable()->constrained('vat_codes');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('deposit', 10, 2)->default(0.00);
            $table->decimal('bcrs_deposit', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepaid_offers');
        Schema::dropIfExists('prepaid_offer_products');
    }
};
