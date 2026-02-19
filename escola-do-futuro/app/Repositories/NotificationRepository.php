<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function getByUserId($userId, $perPage = 20)
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getUnreadByUserId($userId, $limit = 10)
    {
        return $this->model
            ->where('user_id', $userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function markAsRead($notificationId, $userId)
    {
        $notification = $this->findByUserIdAndId($userId, $notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            return $notification;
        }
        
        return null;
    }

    public function markAllAsReadByUserId($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->unread()
            ->update(['read_at' => now()]);
    }

    public function findByUserIdAndId($userId, $notificationId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('id', $notificationId)
            ->first();
    }
}
