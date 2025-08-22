<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\General\Offer;
use App\Models\General\VatCode;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Transform single product with pivot details
    protected function transformProductPivot($product)
    {
        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price_type_id' => $product->pivot->price_type_id,
            'vat_code_id' => $product->pivot->vat_code_id,
            'vat_code' => optional(VatCode::find($product->pivot->vat_code_id))->name,
            'quantity' => $product->pivot->quantity,
            'price' => $product->pivot->price,
            'deposit' => $product->pivot->deposit,
            'bcrs_deposit' => $product->pivot->bcrs_deposit,
            'total_price' => $product->pivot->total_price,
        ];
    }

    // Transform single offer along with its products
    protected function transformOfferWithProducts($offer)
    {
        return [
            'offer_id' => $offer->id,
            'offer_name' => $offer->name,
            'offer_total_price' => $offer->products->sum('pivot.total_price'),
            'products' => $offer->products->map(function($product) {
                return $this->transformProductPivot($product);
            }),
        ];
    }

    // Original method for a single product, refactored
    public function getProductPricesAndOffers(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $product = Product::with('priceType')->findOrFail($id);

        $offers = Offer::whereHas('products', function($q) use ($id) {
            $q->where('product_id', $id);
        })->with([
            'products' => function($q) {
                $q->withPivot([
                    'price_type_id', 'vat_code_id', 'quantity',
                    'price', 'deposit', 'bcrs_deposit', 'total_price'
                ]);
            }
        ])->get();

        $responseOffers = $offers->map(function($offer) {
            return $this->transformOfferWithProducts($offer);
        });

        return response()->json([
            'id' => $id,
            'product' => $product,
            'offers' => $responseOffers,
        ]);
    }

    // Original method for all products, refactored
    public function getAllProductPricesAndOffers(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = Product::with('priceType')->get();

        $responseProducts = $products->map(function($product) {
            $offers = Offer::whereHas('products', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })->with([
                'products' => function($q) {
                    $q->withPivot([
                        'price_type_id', 'vat_code_id', 'quantity',
                        'price', 'deposit', 'bcrs_deposit', 'total_price'
                    ]);
                }
            ])->get();

            $responseOffers = $offers->map(function($offer) {
                return $this->transformOfferWithProducts($offer);
            });

            return [
                'id' => $product->id,
                'product' => $product,
                'offers' => $responseOffers,
            ];
        });

        return response()->json([
            'products' => $responseProducts,
        ]);
    }
}
