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
        Schema::create('collection_notes', function(Blueprint $table) {
            $table->id();
            $table->string('collection_note_number')->unique();
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();

            $table->foreignId('salesman_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_type_id')->constrained('order_types')->onDelete('cascade');

            $table->text('days_for_delivery')->nullable();

            $table->date('expiry_date')->nullable();
            $table->string('delivery_instructions')->nullable();
            $table->string('delivery_directions')->nullable();
            $table->text('remarks')->nullable();

            $table->boolean('status')->default(false);
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('customer_account_number')->nullable();
            $table->string('customer_email')->nullable();

            $table->foreignId('customer_area')->constrained('areas')->onDelete('cascade');
            $table->foreignId('customer_location')->constrained('locations')->onDelete('cascade');
            $table->string('customer_address')->nullable();
            $table->string('delivery_days')->nullable();

            $table->decimal('balance_on_delivery', 10, 2)->default(0.00);
            $table->decimal('credit_on_delivery', 10, 2)->default(0.00);

            $table->decimal('balance_on_deposit', 10, 2)->default(0.00);
            $table->decimal('credit_on_deposit', 10, 2)->default(0.00);

            $table->integer('credit_limit')->nullable()->default(0);
            $table->date('last_delivery_date')->nullable();

            $table->date('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_notes');
    }
};
