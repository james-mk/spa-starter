<?php

namespace App\Traits;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected function logActivity($action, $model, $modelId, $description, $oldValues = null, $newValues = null)
    {
        UserLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => class_basename($model),
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected function getRelevantAttributes($model, $attributes)
    {
        // Remove sensitive and irrelevant fields
        $hiddenFields = ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];

        return collect($attributes)
            ->except($hiddenFields)
            ->toArray();
    }
}