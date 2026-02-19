<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Listeners\NotifyStudentEnrollment;
use App\Events\StudentEnrolled;
use App\Jobs\SendEnrollmentNotification;
use Illuminate\Support\Facades\Queue;
use Mockery;

class NotifyStudentEnrollmentTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        // Assert
        $listener = new NotifyStudentEnrollment();
        
        $this->assertInstanceOf(NotifyStudentEnrollment::class, $listener);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
