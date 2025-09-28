<?php

namespace App\Models\Post;

use App\Models\General\Offer;
use App\Models\General\VatCode;
use App\Models\Product\PriceType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrepaidOfferProduct extends Model
{
    protected $fillable = [
        'prepaid_offer_id',
        'product_id',
        'offer_id',
        'price_type_id',
        'vat_code_id',
        'quantity',
        'price',
        'deposit',
        'bcrs_deposit',
        'total_price',
        'timestamps',
        'total_taken',
        'total_remaining',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'deposit' => 'decimal:2',
        'bcrs_deposit' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }

    public function vatCode(): BelongsTo
    {
        return $this->belongsTo(VatCode::class);
    }

    public function prepaidOffer(): BelongsTo
    {
        return $this->belongsTo(PrepaidOffer::class);
    }

    public static function boot()
    {
        parent::boot();

        static::updated(function($model) {

            //Check to see if total_remaining has changed to 0, if so check if all are 0 and if so mark the offer as terminated
            if($model->isDirty('total_remaining') && $model->total_remaining == 0) {
                $prepaidOffer = $model->prepaidOffer;
                if($prepaidOffer) {
                    $allZero = $prepaidOffer->prepaidOfferProducts()->where('total_remaining', '>', 0)->count() == 0;
                    if($allZero) {
                        $prepaidOffer->terminated = true;
                        $prepaidOffer->save();
                    }
                }
            }

            //Handle the case where total_remaining is increased (a refund scenario)
            if($model->isDirty('total_remaining') && $model->getOriginal('total_remaining') < $model->total_remaining) {
                $prepaidOffer = $model->prepaidOffer;
                if($prepaidOffer && $prepaidOffer->terminated) {
                    $prepaidOffer->terminated = false;
                    $prepaidOffer->save();
                }
            }

        });

        //      Refactor to cleanup
        //      Make Sure that api can be used for Direct Sales as well
        //      Add an addition column to the offer-products table for order_number tracking
        //      In the Boot delete method of PrepaidOfferProduct and DirectSaleProduct, to restore the quantity to the PrepaidOffer if has the above column and the parent offer

    }
}
