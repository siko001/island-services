<?php

namespace App\Enums;

enum OwnershipType: string
{
    case Rented = 'rented';
    case Purchased = 'purchased';
}
