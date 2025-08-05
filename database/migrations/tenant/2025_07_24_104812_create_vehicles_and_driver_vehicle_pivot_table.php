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
        Schema::create('vehicles', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('make');
            $table->string('model')->nullable();
            $table->string('body_type')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('color')->nullable();
            $table->date('purchase_date')->nullable();
            $table->integer('purchase_price')->nullable();
            $table->string('cc')->nullable();
            $table->integer('manufacture_year')->nullable();
            $table->string('tonnage')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('tank_capacity')->nullable();
            $table->string('registration_number')->nullable();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
        });

        Schema::create('driver_vehicle', function(Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('driver_vehicle');
    }
};
