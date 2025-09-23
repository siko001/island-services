<?php

namespace App\Models\Post;

use App\Helpers\HelperFunctions;
use App\Models\Customer\Customer;
use App\Models\General\Area;
use App\Models\General\Location;
use App\Models\General\OrderType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionNote extends Model
{
    protected $fillable = [
        'delivery_note_number',
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

    public function salesman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'salesman_id');
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


    //    Create and uncomment
    //    public function collectionNoteProducts()
    //    {
    //        return $this->hasMany(CollectionNoteProducts::class);
    //    }

    public static function boot()
    {
        parent::boot();
        static::updating(function($collectionNote) {
            if($collectionNote->isDirty('status') && $collectionNote->status == 1 && !$collectionNote->processed_at) {
                $collectionNote->processed_at = Carbon::now();
                foreach($collectionNote->collectionNoteProducts as $lineItem) {
                    $product = $lineItem->product;
                }
            }
        });

    }

    public function replicate(array $except = null): CollectionNote
    {
        $new = parent::replicate($except);
        $new->collection_note_number = HelperFunctions::generateOrderNumber('collection_note', $new);
        return $new;
    }
}
