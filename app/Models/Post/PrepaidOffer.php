<?php

namespace App\Models\Post;

use App\Models\Customer\Customer;
use App\Models\General\Area;
use App\Models\General\Location;
use App\Models\General\Offer;
use App\Models\General\OfferProduct;
use App\Models\General\OrderType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrepaidOffer extends Model
{
    protected $table = 'prepaid_offers';
    protected $fillable = [
        'order_date',
        "salesman_id",
        "operator_id",
        "order_type_id",
        "delivery_instructions",
        "delivery_directions",
        "remarks",
        "status",
        "terminated",
        "offer_id",
        "customer_id",
        "customer_account_number",
        "customer_email",
        "days_for_delivery",
        "customer_area",
        "customer_location",
        "customer_address",
        "delivery_days",
        "balance_on_delivery",
        "balance_on_deposit",
        "net",
        "eco",
        "vat",
        "bcrs",
        "total",
        "timestamps",
        "last_delivery_date",
        "processed_at",
        "terminated_at",
    ];
    protected $casts = [
        "terminated_at" => "datetime",
        "processed_at" => "datetime",
        "timestamps" => "datetime",
        "order_date" => "datetime",
        "last_delivery_date" => "date",
        "total" => 'decimal:2',
        "balance_on_delivery" => 'decimal:2',
        "balance_on_deposit" => 'decimal:2',
        "net" => 'decimal:2',
        "eco" => 'decimal:2',
        "vat" => 'decimal:2',
        "bcrs" => 'decimal:2',
        "status" => "boolean",
        "terminated" => "boolean",
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesman(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderType(): BelongsTo
    {
        return $this->belongsTo(OrderType::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'customer_area');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'customer_location');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function prepaidOfferProducts(): HasMany
    {
        return $this->hasMany(PrepaidOfferProduct::class);
    }

    public static function boot()
    {
        parent::boot();
        static::created(function($model) {
            $offerProducts = OfferProduct::where('offer_id', $model->offer_id)->get();

            foreach($offerProducts as $offerProduct) {
                $model->prepaidOfferProducts()->create([
                    'product_id' => $offerProduct->product_id,
                    'offer_id' => $offerProduct->offer_id,
                    'price_type_id' => $offerProduct->price_type_id,
                    'vat_code_id' => $offerProduct->vat_code_id,
                    'quantity' => $offerProduct->quantity,
                    'price' => $offerProduct->price,
                    'deposit' => $offerProduct->deposit ?? 0,
                    'bcrs_deposit' => $offerProduct->bcrs_deposit,
                    'total_price' => $offerProduct->total_price,
                ]);
            }
        });
    }
}
