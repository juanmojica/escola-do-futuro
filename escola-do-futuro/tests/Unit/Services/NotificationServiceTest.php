<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\NotificationService;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Exceptions\BusinessException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class NotificationServiceTest extends TestCase
{
    protected $notificationRepository;
    protected $notificationService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->notificationRepository = Mockery::mock(NotificationRepositoryInterface::class);
        $this->notificationService = new NotificationService($this->notificationRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_user_notifications()
    {
        // Arrange
        $userId = 1;
        $perPage = 20;

        $notifications = collect([
            (object) ['id' => 1, 'user_id' => 1, 'message' => 'Test'],
        ]);

        $paginator = new LengthAwarePaginator($notifications, 1, $perPage, 1);

        $this->notificationRepository
            ->shouldReceive('getByUserId')
            ->with($userId, $perPage)
            ->once()
            ->andReturn($paginator);

        // Act
        $result = $this->notificationService->getUserNotifications($userId, $perPage);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    /** @test */
    public function it_can_get_unread_notifications()
    {
        // Arrange
        $userId = 1;
        $limit = 10;

        $notification = (object) [
            'id' => 1,
            'message' => 'Você foi matriculado no curso: PHP',
            'type' => 'enrollment',
            'created_at' => now(),
            'data' => (object) ['course_id' => 1],
        ];

        $notifications = collect([$notification]);

        $this->notificationRepository
            ->shouldReceive('getUnreadByUserId')
            ->with($userId, $limit)
            ->once()
            ->andReturn($notifications);

        // Act
        $result = $this->notificationService->getUnreadNotifications($userId, $limit);

        // Assert
        $this->assertEquals(1, $result['count']);
        $this->assertCount(1, $result['notifications']);
        $this->assertEquals(1, $result['notifications'][0]['id']);
    }

    /** @test */
    public function it_can_mark_notification_as_read()
    {
        // Arrange
        $notificationId = 1;
        $userId = 1;

        $notification = (object) [
            'id' => 1,
            'user_id' => 1,
            'read_at' => now(),
        ];

        $this->notificationRepository
            ->shouldReceive('markAsRead')
            ->with($notificationId, $userId)
            ->once()
            ->andReturn($notification);

        // Act
        $result = $this->notificationService->markAsRead($notificationId, $userId);

        // Assert
        $this->assertEquals($notification, $result);
    }

    /** @test */
    public function it_throws_exception_when_notification_not_found()
    {
        // Arrange
        $notificationId = 999;
        $userId = 1;

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('Notificação não encontrada.');

        $this->notificationRepository
            ->shouldReceive('markAsRead')
            ->with($notificationId, $userId)
            ->once()
            ->andReturn(null);

        // Act
        $this->notificationService->markAsRead($notificationId, $userId);
    }

    /** @test */
    public function it_can_mark_all_as_read()
    {
        // Arrange
        $userId = 1;

        $this->notificationRepository
            ->shouldReceive('markAllAsReadByUserId')
            ->with($userId)
            ->once()
            ->andReturn(5);

        // Act
        $result = $this->notificationService->markAllAsRead($userId);

        // Assert
        $this->assertEquals(5, $result);
    }
}
