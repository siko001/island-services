<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait LensPolicy
{
    protected static function policyKey(): string
    {
        return static::$policyKey ?? Str::snake(class_basename(static::class));
    }

    protected static function permission(string $action): string
    {
        return $action . ' ' . static::policyKey();
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()?->can(static::permission('view')) ?? false;
    }
}
