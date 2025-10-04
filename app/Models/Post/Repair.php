<?php

namespace App\Models\Post;

use App\Enums\OwnershipType;
use App\Models\Customer\Customer;
use App\Models\General\Area;
use App\Models\General\Location;
use App\Models\General\OrderType;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repair extends Model
{
    protected $table = 'repair_notes';
    protected $fillable = [
        'create_at',
        'updated_at',
        'processed_at',
        'sanitization_date',
        'collection_date',
        'rental_date',
        'date',
        'status',
        'replacement',
        'collection_note',
        'sanitization',
        'customer_id',
        'customer_area',
        'customer_location',
        'operator_id',
        'order_type_id',
        'salesman_id',
        'product_id',
        'technician_id',
        'delivery_note_id',
        'ownership_type',
        'delivery_note_number',
        'customer_address',
        'customer_telephone',
        'days_for_delivery',
        'make',
        'model',
        'serial_number',
        'delivery_instructions',
        'delivery_directions',
        'balance_on_delivery',
        'balance_on_deposit',
        'customer_id',
        'repair_note_number',
        'faults_reported',
        'customer_email',
        'customer_account_number',
        'customer_mobile',
        //        'reference_number'

    ];
    protected $casts = [
        'create_at' => 'date',
        'updated_at' => 'date',
        'processed_at' => 'date',
        'sanitization_date' => 'date',
        'collection_date' => 'date',
        'rental_date' => 'date',
        'date' => 'date',
        'status' => 'boolean',
        'replacement' => 'boolean',
        'collection_note' => 'boolean',
        'sanitization' => 'boolean',
        'ownership_type' => OwnershipType::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'customer_area');
    }

    public function salesman(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'customer_location');
    }

    public function orderType(): BelongsTo
    {
        return $this->belongsTo(OrderType::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id');
    }
}
