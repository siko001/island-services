<?php

namespace App\Helpers;
class Data
{
    public static function DepartmentOptions(): array
    {
        return [
            'General' => "General",
            "Technical" => "Technical",
            'Operations' => "Operations",
            "Production" => "Production",
            'Sales' => 'Sales',
            'Delivery' => "Delivery",
            'Delivery Driver' => "Delivery Driver",
            'Customer Care' => "Customer Care",
            'Finance' => "Finance",
        ];
    }
}
