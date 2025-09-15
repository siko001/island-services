<?php

namespace App\Nova\Parts\Product;

use App\Helpers\HelperFunctions;
use IslandServices\Gallery\Gallery;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Textarea;

class WebsiteInfo
{
    public function __invoke(): array
    {
        return [
            Textarea::make('Short Description')
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->rules('max:255')
                ->maxlength(255)
                ->hideFromIndex()
                ->sortable(),

            Textarea::make('Detailed Description')
                ->withMeta(['extraAttributes' => ['style' => 'max-height: 150px; min-height:100px']])
                ->hideFromIndex()
                ->sortable(),

            File::make('Product Image', "image_path")
                ->thumbnail(function($value, $disk) {
                    return $value ? tenant_asset($value) : null;
                })
                ->storeAs(function($request) {
                    return HelperFunctions::retainFileName($request, 'images');
                })
                ->disk('public')
                ->path('images')
                ->rules('file', 'max:51200') //50MB
                ->hideFromIndex()
                ->help('Upload the Product Featured Image here.'),

            Gallery:: make('Gallery')

        ];

    }
}
