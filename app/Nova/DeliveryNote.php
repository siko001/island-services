<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Nova\Actions\DeliveryNote\ProcessDeliveryNote;
use App\Nova\Lenses\Post\DeliveryNote\ProcessedDeliveryNotes;
use App\Nova\Lenses\Post\DeliveryNote\UnprocessedDeliveryNotes;
use App\Nova\Parts\Post\DeliveryNote\AdditionalDetails;
use App\Nova\Parts\Post\DeliveryNote\DeliveryDetails;
use App\Nova\Parts\Post\DeliveryNote\FinancialDetails;
use App\Policies\ResourcePolicies;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;
use Laravel\Nova\Tabs\Tab;

class DeliveryNote extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'delivery_note';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\DeliveryNote>
     */
    public static $model = \App\Models\Post\DeliveryNote::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'delivery_note_number';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'delivery_note_number',
    ];

    public static function searchableColumns(): array
    {
        return [
            'delivery_note_number',
            new SearchableRelation('customer', 'client'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [

            //            random and auto generate delivery note number
            Boolean::make("Processed", 'status')->readonly()->onlyOnDetail(),
            Boolean::make("Processed", 'status')->readonly()->onlyOnIndex()->sortable(),

            Text::make('Delivery Note Number', 'delivery_note_number')
                ->default(function() {
                    return \App\Models\Post\DeliveryNote::generateDeliveryNoteNumber();
                })
                ->help('this field is auto generated')
                ->sortable()
                ->rules('required', 'max:255', 'unique:delivery_notes,delivery_note_number,{{resourceId}}')
                ->creationRules('unique:delivery_notes,delivery_note_number'),
            Date::make('Order Date', 'order_date')->default(\Carbon\Carbon::now())
                ->sortable()
                ->rules('date'),

            BelongsTo::make('Customer', 'customer', Customer::class)->sortable(),

            Text::make('Account Number', 'customer_account_number')
                ->hideFromIndex()
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'account_number');
                }),
            Text::make('Customer Email')
                ->dependsOn('customer', function($field, $request, FormData $formData) {
                    HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Customer\Customer::class, 'customer', 'delivery_details_email_one');
                }),

            Tab::group('Information', [

                Tab::make("Delivery Details", new DeliveryDetails()),

                Tab::make("Financial Details", new FinancialDetails()),

                Tab::make("Additional Details", new AdditionalDetails()),
            ]),
            //
            //            HasMany::make("Products", 'deliveryNoteProduct')->fields(function() {
            //                return [
            //
            //                    Select::make('Price Type', 'price_type_id')
            //                        ->searchable()
            //                        ->hide()
            //                        ->dependsOn(['deliveryNoteProduct'], function($field, $request, $formData) {
            //                            $productId = $formData['deliveryNoteProduct'] ?? null;
            //                            if($productId) {
            //                                $field->required();
            //                                $field->show();
            //                                $priceTypes = \App\Models\Product\ProductPriceType::where('product_id', $productId)->with('priceType')->get();
            //                                $options = $priceTypes->mapWithKeys(function($ppt) {
            //                                    return [$ppt->price_type_id => $ppt->priceType->name];
            //                                })->toArray();
            //
            //                                $field->options($options);
            //                            }
            //                        })
            //                        ->displayUsingLabels(),
            //                    Number::make('Quantity')
            //                        ->rules('numeric', "min:1")
            //                        ->step(1)
            //                        ->default(1)
            //                        ->hide()
            //                        ->dependsOn(['deliveryNoteProduct', 'price_type_id'], function($field, $request, $formData) {
            //                            $productId = $formData['deliveryNoteProduct'] ?? null;
            //                            $price_type_id = $formData['price_type_id'] ?? null;
            //                            if($productId && $price_type_id) {
            //                                $field->show();
            //                            }
            //                        }),
            //
            //                    Number::make('Unit Price', 'unit_price')
            //                        ->dependsOn(['price_type_id', 'deliveryNoteProduct'], function($field, $request, $formData) {
            //                            $priceTypeId = $formData['price_type_id'] ?? null;
            //                            $productId = $formData['deliveryNoteProduct'] ?? null;
            //                            if($priceTypeId && $productId) {
            //                                $productPriceType = \App\Models\Product\ProductPriceType::where('product_id', $productId)
            //                                    ->where('price_type_id', $priceTypeId)
            //                                    ->first();
            //                                if($productPriceType) {
            //                                    $field->show();
            //                                    $field->value = $productPriceType->unit_price;
            //                                    $field->default($productPriceType->unit_price);
            //                                }
            //                            }
            //                        })
            //                        ->hide()
            //                        ->rules('numeric', 'min:1')
            //                        ->step(0.01),
            //
            //                    Number::make('Total Price', 'total_price')
            //                        ->readonly()
            //                        ->hide()
            //                        ->dependsOn(['price_type_id', 'deliveryNoteProduct', 'quantity', 'unit_price'], function($field, $request, $formData) {
            //                            $priceTypeId = $formData['price_type_id'] ?? null;
            //                            $productId = $formData['deliveryNoteProduct'] ?? null;
            //                            $quantity = $formData['quantity'] ?? null;
            //                            $unitPriceForm = $formData->get('unit_price');
            //
            //                            if($priceTypeId && $productId) {
            //                                $productPriceType = \App\Models\Product\ProductPriceType::where('product_id', $productId)
            //                                    ->where('price_type_id', $priceTypeId)
            //                                    ->first();
            //                                if($productPriceType && $unitPriceForm == '') {
            //                                    $unitPrice = $productPriceType->unit_price;
            //                                    $field->value = round($quantity * $unitPrice, 2);
            //                                    $field->show();
            //                                    $field->readOnly();
            //                                } else {
            //                                    if($unitPriceForm !== "") {
            //                                        $field->value = round($quantity * $unitPriceForm, 2);
            //                                        $field->show();
            //                                        $field->readOnly();
            //                                    }
            //                                }
            //                            }
            //                        }),
            //
            //                    Select::make('Vat Code', 'vat_code_id')
            //                        ->dependsOn(['deliveryNoteProduct', 'price_type_id'], function($field, $request, $formData) {
            //                            $priceTypeId = $formData['price_type_id'] ?? null;
            //                            $productId = $formData['deliveryNoteProduct'] ?? null;
            //                            if($priceTypeId && $productId) {
            //                                $productPriceType = \App\Models\Product\ProductPriceType::where('product_id', $productId)
            //                                    ->where('price_type_id', $priceTypeId)
            //                                    ->first();
            //                                $vatCodeId = $productPriceType->vat_id ?? null;
            //
            //                                // Now get the single VAT code record
            //                                if($vatCodeId) {
            //                                    $vatCode = \App\Models\General\VatCode::find($vatCodeId);
            //                                    if($vatCode) {
            //                                        $field->options([
            //                                            $vatCode->id => $vatCode->name,
            //                                        ]);
            //                                        // Pre-select it in the form
            //                                        $field->value = $vatCode->id;
            //                                    }
            //                                }
            //                            }
            //                        })
            //                        ->displayUsingLabels()
            //                        ->rules('required')

            //                ];
            //            }),

            HasMany::make('Products', 'deliveryNoteProducts', DeliveryNoteProduct::class),

        ];
        //        Still to do load-sheet number (when in a load sheet) Products has many rel
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
        return [
            new ProcessedDeliveryNotes(),
            new UnprocessedDeliveryNotes()
        ];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new ProcessDeliveryNote(),
        ];
    }

    public static function relatableCustomers(NovaRequest $request, $query)
    {
        return $query->where('account_closed', false);
    }
}
