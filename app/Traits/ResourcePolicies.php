<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResourcePolicies
{
    protected static function policyKey(): string
    {
        return static::$policyKey ?? Str::snake(class_basename(static::class));
    }

    protected static function permission(string $action): string
    {
        return $action . ' ' . static::policyKey();
    }

    public static function authorizedToViewAny(Request $request): bool
    {
        return $request->user()?->can(static::permission('view any')) ?? false;
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return $request->user()?->can(static::permission('create')) ?? false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return $request->user()?->can(static::permission('update')) ?? false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return $request->user()?->can(static::permission('delete')) ?? false;
    }

    public function authorizedToView(Request $request): bool
    {
        return $request->user()?->can(static::permission('view')) ?? false;
    }
}
