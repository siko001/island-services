<?php

namespace IslandServices\PendingOrderInfo;

use Laravel\Nova\ResourceTool;

class PendingOrderInfo extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     */
    public function name(): string
    {
        return 'Pending Order Info';
    }

    /**
     * Get the component name for the resource tool.
     */
    public function component(): string
    {
        return 'pending-order-info';
    }
}
