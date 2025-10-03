<?php

namespace Database\Seeders\Post;

use App\Helpers\HelperFunctions;
use App\Models\Customer\Customer;
use App\Models\General\AreaLocation;
use App\Models\General\OrderType;
use App\Models\Post\Repair;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class RepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $repairNotes = 50;
        $this->command->info("Creating Repair $$repairNotes Notes");

        $clients = Customer::all();
        if(empty($clients)) {
            $this->command->info("No Clients found please run the customer seeder");
        }

        $products = Product::where('is_spare_part', true)->get();
        if(empty($products)) {
            $this->command->info("No products that are spare part products found please run the product seeder or toggle the option on some of the products");
        }

        for($i = 0; $i < $repairNotes; $i++) {
            $repairNumber = HelperFunctions::generateOrderNumber("repair_note", Repair::class);
            $client = $clients->random();
            $product = $products->random();

            $this->command->info("Creating Repair note: $repairNumber for Client: $client->name with for Product: $product->name");

            // Get customer details
            $customerArea = $client->delivery_details_area_id;
            $customerLocation = $client->delivery_details_locality_id;
            $areaLocation = null;
            $daysForDelivery = null;
            if($customerArea && $customerLocation) {
                $areaLocation = AreaLocation::where('area_id', $customerArea)
                    ->where('location_id', $customerLocation)
                    ->first();
            }
            if($areaLocation) {
                $days = collect([
                    'Monday' => $areaLocation->monday,
                    'Tuesday' => $areaLocation->tuesday,
                    'Wednesday' => $areaLocation->wednesday,
                    'Thursday' => $areaLocation->thursday,
                    'Friday' => $areaLocation->friday,
                    'Saturday' => $areaLocation->saturday,
                    'Sunday' => $areaLocation->sunday,
                ])->filter(fn($delivered) => $delivered)->keys()->toArray();
                $daysForDelivery = implode(', ', $days);
            }

            Repair::create([
                'created_at' => now(),
                'processed_at' => null,
                'sanitization_date' => null,
                'collection_date' => null,
                'rental_date' => null,
                'date' => now(),
                'status' => 0,
                'replacement' => 0,
                'collection_note' => false,
                'collection_note_id' => null,
                'sanitization' => false,
                'customer_id' => $client->id,
                'customer_area' => $customerArea,
                'customer_location' => $customerLocation,
                'operator_id' => User::find(1)->id,
                'order_type_id' => OrderType::where('is_default', true)->first()->id,
                'salesman_id' => $client->user->id,
                'product_id' => $product->id,
                'technician_id' => null,
                'delivery_note_id' => null,
                'ownership_type' => "rented",
                'delivery_note_number' => null,
                'customer_address' => $client->delivery_details_address,
                'customer_telephone' => $client->delivery_details_telephone,
                'days_for_delivery' => $daysForDelivery,
                'make' => 1,
                'model' => 1,
                'serial_number' => 1,
                'delivery_instructions' => $client->deliver_instruction,
                'delivery_directions' => $client->directions,
                'balance_on_delivery' => $client->deliver_instruction,
                'balance_on_deposit' => $client->balance_del,
                'repair_note_number' => HelperFunctions::generateOrderNumber("repair_note", Repair::class),
                'faults_reported' => null,
                'customer_email' => $client->delivery_details_email_one,
                'customer_account_number' => $client->account_number,
                'customer_mobile' => $client->delivery_details_mobile,
            ]);
        }

    }
}
