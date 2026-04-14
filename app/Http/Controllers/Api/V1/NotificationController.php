<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponses;

    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()->paginate(20);

        foreach ($notifications as $notification) {
            $notification->time_ago = $notification->created_at->diffForHumans();
        }

        $unreadCount = $user->unreadNotifications()->count();

        return $this->successResponse([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return $this->noContentResponse();
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->noContentResponse();
    }
}