<?php

namespace App\Nova\Parts\Customer;

use Laravel\Nova\Fields\Select;

class OtherDetails
{
    public function __invoke(): array
    {
        return [
            Select::make('Accounts Group', 'customer_groups_id')
                ->options(\App\Models\Customer\CustomerGroup::all()->pluck('name', 'id'))
                ->default(\App\Models\Customer\CustomerGroup::where('is_default', true)->value('id'))
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Select::make('Class', 'classes_id')
                ->options(\App\Models\Customer\Classes::all()->pluck('name', 'id'))
                ->default(\App\Models\Customer\Classes::where('is_default', true)->value('id'))
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Select::make('Client Status', 'client_statuses_id')
                ->options(\App\Models\Customer\ClientStatus::all()->pluck('name', 'id'))
                ->default(\App\Models\Customer\ClientStatus::where('is_default', true)->value('id'))
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Select::make('Client type', 'client_types_id')
                ->options(\App\Models\Customer\ClientType::all()->pluck('name', 'id'))
                ->default(\App\Models\Customer\ClientType::where('is_default', true)->value('id'))
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Select::make('Salesman', 'user_id')
                ->options(\App\Models\User::getSalesmenRoles())
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Select::make('How did you hear about us?', 'hear_about_id')
                ->options(\App\Models\Customer\HearAbout::all()->pluck('name', 'id'))
                ->default(\App\Models\Customer\HearAbout::where('is_default', true)->value('id'))
                ->hideFromIndex()
                ->displayUsingLabels(),

        ];
    }
}
