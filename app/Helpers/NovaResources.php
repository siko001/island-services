<?php

namespace App\Helpers;

use App\Nova\Area;
use App\Nova\Classes;
use App\Nova\ClientStatus;
use App\Nova\ClientType;
use App\Nova\CollectionNote;
use App\Nova\Complaint;
use App\Nova\Customer;
use App\Nova\CustomerDefaultStock;
use App\Nova\CustomerGroup;
use App\Nova\DeliveryNote;
use App\Nova\DirectSale;
use App\Nova\DocumentControl;
use App\Nova\HearAbout;
use App\Nova\Location;
use App\Nova\MonetoryValue;
use App\Nova\Offer;
use App\Nova\OrderType;
use App\Nova\Permission;
use App\Nova\PrepaidOffer;
use App\Nova\PriceType;
use App\Nova\Product;
use App\Nova\Role;
use App\Nova\Service;
use App\Nova\SparePart;
use App\Nova\User;
use App\Nova\VatCode;
use App\Nova\Vehicle;

class NovaResources
{
    public static function generalResources(): array
    {
        return [
            Area::class,
            Location::class,
            OrderType::class,
            SparePart::class,
            Service::class,
            Complaint::class,
            Vehicle::class,
            VatCode::class,
            DocumentControl::class,
            MonetoryValue::class,
            Offer::class,
        ];
    }

    public static function customerResources(): array
    {
        return [
            CustomerGroup::class,
            Classes::class,
            ClientStatus::class,
            HearAbout::class,
            ClientType::class,
            Customer::class,
        ];
    }

    public static function stockResources(): array
    {
        return [
            Product::class,
            PriceType::class,
        ];
    }

    public static function postResources(): array
    {
        return [
            DeliveryNote::class,
            DirectSale::class,
            CollectionNote::class,
            PrepaidOffer::class
        ];
    }

    public static function adminResources(): array
    {
        return
            [
                User::class,
                Role::class,
                Permission::class,
            ];
    }
}
