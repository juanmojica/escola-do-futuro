<?php

namespace App\Services;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Exceptions\BusinessException;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getUserNotifications($userId, $perPage = 20)
    {
        return $this->notificationRepository->getByUserId($userId, $perPage);
    }

    public function getUnreadNotifications($userId, $limit = 10)
    {
        $notifications = $this->notificationRepository->getUnreadByUserId($userId, $limit);

        return [
            'count' => $notifications->count(),
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => $this->getNotificationUrl($notification),
                ];
            }),
        ];
    }

    public function markAsRead($notificationId, $userId)
    {
        $notification = $this->notificationRepository->markAsRead($notificationId, $userId);

        if (!$notification) {
            throw new BusinessException('Notificação não encontrada.');
        }

        return $notification;
    }

    public function markAllAsRead($userId)
    {
        return $this->notificationRepository->markAllAsReadByUserId($userId);
    }

    protected function getNotificationUrl($notification)
    {
        $data = $notification->data;

        switch ($notification->type) {
            case 'enrollment':
                return route('student.enrollments.index');
            default:
                return '#';
        }
    }
}
