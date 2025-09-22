<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\DeliveryNote\NewDeliveryNotes;
use App\Nova\Metrics\DeliveryNote\ProcessedDeliveryNotes;
use Laravel\Nova\Dashboard;
use Laravel\Nova\Fields\Heading;

class MainDash extends Dashboard
{
    public function name()
    {
        $tenant = tenancy()->tenant->id;
        return $tenant . ' Main Dashboard';
    }

    /**
     * Get the cards for the dashboard.
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {

        return [
            Heading::make('Delivery Note Metrics'),
            NewDeliveryNotes::make()->width('1/2')->defaultRange('TODAY'),
            ProcessedDeliveryNotes::make()->width('1/2')->defaultRange('TODAY'),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     */
    public function uriKey(): string
    {
        return 'main';
    }
}
