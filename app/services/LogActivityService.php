<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Facades\Activity;
use Illuminate\Support\Facades\Request; // Use Facade for Request
use Illuminate\Support\Facades\Auth;   // Import the Auth Facade

class LogActivityService
{
    /**
     * Log activity for a given model record.
     *
     * @param Model $model
     * @param string $action
     * @param array $additionalProperties
     * @param string|null $displayAttributeName The name of the attribute to display in the log, e.g., 'nama_jabatan' or 'name'.
     * @return void
     */
    public function logActivity(Model $model , string $action, array $additionalProperties = [], ?string $displayAttributeName = null): void
    {
        // Define default properties including IP address, User Agent, and authenticated user info
        $properties = array_merge([
            'ip_address' => Request::ip(), // Use Request Facade
            'user_agent' => Request::header('User-Agent'),

        ], $additionalProperties);

        // Get authenticated user information
        if (Auth::check()) {
            $user = Auth::user();
            $properties['logged_in_user'] = [
                'id' => $user->id,
                'nama' => $user->nama ?? 'N/A', // Assuming 'name' attribute exists
                'username' => $user->username ?? 'N/A', // Assuming 'email' attribute exists
                // Add any other user attributes you wish to log
            ];
            // Optionally, you can also set the causer directly if Spatie Activitylog is configured for it
            Activity::causedBy($user);
        } else {
            $properties['logged_in_user'] = 'Guest'; // If no user is logged in
        }

        // Get the model's class name without namespace
        $modelName = class_basename($model);

        // Construct the log description dynamically based on the model's attributes
        // Use the provided displayAttributeName, or fallback to 'nama_jabatan', 'name', or the model's key.
        $displayAttribute = $model->getAttribute($displayAttributeName)
                            ?? $model->getAttribute('name')
                            ?? $model->getKey();

        Activity::performedOn($model)
            ->withProperties($properties)
            ->log("{$modelName} \"{$displayAttribute}\" telah berhasil di{$action}.");
    }
}

