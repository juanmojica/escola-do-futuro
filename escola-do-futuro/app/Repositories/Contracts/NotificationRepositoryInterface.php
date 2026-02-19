<?php

namespace App\Repositories\Contracts;

interface NotificationRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUserId($userId, $perPage = 20);
    public function getUnreadByUserId($userId, $limit = 10);
    public function markAsRead($notificationId, $userId);
    public function markAllAsReadByUserId($userId);
    public function findByUserIdAndId($userId, $notificationId);
}
