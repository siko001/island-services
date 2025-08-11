<?php

namespace App\Nova\Parts\General;

use Laravel\Nova\Fields\Boolean;

class WeekdaysFields
{
    public function __invoke(): array
    {
        return [
            Boolean::make('Monday')->sortable(),
            Boolean::make('Tuesday')->sortable(),
            Boolean::make('Wednesday')->sortable(),
            Boolean::make('Thursday')->sortable(),
            Boolean::make('Friday')->sortable(),
            Boolean::make('Saturday')->sortable(),
            Boolean::make('Sunday')->sortable(),
        ];

    }
}
