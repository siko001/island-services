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
        Schema::create('repair_notes', function(Blueprint $table) {
            $table->id();

            $table->string('repair_note_number')->unique();
            $table->date('date')->nullable();
            $table->boolean('status')->default(false);
            $table->foreignId('customer_id')->constrained('products')->onDelete('cascade');
            $table->string('customer_account_number');
            $table->text('customer_email');
            $table->text('customer_address')->nullable();
            $table->text('customer_telephone')->nullable();
            $table->text('customer_mobile')->nullable();
            $table->text('days_for_delivery')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->text('delivery_directions')->nullable();
            $table->text('balance_on_delivery')->nullable();
            $table->text('balance_on_deposit')->nullable();

            $table->foreignId('customer_area')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_location')->constrained('order_types')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_type_id')->constrained('order_types')->onDelete('cascade');
            $table->foreignId('salesman_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->text('make');
            $table->text('model');
            $table->text('serial_number');

            $table->enum('ownership_type', ['rented', 'purchased']);
            $table->date('rental_date')->nullable();

            //            $table->foreignId('reference_number')->constrained('rental_agreements')->onDelete('cascade');
            $table->longText('faults_reported')->nullable();

            $table->boolean('replacement')->default(false);
            $table->foreignId('delivery_note_id')->nullable()->constrained('delivery_notes')->onDelete('cascade');
            $table->string('delivery_note_number')->nullable();

            $table->boolean('collection_note')->default(false);
            $table->foreignId('collection_note_id')->nullable()->constrained('collection_notes')->onDelete('cascade');
            $table->date('collection_date')->nullable();

            $table->boolean('sanitization')->default(false);
            $table->date('sanitization_date')->nullable();

            $table->timestamps();
            $table->date('processed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
