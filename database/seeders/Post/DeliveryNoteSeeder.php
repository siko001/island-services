<?php

namespace Database\Seeders\Post;

use App\Models\Customer\Customer;
use App\Models\General\AreaLocation;
use App\Models\General\OrderType;
use App\Models\Post\DeliveryNote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DeliveryNoteSeeder extends Seeder
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

        $this->command->info('Creating delivery notes...');

        // Create 50 delivery notes
        $count = 50;
        for($i = 0; $i < $count; $i++) {
            try {
                // Get a random customer
                $customer = $customers->random();

                // Get customer details
                $customerArea = $customer->delivery_details_area_id;
                $customerLocation = $customer->delivery_details_locality_id;
                $customerAddress = $customer->delivery_details_address;
                $customerEmail = $customer->delivery_details_email_one;
                $customerAccountNumber = $customer->account_number;

                // Get random salesman and operator
                $salesman = $salesmen->random();
                $operator = $operators->random();

                // Get random order type
                $orderType = $orderTypes->random();

                // Generate dates
                // Make order date today or yesterday for more realistic data
                $orderDate = Carbon::now()->subDays(rand(0, 1));

                // Get the next delivery date based on the customer's area and location
                $deliveryDate = AreaLocation::getNextDeliveryDate($customerArea, $customerLocation, $customer->id);

                // If no delivery date could be determined, default to at least 2 days after order date
                if (!$deliveryDate) {
                    $deliveryDate = Carbon::parse($orderDate)->addDays(rand(2, 5));
                }

                // Create delivery note
                $deliveryNote = DeliveryNote::create([
                    'delivery_note_number' => DeliveryNote::generateDeliveryNoteNumber(),
                    'order_date' => $orderDate,
                    'delivery_date' => $deliveryDate,
                    'salesman_id' => $salesman->id,
                    'operator_id' => $operator->id,
                    'order_type_id' => $orderType->id,
                    'delivery_instructions' => "Delivery instructions for {$customer->client}",
                    'delivery_directions' => "Directions for {$customer->client}",
                    'remarks' => "Remarks for delivery note",
                    'status' => rand(0, 1), // Randomly set as processed or not
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
                ]);

                $this->command->info("Created delivery note #" . ($i + 1) . ": " . $deliveryNote->delivery_note_number . " for " . $customer->client);
            } catch(\Exception $e) {
                $this->command->error("Error creating delivery note: {$e->getMessage()}");
                Log::error("Error in DeliveryNoteSeeder: {$e->getMessage()}");
            }
        }

        $this->command->info("Successfully created {$count} delivery notes.");
    }
}
