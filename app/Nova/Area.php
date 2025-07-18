<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Area extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Area>
     */
    public static $model = \App\Models\Area::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'name';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable()->rules('required', 'max:255'),
            Text::make('Abbreviation')->sortable()->rules('max:20'),
            Boolean::make('Is Foreign', 'is_foreign_area')
                ->sortable()
                ->default(false)
                ->hideFromIndex(),

            Panel::make('Commission ( in % ) for Paid Outstanding ', [
                //            Heading::make("<p style='margin-top:24px;' class='md:text-xl'>Commission ( in % ) for Paid Outstanding </p>")->asHtml(),
                Number::make('on Deliveries', 'commission_paid_outstanding_delivery')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),

                Number::make('On Deposits', 'commission_paid_outstanding_deposit')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),

            ]),

            Panel::make('Commission ( in % ) for Cash', [
                //                Heading::make("<p style='margin-top:24px;' class='md:text-xl'>Commission ( in % ) for Cash</p>")->asHtml(),
                Number::make('on Deliveries', 'commission_cash_delivery')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),

                Number::make('On Deposits', 'commission_cash_deposit')
                    ->sortable()
                    ->rules('required', 'numeric')
                    ->default('0.00')
                    ->step(0.01)
                    ->withMeta(['extraAttributes' => ['style' => 'width:50%;min-width: 250px;']])
                    ->hideFromIndex(),
            ]),

            Panel::make("Delivery Note Information", [
                Textarea::make('Delivery Note Remark', 'delivery_note_remark')
                    ->sortable()
                    ->rules('max:255')
                    ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px;']])
                    ->hideFromIndex(),

                Email::make('Customer Care Email', 'customer_care_email')
                    ->sortable()
                    ->rules('required', 'email')
                    ->hideFromIndex(),
            ])
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
