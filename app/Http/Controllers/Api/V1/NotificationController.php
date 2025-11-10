<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate($request->get('per_page', 15));

        return response()->json($notifications);
    }

    public function unread(Request $request)
    {
        $notifications = $request->user()
            ->unreadNotifications()
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    //mark as unread
    public function markAsUnread(Request $request, $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsUnread();

        return response()->json([
            'message' => 'Notification marked as unread'
        ]);
    }

    public function markAllAsUnread(Request $request)
    {
        $request->user()
            ->readNotifications
            ->markAsUnread();

        return response()->json([
            'message' => 'All notifications marked as unread'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted'
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'total' => $user->notifications()->count(),
            'unread' => $user->unreadNotifications()->count(),
            'read' => $user->readNotifications()->count(),
        ]);
    }
}
