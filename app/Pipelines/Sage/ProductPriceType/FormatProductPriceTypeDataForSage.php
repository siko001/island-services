<?php

namespace App\Pipelines\Sage\ProductPriceType;

use App\Models\General\VatCode;
use App\Models\Product\Product;
use Closure;
use Illuminate\Support\Facades\Log;

class FormatProductPriceTypeDataForSage
{
    public function handle($context, Closure $next)
    {
        $productPriceType = $context;
        $productId = $productPriceType->product_id;
        $product = Product::find($productId);

        $prices = $product->priceType()->withPivot(['unit_price', 'yearly_rental'])->get();
        $formattedPrices = $prices->map(function($priceType) use ($product) {

            $vatPercentage = 0;
            if($priceType->pivot->vat_id) {
                $vat = VatCode::find($priceType->pivot->vat_id);
                $vatPercentage = $vat ? $vat->percentage : 0;
            }

            // Price calculations
            $priceExclusive = (float)$priceType->pivot->unit_price;
            $priceInclusive = $priceExclusive * (1 + $vatPercentage / 100);

            return [
                'ItemCode' => $product->abbreviation,
                'PriceListName' => $priceType->name,
                'PriceExclusive' => round($priceExclusive, 2),
                'PriceInclusive' => round($priceInclusive, 2),
                'VatPercentage' => $vatPercentage,
            ];
        });

        $prettyJson = json_encode($formattedPrices->toArray(), JSON_PRETTY_PRINT);

        Log::info("Formatted Sage Pricing Data:\n" . $prettyJson);

        return $next($context);
    }
}
