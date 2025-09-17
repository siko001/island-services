<?php

namespace App\Nova\Parts\Customer;

use App\Nova\Area;
use App\Nova\Location;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class SummerAddress
{
    public function __invoke(): array
    {
        $fields = [

            Textarea::make('Address', 'summer_address')
                ->rules('max:255')
                ->maxlength(255)
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex(),

            Text::make('Post Code', 'summer_address_post_code')
                ->hideFromIndex()
                ->rules('max:255'),

            BelongsTo::make('Area', 'summerArea', Area::class)
                ->nullable()
                ->hideFromIndex(),

            BelongsTo::make('Location', 'summerLocality', Location::class)
                ->nullable()
                ->hideFromIndex(),

            Text::make('Country', 'summer_address_country')
                ->nullable()
                ->hideFromIndex(),

        ];

        // Conditionally apply `dependsOn` if this is for "billing"
        $fields = collect($fields)->map(function($field) {
            return $field->dependsOn('use_summer_address', function($fieldInstance, NovaRequest $request, $value) {
                if(!$value->use_summer_address) {
                    $fieldInstance->hide();
                }
            });
        })->all();

        return $fields;
    }
}
