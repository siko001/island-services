<?php

namespace IslandServices\GroupedPermissions;

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
        if(!in_array(HasPermissions::class, class_uses_recursive($model))) {
            throw new \InvalidArgumentException('Model must implement HasPermissions trait.');
        }

        if(!$request->exists($requestAttribute)) {
            return;
        }

        $values = $request->input($requestAttribute);

        if(is_string($values)) {
            $values = json_decode($values, true) ?: [];
        }

        if(is_array($values)) {
            $model->syncPermissions($values);
        }
    }
}
