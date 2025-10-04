<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Notifications\NovaNotification;

class Notifications
{
    /**
     * Notify all super admin users with a custom message about a model operation.
     * Placeholders in the $messageTemplate:
     *  - {model}      Model class name (e.g., App\Models\Post)
     *  - {attr}       Value of the attribute provided (e.g., 'title' => 'Welcome')
     *  - {operation}  Operation performed (e.g., 'created', 'updated')
     * Example usage:
     *  notifyAdmins(
     *      $post,
     *      'title',
     *      'created',
     *      "New {model} with title '{attr}' was {operation}."
     *  );
     * @param object $model The Eloquent model instance.
     * @param array $attributes
     * @param string $operation The operation performed.
     * @param string $messageTemplate Message template (supports {model}, {attr}, {operation}).
     * @return void
     */
    public static function notifyAdmins(object $model, array $attributes, string $operation, string $messageTemplate, string $icon = "user", $type = "success"): void
    {
        $message = self::getReplacements($model, $attributes, $operation, $messageTemplate);
        $admins = User::role('super admin')->get();
        foreach($admins as $admin) {
            $admin->notify(
                NovaNotification::make()
                    ->message($message)
                    ->icon($icon)
                    ->type($type)
            );
        }
    }

    public static function notifyUser(object $model, array $attributes, string $operation, string $messageTemplate, string $icon = "user", $type = "success"): void
    {
        $user = Auth::user();
        $message = self::getReplacements($model, $attributes, $operation, $messageTemplate);
        $user->notify(
            NovaNotification::make()
                ->message($message)
                ->icon($icon)
                ->type($type)
        );
    }

    protected static function getReplacements($model, $attributes, $operation, $messageTemplate): string
    {
        $replacements = [
            '{model}' => get_class($model),
            '{operation}' => $operation,
        ];

        foreach($attributes as $key => $value) {
            $replacements["{" . $key . "}"] = $value;
        }

        return str_replace(array_keys($replacements), array_values($replacements), $messageTemplate);

    }
}
