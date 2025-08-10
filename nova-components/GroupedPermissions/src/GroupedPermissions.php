<?php

namespace IslandServices\GroupedPermissions;

use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasPermissions;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;

class GroupedPermissions extends PermissionBooleanGroup
{
    /**
     * The field's component.
     * @var string
     */
    public $component = 'grouped-permissions';

    protected function fillAttributeFromRequest(\Laravel\Nova\Http\Requests\NovaRequest $request, string $requestAttribute, object $model, string $attribute): void
    {
        Log::debug('fillAttributeFromRequest called', [
            'request_attribute' => $requestAttribute,
            'request_data' => $request->all(),
        ]);

        if(!in_array(HasPermissions::class, class_uses_recursive($model))) {
            throw new \InvalidArgumentException('Model must implement HasPermissions trait.');
        }

        if(!$request->exists($requestAttribute)) {
            Log::debug('Request attribute missing: ' . $requestAttribute);
            return;
        }

        $values = $request->input($requestAttribute);

        Log::debug('Permissions sync input values:', ['values' => $values]);

        if(is_string($values)) {
            $values = json_decode($values, true) ?: [];
        }

        if(is_array($values)) {
            $model->syncPermissions($values);
            Log::debug('Permissions synced for model ID ' . $model->id);
        } else {
            Log::debug('Input values is not an array', ['type' => gettype($values)]);
        }
    }
}
