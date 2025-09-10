<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class GalleryItem extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('UUID', 'uuid'),

            Image::make('Gallery Image', "gallery")
                ->disk('public')
                ->path('gallery')
                ->rules('file', 'max:51200')

        ];
    }
}
