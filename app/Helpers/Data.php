<?php

namespace App\Helpers;
class Data
{
    public static $MAX_DRIVER_COUNT = 2;

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

    public static function RoleOptions(): array
    {
        return [
            'Super Admin',
            'General Manager',
            'Manager',
            'Finance',
            'Driver',
            'Salesman',
            'Technician',
            'User',
            'Sales',
            'Customer Care',
            'Auditors'
        ];
    }
}
