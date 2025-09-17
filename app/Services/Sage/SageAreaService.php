<?php

namespace App\Services\Sage;

use App\Helpers\Notifications;
use App\Models\General\Area;
use App\Pipelines\Sage\Area\CheckAreaExists;
use App\Pipelines\Sage\Area\FormatAreaDataForSage;
use App\Pipelines\Sage\Area\SendAreaCreationRequest;
use App\Pipelines\Sage\Area\ValidateAreaForSage;
use App\Pipelines\Sage\SageConnection;
use Exception;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;

class SageAreaService
{
    /**
     * @throws Exception
     */
    public function createInSage(Area $area): void //POST || Create a new area || /Freedom.Core/Freedom Database/SDK/AreaInsert{AREA}
    {
        try {
            $context = app(Pipeline::class)
                ->send($area)
                ->through([
                    ValidateAreaForSage::class,
                    FormatAreaDataForSage::class,
                    SageConnection::class,
                    CheckAreaExists::class,
                    SendAreaCreationRequest::class
                ])
                ->thenReturn();
            Log::info('Sage API createInSage called for Area', ['area_id' => $area->id, 'context' => $context]);

            Notifications::notifyAdmins(
                $area,
                ['area' => $area->name],
                'created',
                "Area {area} created successfully in Sage",
                'map-pin'
            );

        } catch(Exception $err) {
            Log::error('Error creating area in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);

            Notifications::notifyAdmins(
                $area,
                ['area' => $area->name],
                'created',
                "Area {area} failed to create in Sage",
                'exclamation-circle',
                'error',
            );

            throw $err;
        }

    }

    public function updateInSage(Area $area): void //POST || Update an existing area ||/Freedom.Core/Freedom Database/SDK/AreaUpdate{AREA}
    {
        Log::info("Starting Pipelines for sage: " . json_encode($area));
        try {
            $context = app(Pipeline::class)
                ->send($area)
                ->through([
                    ValidateAreaForSage::class,
                    FormatAreaDataForSage::class,
                    SageConnection::class,
                    CheckAreaExists::class,
                    SendAreaCreationRequest::class // TODO - Create a separate pipeline stage for updating an area
                ])
                ->thenReturn();

            Notifications::notifyAdmins(
                $area,
                ['area' => $area->name],
                'update',
                "Area {area} updated successfully in Sage",
                'map-pin'
            );
            Log::info('Sage API update In Sage called', ['area_id' => $area->id, 'context' => $context]);
        } catch(Exception $err) {

            Notifications::notifyAdmins(
                $area,
                ['area' => $area->name],
                'update',
                "Area {area} failed to update in Sage",
                'exclamation-circle',
                'error',
            );
            Log::error('Error updating area in Sage: ' . $err->getMessage(), [
                'exception' => $err,
            ]);
            throw $err;
        }

    }
}
