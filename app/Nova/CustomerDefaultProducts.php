<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomerDefaultProducts extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Customer\CustomerDefaultProducts>
     */
    public static $model = \App\Models\Customer\CustomerDefaultProducts::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
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
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Customer', 'customer', Customer::class)->immutable()->required(),
            BelongsTo::make('Product', 'product', Product::class)->searchable()->required(),
            BelongsTo::make('Price Type', 'priceType', PriceType::class)->searchable()->required()->hideWhenCreating()->hideWhenUpdating(),
            Select::make('Price Type', 'price_type_id')
                ->onlyOnForms()
                ->hide()
                ->dependsOn(['product', 'customer'], function($field, $request, $formData) {
                    $customerId = $formData['resource:customers'] ?? $formData['customer'] ?? null;
                    $product = \App\Models\Product\Product::find($formData->product);
                    if(!$product || !$customerId) {
                        return;
                    }

                    $customer = \App\Models\Customer\Customer::where('id', $customerId)->with('defaultStock')->first();
                    $defaultStockPriceTypes = $customer->defaultStock->pluck('price_type_id')->toArray();
                    $currentPriceTypeId = $formData['price_type_id'] ?? null;

                    $filteredPriceTypes = $product->priceType->filter(function($priceType) use ($defaultStockPriceTypes, $currentPriceTypeId) {
                        return !in_array($priceType->id, $defaultStockPriceTypes) || ($currentPriceTypeId && $priceType->id == $currentPriceTypeId);
                    })->pluck('name', 'id')->toArray();

                    $field->options($filteredPriceTypes)
                        ->displayUsingLabels()
                        ->required()
                        ->show();
                })

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
