<?php

namespace App\Nova;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Query\Search\SearchableRelation;

class CustomerDefaultProducts extends Resource
{
    public static $model = \App\Models\Customer\CustomerDefaultProducts::class;
    public static $globallySearchable = false;
    public static $perPageViaRelationship = 15;

    public function title()
    {
        return $this->product->name ?? 'Product #' . $this->id;
    }

    public static function searchableColumns(): array
    {
        return [
            new SearchableRelation('customer', 'client'),
            new SearchableRelation('product', 'name'),
            new SearchableRelation('priceType', 'name'),
        ];
    }

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
                }),

            Number::make('Quantity')
                ->hide()
                ->default(1)
                ->rules('required', 'integer', 'min:1', "max:9999")
                ->dependsOn('product', function($field, $request, $formData) {
                    $formData['product'] && $field->show();
                }),

        ];
    }

    /**
     * Get the cards available for the resource.
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @return array<int, Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
