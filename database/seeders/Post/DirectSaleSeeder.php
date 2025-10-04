<?php

namespace Database\Seeders\Post;

use App\Helpers\HelperFunctions;
use App\Models\Customer\Customer;
use App\Models\General\Area;
use App\Models\General\AreaLocation;
use App\Models\General\Location;
use App\Models\General\OrderType;
use App\Models\Post\DirectSale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DirectSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have customers
        $customers = Customer::where('account_closed', false)->get();
        if($customers->isEmpty()) {
            $this->command->error('No active customers found. Please run CustomerSeeder first.');
            return;
        }

        // Check if we have order types
        $orderTypes = OrderType::all();
        if($orderTypes->isEmpty()) {
            $this->command->error('No order types found. Please create order types first.');
            return;
        }

        // Check if we have salesmen
        $salesmen = User::whereHas('roles', function($query) {
            $query->where('name', 'like', '%salesman%');
        })->get();

        if($salesmen->isEmpty()) {
            $salesmen = User::all(); // Fallback to all users if no salesmen found
            if($salesmen->isEmpty()) {
                $this->command->error('No users found. Please create users first.');
                return;
            }
        }

        // Check if we have operators
        $operators = User::whereHas('roles', function($query) {
            $query->where('name', 'like', '%operator%');
        })->get();

        if($operators->isEmpty()) {
            $operators = $salesmen; // Fallback to salesmen if no operators found
        }

        $this->command->info('Creating direct sales...');

        // Create 50 direct sales
        $count = 50;
        for($i = 0; $i < $count; $i++) {
            try {
                // Get a random customer
                $customer = $customers->random();

                // Get customer details
                $customerArea = Area::where('is_direct_sale', true)->first()->id;
                $customerLocation = Location::where('is_direct_sale', true)->first()->id;
                $customerAddress = $customer->delivery_details_address;
                $customerEmail = $customer->delivery_details_email_one;
                $customerAccountNumber = $customer->account_number;

                // Get random salesman and operator
                $salesman = $salesmen->random();
                $operator = $operators->random();

                // Get random order type
                $orderType = $orderTypes->random();

                $randomDate = Carbon::now()->subDays(rand(0, 7));

                // Get the next delivery date based on the customer's area and location
                $deliveryDate = AreaLocation::getNextDeliveryDate($customerArea, $customerLocation, $customer->id);

                // If no delivery date could be determined, default to at least 2 days after order date
                if(!$deliveryDate) {
                    $deliveryDate = $randomDate;
                }

                $processed = rand(0, 1);

                $this->command->info("Customer Area: {$customerArea}, Location: {$customerLocation}");

                $areaLocation = null;
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

                    $this->command->info("Delivery days: " . implode(', ', $days));

                    $daysForDelivery = implode(', ', $days);
                } else {
                    $this->command->warn('No area-location found or invalid area/location IDs.');
                    $daysForDelivery = 'No delivery information';
                }

                // Create direct sale
                $directSale = DirectSale::create([
                    'direct_sale_number' => HelperFunctions::generateOrderNumber("direct_sale", DirectSale::class),
                    'order_date' => $randomDate,
                    'delivery_date' => $deliveryDate,
                    'salesman_id' => $salesman->id,
                    'operator_id' => $operator->id,
                    'order_type_id' => $orderType->id,
                    'days_for_delivery' => $daysForDelivery,
                    'delivery_instructions' => "Delivery instructions for {$customer->client}",
                    'delivery_directions' => "Directions for {$customer->client}",
                    'remarks' => Area::where('is_direct_sale', true, '')->first()->delivery_note_remark,
                    'processed_at' => $processed ? $deliveryDate : null,
                    'status' => $processed,
                    'customer_id' => $customer->id,
                    'customer_account_number' => $customerAccountNumber,
                    'customer_email' => $customerEmail,
                    'customer_area' => $customerArea,
                    'customer_location' => $customerLocation,
                    'customer_address' => $customerAddress,
                    'balance_on_delivery' => $customer->balance_del,
                    'credit_on_delivery' => $customer->credit_limit_del,
                    'balance_on_deposit' => $customer->balance_dep,
                    'credit_on_deposit' => $customer->credit_limit_dep,
                    'credit_limit' => $customer->credit_terms_current,
                    'created_at' => $randomDate,
                ]);

                $this->command->info("Created direct sale #" . ($i + 1) . ": " . $directSale->direct_sale_number . " for " . $customer->client);
            } catch(\Exception $e) {
                $this->command->error("Error creating direct sale: {$e->getMessage()}");
                Log::error("Error in DirectSaleSeeder: {$e->getMessage()}");
            }
        }

        $this->command->info("Successfully created {$count} direct sales.");
    }
}
