<?php

namespace App\Nova;

use App\Policies\ResourcePolicies;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class OfferProduct extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'offer';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\OfferProduct>
     */
    public static $model = \App\Models\General\OfferProduct::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the display name for the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Products';
    }

    /**
     * Get the singular label for the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Product';
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string
     */
    public static function createButtonLabel()
    {
        return 'Attach Products';
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->product->name ?? 'Product #' . $this->id;
    }

    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Offer', 'offer', Offer::class)
                ->withMeta(['extraAttributes' => ['readonly' => true]])
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Product', 'product', Product::class)
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Price Type', 'priceType', PriceType::class)->onlyOnDetail(),
            BelongsTo::make('Price Type', 'priceType', PriceType::class)->onlyOnIndex()->sortable(),
            Select::make('Price Type', 'price_type_id')->onlyOnForms()
                ->options(fn() => \App\Models\Product\PriceType::all()->pluck('name', 'id')->toArray())
                ->default(fn() => \App\Models\Product\PriceType::where('is_default', true)->first()?->id),

            BelongsTo::make('VAT Code', 'vatCode', VatCode::class)->onlyOnDetail(),
            BelongsTo::make('VAT Code', 'vatCode', VatCode::class)->onlyOnIndex()->sortable(),
            Select::make('VAT Code', 'vat_code_id')->onlyOnForms()
                ->options(fn() => \App\Models\general\VatCode::all()->pluck('name', 'id')->toArray())
                ->default(fn() => \App\Models\general\VatCode::where('is_default', true)->first()?->id),

            Number::make('Quantity', 'quantity')
                ->dependsOn(['product'], function($field, $request, $formData) {
                    $product = $formData->get('product');
                    $product && $field->show();
                })
                ->hide()
                ->sortable()
                ->min(0)
                ->step(1)
                ->default(1)
                ->rules('required', 'numeric', 'min:1'),

            Number::make('Unit Price', 'price')
                ->dependsOn(['product'], function($field, $request, $formData) {
                    $productId = $formData->get('product');
                    $productPrice = \App\Models\Product\Product::find($productId)?->product_price;
                    if($productId && $productPrice) {
                        $field->value = $productPrice;
                        $field->show();
                    }
                })
                ->hide()
                ->sortable()
                ->min(0)
                ->step(0.01)
                ->nullable()
                ->help('Default from product but can be adjusted'),

            Number::make('Total Price (Ex. Vat)', 'total_price')
                ->dependsOn(['product', 'price', 'quantity'], function($field, $request, $formData) {
                    $unitPrice = $formData->get('price');
                    $quantity = $formData->get('quantity');
                    if($unitPrice) {
                        $field->value = $unitPrice * $quantity;
                        $field->show();
                    }
                })
                ->hide()
                ->withMeta(['extraAttributes' => ['readonly' => true]]),

            Number::make('Deposit', 'deposit')
                ->dependsOn(['product'], function($field, $request, $formData) {
                    $productId = $formData->get('product');
                    $productDeposit = \App\Models\Product\Product::find($productId)?->deposit;
                    if($productDeposit) {
                        $field->value = $productDeposit;
                        $field->show();
                    }
                })
                ->hide()
                ->sortable()
                ->min(0)
                ->step(0.01)
                ->nullable()
                ->help('Default from product but can be adjusted'),

            Number::make('BCRS Deposit', 'bcrs_deposit')
                ->dependsOn(['product'], function($field, $request, $formData) {
                    $productId = $formData->get('product');
                    $productBCRSDeposit = \App\Models\Product\Product::find($productId)?->bcrs_deposit;
                    if($productBCRSDeposit) {
                        $field->value = $productBCRSDeposit;
                        $field->show();
                    }
                })
                ->hide()
                ->sortable()
                ->min(0)
                ->step(0.01)
                ->nullable()
                ->help('From product'),
        ];
    }

    /**
     * Get the cards available for the resource.
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
