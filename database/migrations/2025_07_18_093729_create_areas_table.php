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
        Schema::create('areas', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->boolean('is_foreign_area')->default(false);
            // Commission fields
            $table->float('commission_paid_outstanding_delivery', 8, 2)->default(0.00);
            $table->float('commission_paid_outstanding_deposit', 8, 2)->default(0.00);
            $table->float('commission_cash_delivery', 8, 2)->default(0.00);
            $table->float('commission_cash_deposit', 8, 2)->default(0.00);

            $table->string('delivery_note_remark')->nullable();
            $table->string('customer_care_email')->nullable();
            $table->timestamps();
            // Indexes
            $table->index(['name', 'abbreviation'], 'idx_areas_name_abbreviation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
