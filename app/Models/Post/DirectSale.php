<?php

namespace App\Models\Post;

use App\Models\Customer\Customer;
use App\Models\General\Area;
use App\Models\General\Location;
use App\Models\General\OrderType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class DirectSale extends Model
{
    //
    protected $fillable = [
        'direct_sale_number',
        'order_date',
        'delivery_date',
        'salesman_id',
        'operator_id',
        'order_type_id',
        'expiry_date',
        'delivery_instructions',
        'delivery_directions',
        'remarks',
        'status',
        'customer_id',
        'customer_account_number',
        'customer_email',
        'customer_area',
        'customer_location',
        'customer_address',
        'delivery_days',
        'balance_on_delivery',
        'credit_on_delivery',
        'balance_on_deposit',
        'credit_on_deposit',
        'credit_limit',
        'last_delivery_date'
    ];
    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'expiry_date' => 'date',
        'last_delivery_date' => 'date',
        'status' => 'boolean',
        'balance_on_delivery' => 'decimal:2',
        'credit_on_delivery' => 'decimal:2',
        'balance_on_deposit' => 'decimal:2',
        'credit_on_deposit' => 'decimal:2',
        'credit_limit' => 'integer'
    ];

    public function salesman()
    {
        return $this->belongsTo('App\Models\User', 'salesman_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function orderType(): BelongsTo
    {
        return $this->belongsTo(OrderType::class, 'order_type_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'customer_area');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'customer_location');
    }

    public function directSaleProducts(): hasMany
    {
        return $this->hasMany(DirectSaleProduct::class);
    }

    public static function boot()
    {
        parent::boot();
        static::updating(function($directSale) {
            Log::info($directSale->isDirty('status'));
            if($directSale->isDirty('status') && $directSale->status == 1 && !$directSale->processed_at) {
                $directSale->processed_at = Carbon::now();

                foreach($directSale->directSaleProducts as $lineItem) {
                    Log::info('running: ' . $lineItem);
                    $product = $lineItem->product;
                    if($product) {
                        $product->stock -= $lineItem->quantity;
                        $product->save();
                    }
                }

            }
        });

    }
}
