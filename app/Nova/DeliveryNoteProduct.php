<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeliveryNoteProduct extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\DeliveryNoteProduct>
     */
    public static $model = \App\Models\Post\DeliveryNoteProduct::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'id';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'Products';
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Delivery Note', 'deliveryNote', DeliveryNote::class)->withMeta(['extraAttributes' => ['readonly' => true]]),
            BelongsTo::make('Product', 'product', Product::class),

            BelongsTo::make('Price Type', 'priceType')->onlyOnDetail(),
            BelongsTo::make('Price Type', 'priceType')->onlyOnIndex(),
            Select::make('Price Type', 'price_type_id')
                ->displayUsingLabels()
                ->onlyOnForms()
                ->searchable()
                ->hide()
                ->dependsOn(['product'], function($field, $request, $formData) {
                    $productId = $formData['product'] ?? null;
                    if($productId) {
                        $field->required();
                        $field->show();
                        $priceTypes = \App\Models\Product\ProductPriceType::where('product_id', $productId)->with('priceType')->get();
                        $options = $priceTypes->mapWithKeys(function($ppt) {
                            return [$ppt->price_type_id => $ppt->priceType->name];
                        })->toArray();

                        $field->options($options);
                    }
                }),

            Number::make('Quantity')
                ->rules('numeric', "min:1")
                ->step(1)
                ->default(1)
                ->hide()
                ->dependsOn(['product', 'price_type_id'], function($field, $request, $formData) {
                    $productId = $formData['product'] ?? null;
                    $price_type_id = $formData['price_type_id'] ?? null;
                    if($productId) {
                        $productQuantity = \App\Models\Product\Product::find($productId)->stock;
                        if($productQuantity > 0) {
                            $field->help('You currently have ' . $productQuantity . ' in stock.');
                        } else {
                            $field->help("❌ You're currently out of stock: $productQuantity.");
                        }

                    }
                    if($productId && $price_type_id) {
                        $field->show();
                    }
                }),

            Number::make('Unit Price', 'unit_price')
                ->dependsOn(['price_type_id', 'product'], function($field, $request, $formData) {
                    $priceTypeId = $formData['price_type_id'] ?? null;
                    $productId = $formData['product'] ?? null;
                    if($priceTypeId && $productId) {
                        $productPriceType = \App\Models\Product\ProductPriceType::where('product_id', $productId)
                            ->where('price_type_id', $priceTypeId)
                            ->first();
                        if($productPriceType) {
                            $field->show();
                            $field->value = $productPriceType->unit_price;
                            $field->default($productPriceType->unit_price);
                        }
                    }
                })
                ->hide()
                ->rules('numeric', 'min:1')
                ->step(0.01),

            Number::make('Total Price  (Ex. Vat)', 'total_price')->onlyOnDetail(),
            Number::make('Total Price  (Ex. Vat)', 'total_price')->onlyOnIndex(),
            Number::make('Total Price', 'total_price')
                ->withMeta(['extraAttributes' => ['readonly' => true]])
                ->help('excluding VAT')
                ->onlyOnForms()
                ->hide()
                ->dependsOn(['price_type_id', 'product', 'quantity', 'unit_price'], function($field, $request, $formData) {
                    $priceTypeId = $formData['price_type_id'] ?? null;
                    $productId = $formData['product'] ?? null;
                    $quantity = $formData['quantity'] ?? null;
                    $unitPriceForm = $formData->get('unit_price');

                    if($priceTypeId && $productId) {
                        $productPriceType = \App\Models\Product\ProductPriceType::where('product_id', $productId)
                            ->where('price_type_id', $priceTypeId)
                            ->first();
                        if($productPriceType && $unitPriceForm == '') {
                            $unitPrice = $productPriceType->unit_price;
                            $field->value = round($quantity * $unitPrice, 2);
                            $field->default(round($quantity * $unitPrice, 2));
                            $field->show();
                        } else {
                            if($unitPriceForm !== "") {
                                $field->default(round($quantity * $unitPriceForm, 2));
                                $field->value = round($quantity * $unitPriceForm, 2);
                                $field->show();
                            }
                        }
                    }
                }),

            BelongsTo::make('Vat Code', 'vatCode')->onlyOnDetail(),
            BelongsTo::make('Vat Code', 'vatCode')->onlyOnIndex(),
            Select::make('Vat Code', 'vat_code_id')
                ->dependsOn(['product', 'price_type_id'], function($field, $request, $formData) {
                    $priceTypeId = $formData['price_type_id'] ?? null;
                    $productId = $formData['product'] ?? null;
                    if($priceTypeId && $productId) {
                        $productPriceType = \App\Models\Product\ProductPriceType::where('product_id', $productId)
                            ->where('price_type_id', $priceTypeId)
                            ->first();
                        $vatCodeId = $productPriceType->vat_id ?? null;
                        $field->show();

                        // Now get the single VAT code record
                        if($vatCodeId) {
                            $vatCode = \App\Models\General\VatCode::find($vatCodeId);
                            if($vatCode) {
                                $field->options([
                                    $vatCode->id => $vatCode->name,
                                ]);
                                // Pre-select it in the form
                                $field->value = $vatCode->id;
                            }
                        }
                    }
                })
                ->onlyOnForms()
                ->hide()
                ->displayUsingLabels()
                ->rules('required')

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
