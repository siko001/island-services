<?php

namespace Database\Seeders\Post;

use App\Helpers\HelperFunctions;
use App\Models\Customer\Customer;
use App\Models\General\Area;
use App\Models\General\AreaLocation;
use App\Models\General\Offer;
use App\Models\General\OrderType;
use App\Models\Post\PrepaidOffer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PrepaidOfferSeeder extends Seeder
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

        $offers = Offer::all();
        if($offers->isEmpty()) {
            $this->command->error('No Offers found. Please create offers and attach products first.');
            return;
        }

        // Check if we have salesmen
        $salesmen = User::whereHas('roles', function($query) {
            $query->where('name', 'like', '%salesman%');
        })->get();

        if($salesmen->isEmpty()) {
            $salesmen = User::all();
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
            $operators = $salesmen;
        }

        $this->command->info('Creating Prepaid Offer...');

        // Create 50 Prepaid Offer
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

                $salesman = $salesmen->random();
                $operator = $operators->random();
                $orderType = $orderTypes->random();
                $offer = $offers->random();

                $randomDate = Carbon::now()->subDays(rand(0, 7));
                $deliveryDate = AreaLocation::getNextDeliveryDate($customerArea, $customerLocation, $customer->id);
                if(!$deliveryDate) {
                    $deliveryDate = $randomDate;
                }

                $processed = rand(0, 1);
                $terminated = false;
                $processed && $terminated = rand(0, 1);

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

                    $daysForDelivery = implode(', ', $days);
                } else {
                    $this->command->warn('No area-location found or invalid area/location IDs.');
                    $daysForDelivery = 'No delivery information';
                }

                // Create Prepaid Offer
                $prepaidOffer = PrepaidOffer::create([
                    'prepaid_offer_number' => HelperFunctions::generateOrderNumber("prepaid_offer", PrepaidOffer::class),
                    'order_date' => $randomDate,
                    'salesman_id' => $salesman->id,
                    'operator_id' => $operator->id,
                    'order_type_id' => $orderType->id,
                    'days_for_delivery' => $daysForDelivery,
                    'delivery_instructions' => "Delivery instructions for {$customer->client}",
                    'delivery_directions' => "Directions for {$customer->client}",
                    'remarks' => Area::where('id', $customerArea)->first()->delivery_note_remark,
                    'offer_id' => $offer->id,
                    'processed_at' => null,
                    'status' => false,
                    'terminated' => false,
                    'customer_id' => $customer->id,
                    'customer_account_number' => $customerAccountNumber,
                    'customer_email' => $customerEmail,
                    'customer_area' => $customerArea,
                    'customer_location' => $customerLocation,
                    'customer_address' => $customerAddress,
                    'balance_on_delivery' => $customer->balance_del,
                    'balance_on_deposit' => $customer->balance_dep,
                    'created_at' => $randomDate,
                    'terminated_at' => null,
                    'net' => $offer->offerProducts->sum('total_price'),
                    'bcrs' => $offer->offerProducts->sum('bcrs_deposit'),
                    'vat' => round($offer->offerProducts->sum('total_price') * 0.18, 2),
                    'last_delivery_date' => null,
                ]);

                if(!$terminated && $processed) {
                    $prepaidOffer->status = true;
                    $prepaidOffer->processed_at = Carbon::now();
                    $prepaidOffer->save();
                }

                if($terminated && $processed) {
                    $prepaidOffer->terminated = true;
                    $prepaidOffer->terminated_at = Carbon::now();
                    $prepaidOffer->status = true;
                    $prepaidOffer->processed_at = Carbon::now();
                    $prepaidOffer->save();
                }

                $this->command->info("Created Prepaid Offer #" . ($i + 1) . ": " . $prepaidOffer->perpaid_offer_number . " for " . $customer->client);
            } catch(\Exception $e) {
                $this->command->error("Error creating Prepaid Offer: {$e->getMessage()}");
                Log::error("Error in PrepaidOfferSeeder: {$e->getMessage()}");
            }
        }

        $this->command->info("Successfully created {$count} Prepaid Offer.");
    }
}
