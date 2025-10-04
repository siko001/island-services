<?php

namespace App\Nova;

use App\Helpers\HelperFunctions;
use App\Traits\ResourcePolicies;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class DocumentControl extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'document_control';
    public static $model = \App\Models\General\DocumentControl::class;
    public static $title = 'name';
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Select::make('Department')
                ->options(\App\Helpers\Data::DepartmentOptions())
                ->sortable()
                ->rules('required', 'string', 'max:255')
                ->displayUsingLabels(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:255'),

            File::make('Document', "file_path")
                ->storeAs(function($request) {
                    return HelperFunctions::retainFileName($request, 'documents');
                })
                ->hideFromIndex()
                ->disk('public')
                ->path('documents')
                ->rules('required', 'file', 'max:51200') //50MB
                ->help('Upload the document file here.'),

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
