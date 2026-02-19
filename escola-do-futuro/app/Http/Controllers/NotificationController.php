<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $notifications = $this->notificationService->getUserNotifications(Auth::id(), 20);

        return view('notifications.index', compact('notifications'));
    }

    public function unread()
    {
        $data = $this->notificationService->getUnreadNotifications(Auth::id(), 10);

        return response()->json($data);
    }

    public function markAsRead($id)
    {
        try {
            $this->notificationService->markAsRead($id, Auth::id());

            return response()->json(['success' => true]);

        } catch (BusinessException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(Auth::id());

        return response()->json(['success' => true]);
    }
}
