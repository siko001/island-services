<?php

namespace App\Models\Customer;

use App\Models\General\Area;
use App\Models\General\Location;
use App\Models\User;
use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'client',
        'account_number',
        'delivery_details_name',
        'delivery_details_surname',
        'delivery_details_company_name',
        'delivery_details_department',
        'delivery_details_address',
        'delivery_details_area_id',
        'delivery_details_locality_id',
        'delivery_details_post_code',
        'delivery_details_country',
        'delivery_details_telephone_home',
        'delivery_details_telephone_office',
        'delivery_details_fax_one',
        'delivery_details_fax_two',
        'delivery_details_email_one',
        'delivery_details_email_two',
        'delivery_details_mobile',
        'delivery_details_url',
        'delivery_details_id_number',
        'delivery_details_vat_number',
        'delivery_details_registration_number',
        'credit_terms_current',
        'credit_terms_default',
        'credit_limit_del',
        'credit_limit_dep',
        'balance_del',
        'balance_dep',
        'turnover',
        'billing_details_name',
        'billing_details_surname',
        'billing_details_company_name',
        'billing_details_department',
        'billing_details_address',
        'billing_details_area_id',
        'billing_details_locality_id',
        'billing_details_post_code',
        'billing_details_country',
        'billing_details_telephone_home',
        'billing_details_telephone_office',
        'billing_details_fax_one',
        'billing_details_fax_two',
        'billing_details_email_one',
        'billing_details_email_two',
        'billing_details_mobile',
        'billing_details_url',
        'billing_id_number',
        'billing_vat_number',
        'billing_registration_number',
        'summer_address',
        'summer_address_post_code',
        'summer_address_country',
        'summer_address_area_id',
        'summer_address_locality_id',
        'delivery_details_financial_name',
        'delivery_details_financial_surname',
        'billing_details_financial_name',
        'billing_details_financial_surname',
        'issue_invoices',
        'different_billing_details',
        'use_summer_address',
        'stop_deliveries',
        'account_closed',
        'barter_client',
        'stop_statement',
        'pet_client',
        'customer_groups_id',
        'classes_id',
        'client_statuses_id',
        'hear_about_id',
        'user_id',
        'client_types_id',
        'deliver_instruction',
        'directions',
        'remarks',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'summer_address_area_id' => 'integer',
        'summer_address_locality_id' => 'integer',
        'credit_terms_current' => 'integer',
        'credit_terms_default' => 'integer',
        'credit_limit_del' => 'decimal:2',
        'credit_limit_dep' => 'decimal:2',
        'balance_del' => 'decimal:2',
        'balance_dep' => 'decimal:2',
        'turnover' => 'decimal:2',
        "created_at" => 'datetime',
        "updated_at" => 'datetime',
        'issue_invoices' => "boolean",
        'different_billing_details' => "boolean",
        'use_summer_address' => "boolean",
        'stop_deliveries' => "boolean",
        'account_closed' => "boolean",
        'barter_client' => "boolean",
        'stop_statement' => "boolean",
        'pet_client' => "boolean",
        'delivery_details_area_id' => 'integer',
        'delivery_details_locality_id' => 'integer',
        'billing_details_area_id' => 'integer',
        'billing_details_locality_id' => 'integer',
    ];

    /**
     * Get the delivery address as a formatted string.
     * @return string
     */

    public function deliveryArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'delivery_details_area_id');
    }

    public function deliveryLocality(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'delivery_details_locality_id');
    }

    public function billingArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'billing_details_area_id');
    }

    public function billingLocality(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'billing_details_locality_id');
    }

    public function summerArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'summer_address_area_id');
    }

    public function summerLocality(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'summer_address_locality_id');
    }

    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_groups_id');
    }

    public function clientStatus(): BelongsTo
    {
        return $this->belongsTo(ClientStatus::class, 'client_statuses_id');
    }

    public function hearAbout(): BelongsTo
    {
        return $this->belongsTo(HearAbout::class, 'hear_about_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clientType(): BelongsTo
    {
        return $this->belongsTo(ClientType::class, 'client_types_id');
    }

    protected static function boot(): void
    {
        parent::boot();
        Customer::observe(CustomerObserver::class);

        static::saving(function($customer) {
            //Pipeline calls Global
            if(!$customer->different_billing_details) {
                $customer->copyDeliveryToBilling();
            }
        });

    }

    protected function copyDeliveryToBilling(): void
    {
        $fields = [
            'name', 'surname', 'company_name', 'department', 'address',
            'post_code', 'country', 'telephone_home', 'telephone_office',
            'fax_one', 'fax_two', 'email_one', 'email_two', 'mobile', 'url',
            'id_number', 'vat_number', 'registration_number',
            'financial_name', 'financial_surname', 'area_id', 'locality_id',
        ];

        foreach($fields as $field) {
            $this->{'billing_details_' . $field} = $this->{'delivery_details_' . $field};
        }
    }
}
