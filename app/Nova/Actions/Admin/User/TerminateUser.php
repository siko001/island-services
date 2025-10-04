<?php

namespace App\Nova\Actions\Admin\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class TerminateUser extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     * @return mixed
     */

    public function handle(ActionFields $fields, Collection $models)
    {
        $countUsers = 0;
        $countVehicles = 0;

        foreach($models as $user) {
            if($user->is_terminated) {
                continue;
            }
            $user->is_terminated = true;
            $user->gets_commission = false;
            $user->standard_commission = false;

            $vehicleCount = $user->vehicles()->count();

            if($vehicleCount > 0) {
                $user->vehicles()->each(function($vehicle) use ($user) {
                    $vehicle->drivers()->detach($user->id);
                });
                $countVehicles += $vehicleCount;
            }

            $user->save();
            $countUsers++;
        }

        // Also display a toast notification after completion (optional redundancy)
        return Action::message(
            "Terminated $countUsers user(s)." .
            ($countVehicles > 0 ? " Unassigned from $countVehicles vehicle(s)." : "")
        );
    }

    /**
     * Get the fields available on the action.
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Determine if the action is authorized to run.
     * @return bool
     */
    public function authorizedToSee(\Illuminate\Http\Request $request)
    {
        if(!auth()->user()->can('terminate user')) {
            return false;
        }

        //hide the action if or for the users that have been already terminated
        $selectedResourceIds = $request->resources ?? [];
        $editViewResourceId = $request->{'resourceId'};

        if(!$editViewResourceId && !$selectedResourceIds) {
            return true;
        }

        $resources = User::whereIn('id', $selectedResourceIds)->get();
        if(!$editViewResourceId && $resources->isEmpty()) {
            return false;
        }

        if($editViewResourceId) {
            $user = User::find($editViewResourceId);
            return $user && !$user->is_terminated;
        }

        return $resources->contains(fn($r) => !$r->is_terminated);

    }
}
