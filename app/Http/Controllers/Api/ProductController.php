<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\General\Offer;
use App\Models\General\VatCode;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Original method for a single product, refactored
    public function getProductPricesAndOffers(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $product = Product::with('priceType')->findOrFail($id);
        [$galleryMedia, $galleryPaths, $galleryUrls] = self::getProductMediaGallery($product);

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
            'gallery' => [
                'gallery_media' => $galleryMedia,
                'gallery_paths' => $galleryPaths,
                'gallery_urls' => $this->getProductGallery($galleryUrls),
            ],
            'offers' => $responseOffers,
        ]);
    }

    public function getAllProductPricesAndOffers(): \Illuminate\Http\JsonResponse
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

            [$galleryMedia, $galleryPaths, $galleryUrls] = self::getProductMediaGallery($product);

            $responseOffers = $offers->map(function($offer) {
                return $this->transformOfferWithProducts($offer);
            });

            return [
                'id' => $product->id,
                'product' => $product,
                'gallery' => [
                    'gallery_media' => $galleryMedia,
                    'gallery_paths' => $galleryPaths,
                    'gallery_urls' => $this->getProductGallery($galleryUrls),
                ],
                'offers' => $responseOffers,
            ];

        });

        //paginate the results
        $perPage = 100;
        $page = request()->get('page', 1);
        $paginated = $responseProducts->forPage($page, $perPage);
        $total = $responseProducts->count();
        $totalPages = ceil($total / $perPage);

        return response()->json([
            'current_page' => (int)$page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'data' => $paginated->values(),
        ]);
    }

    //    Helpers
    public function getProductGallery($galleryUrls): array
    {
        $galleryUrls = collect($galleryUrls);
        return $galleryUrls->map(function($url) {
            $origHost = parse_url(config('app.url'), PHP_URL_HOST) ?? request()->getHost();
            $tenant = strtolower(str_replace(' ', '-', tenant()->id));

            $hostParts = explode('.', $origHost);
            if(count($hostParts) > 2) {
                array_shift($hostParts);
            }
            $baseDomain = implode('.', $hostParts);

            $scheme = request()->getScheme();
            $assetPrefix = config('tenancy.tenant_asset_prefix', '/tenancy/assets/');
            $fullDomain = "{$tenant}.{$baseDomain}";
            $baseUrl = "{$scheme}://{$fullDomain}";

            $parsed = parse_url($url);
            $path = $parsed['path'] ?? $url;
            $relativePath = ltrim(substr($path, strlen('/storage/')), '/');

            return "{$baseUrl}{$assetPrefix}{$relativePath}";
        })->values()->toArray();
    }

    public static function getProductMediaGallery($product): array
    {
        $galleryMedia = $product->getMedia('gallery');

        $galleryPaths = $galleryMedia->map(function($media) {
            return $media->getPath(); // Returns absolute server path
        });

        $galleryUrls = $galleryMedia->map(function($media) {
            return $media->getUrl(); // get URLs for API
        });

        return [$galleryMedia, $galleryPaths, $galleryUrls];
    }

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
}
