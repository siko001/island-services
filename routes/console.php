<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\HelperFunctions;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


//Artisan command to create the permissions
Artisan::command('permissions:generate:crud', function () {
    HelperFunctions::generateCrudPermissions($this->output);
})->describe('Generate CRUD permissions for all Nova resources');
