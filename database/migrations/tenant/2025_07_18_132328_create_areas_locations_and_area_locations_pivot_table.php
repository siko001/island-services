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
        Schema::create('locations', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_direct_sale')->default(false);
            $table->timestamps();
        });

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

        Schema::create('area_location', function(Blueprint $table) {
            $table->id();

            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');

            // Unique number for that location in the area
            $table->unsignedInteger('location_number');

            // Boolean fields for each day of the week
            $table->boolean('monday')->default(false);
            $table->boolean('tuesday')->default(false);
            $table->boolean('wednesday')->default(false);
            $table->boolean('thursday')->default(false);
            $table->boolean('friday')->default(false);
            $table->boolean('saturday')->default(false);
            $table->boolean('sunday')->default(false);

            // Ensure the location number in the area is unique
            $table->unique(['area_id', 'location_number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('area_location');
    }
};
