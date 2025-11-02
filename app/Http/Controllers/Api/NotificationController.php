<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated user
     * Optional filter by type: promotion or transaction
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $type = $request->query('type'); // promotion, transaction

        $query = Notification::forUser($user->id);

        if ($type) {
            $query->ofType($type);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'title_kh' => $notification->title_kh,
                    'message' => $notification->message,
                    'message_kh' => $notification->message_kh,
                    'data' => $notification->data,
                    'image' => $notification->image,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Get a specific notification
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $notification = Notification::forUser($user->id)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'title_kh' => $notification->title_kh,
                'message' => $notification->message,
                'message_kh' => $notification->message_kh,
                'data' => $notification->data,
                'image' => $notification->image,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at,
            ],
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        $notification = Notification::forUser($user->id)->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        $type = $request->type; // Optional: mark only specific type as read

        $query = Notification::forUser($user->id)->unread();

        if ($type) {
            $query->ofType($type);
        }

        $query->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Get unread notification count
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $promotionCount = Notification::forUser($user->id)
            ->ofType('promotion')
            ->unread()
            ->count();

        $transactionCount = Notification::forUser($user->id)
            ->ofType('transaction')
            ->unread()
            ->count();

        $totalCount = $promotionCount + $transactionCount;

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $totalCount,
                'promotion' => $promotionCount,
                'transaction' => $transactionCount,
            ],
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $notification = Notification::forUser($user->id)->findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }
}
