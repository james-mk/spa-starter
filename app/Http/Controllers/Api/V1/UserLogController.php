<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLog;

class UserLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by model
        if ($request->has('model') && $request->model) {
            $query->where('model', $request->model);
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Paginate results
        $logs = $query->paginate($request->get('per_page', 50));

        return response()->json($logs);
    }

    public function show($id)
    {
        $log = UserLog::with('user')->findOrFail($id);
        return response()->json($log);
    }
}