<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DocumentControl extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\General\DocumentControl>
     */
    public static $model = \App\Models\General\DocumentControl::class;
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
            Select::make('Department')
                ->options(\App\Helpers\Data::DepartmentOptions())
                ->sortable()
                ->rules('required', 'string', 'max:255')
                ->displayUsingLabels(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'string', 'max:255'),

            File::make('Document', "file_path")
                ->disk('public')
                ->path('documents')
                ->rules('required', 'file', 'max:51200') //50MB
                ->help('Upload the document file here.'),

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

    //Resource authorization methods
    public static function authorizedToCreate(Request $request): bool
    {
        return $request->user() && $request->user()->can('create document_control');
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return $request->user() && $request->user()->can('update document_control');
    }

    public function authorizedToDelete(Request $request): bool
    {
        return $request->user() && $request->user() && $request->user()->can('delete document_control');
    }

    public static function authorizedToViewAny(Request $request): bool
    {
        return $request->user() && $request->user()->can('view any document_control');
    }

    public function authorizedToView(Request $request): bool
    {
        return $request->user() && $request->user()->can('view document_control');
    }
}
