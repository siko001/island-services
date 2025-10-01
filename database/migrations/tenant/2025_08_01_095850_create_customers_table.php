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
        Schema::create('customers', function(Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->string('account_number')->nullable();
            $table->boolean('issue_invoices')->default(false);
            $table->boolean('different_billing_details')->default(false);
            $table->boolean('use_summer_address')->default(false);
            $table->boolean('stop_deliveries')->default(false);
            $table->boolean('account_closed')->default(false);
            $table->boolean('barter_client')->default(false);
            $table->boolean('stop_statement')->default(false);
            $table->boolean('pet_client')->default(false);
            $table->boolean('has_default_products')->default(false);

            //Delivery details
            $table->string('delivery_details_name')->nullable();
            $table->string('delivery_details_surname')->nullable();
            $table->string('delivery_details_company_name')->nullable();
            $table->string('delivery_details_department')->nullable();
            $table->string('delivery_details_address')->nullable();
            $table->foreignId('delivery_details_area_id')->constrained('areas')->onDelete('cascade');
            $table->foreignId('delivery_details_locality_id')->constrained('locations')->onDelete('cascade');
            $table->string('delivery_details_post_code')->nullable();
            $table->string('delivery_details_country')->nullable();
            $table->string('delivery_details_telephone_home')->nullable();
            $table->string('delivery_details_telephone_office')->nullable();
            $table->string('delivery_details_fax_one')->nullable();
            $table->string('delivery_details_fax_two')->nullable();
            $table->string('delivery_details_email_one')->nullable();
            $table->string('delivery_details_email_two')->nullable();
            $table->string('delivery_details_mobile')->nullable();
            $table->string('delivery_details_url')->nullable();
            $table->string('delivery_details_id_number')->nullable();
            $table->string('delivery_details_vat_number')->nullable();
            $table->string('delivery_details_registration_number')->nullable();
            $table->string('delivery_details_financial_name')->nullable();
            $table->string('delivery_details_financial_surname')->nullable();

            //Credit
            $table->integer('credit_terms_current')->nullable()->default(0);
            $table->integer('credit_terms_default')->nullable()->default(40);
            $table->decimal('credit_limit_del', 10, 2)->nullable()->default(0);
            $table->decimal('credit_limit_dep', 10, 2)->nullable()->default(0);
            $table->decimal('balance_del', 10, 2)->nullable()->default(0);
            $table->decimal('balance_dep', 10, 2)->nullable()->default(0);
            $table->decimal('turnover', 10, 2)->nullable()->default(0);

            //Billing details
            $table->string('billing_details_name')->nullable();
            $table->string('billing_details_surname')->nullable();
            $table->string('billing_details_company_name')->nullable();
            $table->string('billing_details_department')->nullable();
            $table->string('billing_details_address')->nullable();
            $table->foreignId('billing_details_area_id')->nullable()->constrained('areas')->onDelete('cascade');
            $table->foreignId('billing_details_locality_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->string('billing_details_post_code')->nullable();
            $table->string('billing_details_country')->nullable();
            $table->string('billing_details_telephone_home')->nullable();
            $table->string('billing_details_telephone_office')->nullable();
            $table->string('billing_details_fax_one')->nullable();
            $table->string('billing_details_fax_two')->nullable();
            $table->string('billing_details_email_one')->nullable();
            $table->string('billing_details_email_two')->nullable();
            $table->string('billing_details_mobile')->nullable();
            $table->string('billing_details_url')->nullable();
            $table->string('billing_details_id_number')->nullable();
            $table->string('billing_details_vat_number')->nullable();
            $table->string('billing_details_registration_number')->nullable();
            $table->string('billing_details_financial_name')->nullable();
            $table->string('billing_details_financial_surname')->nullable();

            //Summer Address
            $table->string('summer_address')->nullable();
            $table->string('summer_address_post_code')->nullable();
            $table->string('summer_address_country')->nullable();
            $table->foreignId('summer_address_area_id')->nullable()->constrained('areas')->onDelete('cascade');
            $table->foreignId('summer_address_locality_id')->nullable()->constrained('locations')->onDelete('cascade');

            //Other Details / remarks
            $table->foreignId('customer_groups_id')->constrained('customer_groups')->onDelete('cascade');
            $table->foreignId('classes_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('client_statuses_id')->constrained('client_statuses')->onDelete('cascade');
            $table->foreignId('hear_about_id')->nullable()->constrained('hear_abouts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('client_types_id')->constrained('client_types')->onDelete('cascade');
            $table->string('deliver_instruction')->nullable();
            $table->string('directions')->nullable();
            $table->string('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
