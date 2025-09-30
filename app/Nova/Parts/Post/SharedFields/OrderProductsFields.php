<?php

namespace App\Nova\Parts\Post\SharedFields;

use App\Helpers\HelperFunctions;
use App\Nova\DeliveryNote;
use App\Nova\DirectSale;
use App\Nova\Product;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class OrderProductsFields
{
    public function __invoke($orderType): array
    {
        $fields = [];
        switch($orderType) {
            case 'delivery_note':
                $fields[] = BelongsTo::make('Delivery Note', 'deliveryNote', DeliveryNote::class)->withMeta(['extraAttributes' => ['readonly' => true]]);
                break;
            case 'direct_sale':
                $fields[] = BelongsTo::make('Direct Sale', 'directSale', DirectSale::class)->withMeta(['extraAttributes' => ['readonly' => true]]);
                break;
            default:
                break;
        }
        $fields[] = BelongsTo::make('Product', 'product', Product::class);

        $fields[] = BelongsTo::make('Price Type', 'priceType')->onlyOnDetail();
        $fields[] = BelongsTo::make('Price Type', 'priceType')->onlyOnIndex();
        $fields[] = Select::make('Price Type', 'price_type_id')
            ->displayUsingLabels()
            ->rules('required')
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
            });

        $fields[] = Number::make('Quantity')
            ->textAlign('left')
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
            });

        $fields[] = Heading::make('<p style="color:blue; font-weight:bold; font-size:18px;">Price</p>')->asHtml()->hide()
            ->dependsOn(['product', 'vat_code_id'], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                $vatCodeId = $formData['vat_code_id'] ?? null;
                if($productId && $vatCodeId) {
                    $field->show();
                }
            });

        $fields[] = Number::make('Unit Price', 'unit_price')
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
            ->textAlign('left')
            ->hide()
            ->rules('numeric', 'min:1')
            ->step(0.01);

        $fields[] = Number::make('Total Price  (Ex. Vat)', 'total_price')->onlyOnDetail();
        $fields[] = Number::make('Total Price  (Ex. Vat)', 'total_price')->onlyOnIndex()->textAlign('left');
        $fields[] = Number::make('Total Price', 'total_price')
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
            });

        $fields[] = Heading::make('<p style="color:blue; font-weight:bold; font-size:18px;">Deposit</p>')->asHtml()->hide()
            ->dependsOn(['product'], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                if($productId) {
                    $field->show();
                }
            });

        $fields[] = Number::make("Unit Deposit", 'deposit_price')
            ->hide()
            ->dependsOn(['product'], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                if($productId) {
                    $product = \App\Models\Product\Product::where('id', $productId)->first();
                    if($product) {
                        $depositPrice = $product->deposit;
                        if($depositPrice) {
                            $field->show();
                            $field->value = $depositPrice;
                            $field->default($depositPrice);
                        } else {
                            $field->show();
                            $field->value = 0.00;
                            $field->default(0.00);
                        }
                    }
                }
            });

        $fields[] = Number::make("Total Deposit", 'total_deposit_price')
            ->hide()
            ->withMeta(['extraAttributes' => ['readonly' => true]])
            ->dependsOn(['product', 'quantity', 'deposit_price'], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                $quantity = $formData['quantity'] ?? null;
                $depositPriceInput = $formData['deposit_price'] ?? null;

                if($quantity) {
                    if($depositPriceInput !== null) {
                        $depositPrice = floatval($depositPriceInput);
                    } else if($productId) {
                        $product = \App\Models\Product\Product::where('id', $productId)->first();
                        $depositPrice = $product ? floatval($product->deposit) : 0.00;
                    } else {
                        $depositPrice = 0.00;
                    }

                    if($depositPrice > 0) {
                        $field->show();
                        $field->value = round($depositPrice * $quantity, 2);
                    } else {
                        $field->value = 0.00;
                        $field->default(0.00);
                    }
                }
            });

        $fields[] = BelongsTo::make('Vat Code', 'vatCode')->onlyOnDetail();
        $fields[] = BelongsTo::make('Vat Code', 'vatCode')->onlyOnIndex();

        $fields[] = Select::make('Vat Code', 'vat_code_id')
            ->dependsOn(['product', 'price_type_id'], function($field, $request, $formData) {
                $priceTypeId = $formData['price_type_id'] ?? null;
                $productId = $formData['product'] ?? null;

                if($priceTypeId && $productId) {
                    $productPriceType = \App\Models\Product\ProductPriceType::where('product_id', $productId)
                        ->where('price_type_id', $priceTypeId)
                        ->first();

                    $field->show();

                    // Get all VAT codes as options
                    $allVatCodes = \App\Models\General\VatCode::all()->pluck('name', 'id')->toArray();

                    if($productPriceType) {
                        $vatCodeId = $productPriceType->vat_id ?? null;
                        $field->options($allVatCodes);

                        if($vatCodeId && array_key_exists($vatCodeId, $allVatCodes)) {
                            $field->value = $vatCodeId;
                        }
                    } else {
                        // fallback to all vat codes without selected value
                        $field->options($allVatCodes);
                    }
                }
            })
            ->onlyOnForms()
            ->hide()
            ->displayUsingLabels()
            ->rules('required');

        $fields[] = Heading::make('<p style="color:blue; font-weight:bold; font-size:18px;">BCRS Deposit</p>')->asHtml()->hide()
            ->dependsOn(['product'], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                if($productId) {
                    $field->show();
                }
            });

        $fields[] = Number::make('BCRS Deposit')
            ->dependsOn(['product', 'price_type_id'], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                if($productId) {
                    $product = \App\Models\Product\Product::where('id', $productId)->first();
                    if($product) {
                        $bcrs = $product->bcrs_deposit;
                        if($bcrs !== null && $bcrs > 0) {
                            $field->value = round($bcrs, 2);
                            $field->default(round($bcrs, 2));
                            $field->show();
                        } else {
                            $field->value = 0;
                            $field->default(0);
                            $field->show();
                        }
                    }
                }
            })
            ->hide()
            ->textAlign('left');

        $fields[] = Number::make('Total BCRS Deposit')
            ->withMeta(['extraAttributes' => ['readonly' => true]])
            ->dependsOn(['product', 'quantity', 'bcrs_deposit'], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                $quantity = $formData['quantity'] ?? null;
                $bcrsInput = $formData['bcrs_deposit'] ?? null;

                if($productId && $quantity) {
                    if($bcrsInput !== null) {
                        $bcrs = floatval($bcrsInput);
                    } else {
                        $product = \App\Models\Product\Product::where('id', $productId)->first();
                        $bcrs = $product ? floatval($product->bcrs_deposit) : 0;
                    }

                    if($bcrs > 0) {
                        $field->show();
                        $field->value = round($bcrs * $quantity, 2);
                    } else {
                        $field->hide();
                        $field->value = 0;
                        $field->default(0);
                    }
                }
            })
            ->hide()
            ->textAlign('left');

        $fields[] = Heading::make('<p style="color:blue; font-weight:bold; font-size:18px;">Additional Details</p>')->asHtml()->hide()
            ->dependsOn(['product',], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                if($productId) {
                    $field->show();
                }
            });

        $fields[] = Text::make('Make')
            ->hide()
            ->dependsOn(['product'], function($field, $request, $formData) {
                HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Product\Product::class, 'product', 'make');
            });

        $fields[] = Text::make('Model')
            ->hide()
            ->dependsOn(['product'], function($field, $request, $formData) {
                HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Product\Product::class, 'product', 'model');
            });

        $fields[] = Text::make('Serial Number')
            ->hide()
            ->dependsOn(['product'], function($field, $request, $formData) {
                HelperFunctions::fillFromDependentField($field, $formData, \App\Models\Product\Product::class, 'product', 'serial_number');
            });

        $fields[] = Heading::make('<p style="color:blue; font-weight:bold; font-size:18px;">Conversion Info</p>')->asHtml()->hide()
            ->dependsOn(['product',], function($field, $request, $formData) {
                $productId = $formData['product'] ?? null;
                if($productId) {
                    $field->show();
                }
            });

        $fields[] = Boolean::make('Converted')->hideWhenCreating()->hideWhenUpdating();
        $fields[] = BelongsTo::make('Prepaid Offer', "prepaidOffer")->hideWhenCreating()->hideWhenUpdating();

        return $fields;
    }
}
