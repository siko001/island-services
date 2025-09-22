<?php

namespace IslandServices\CustomHeading;

use Laravel\Nova\Card;

class CustomHeading extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     */
    public function component(): string
    {
        return 'custom-heading';
    }
}
