<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Jobs\SendEnrollmentNotification;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Mockery;

class SendEnrollmentNotificationTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        // Arrange
        $enrollment = Mockery::mock(Enrollment::class);

        // Act
        $job = new SendEnrollmentNotification($enrollment);

        // Assert
        $this->assertInstanceOf(SendEnrollmentNotification::class, $job);
        $this->assertEquals(3, $job->tries);
        $this->assertEquals(60, $job->timeout);
        $this->assertEquals(30, $job->backoff);
    }

    /** @test */
    public function it_has_failed_method()
    {
        // Arrange
        $enrollment = new Enrollment([
            'id' => 1,
            'student_id' => 1,
            'course_id' => 1,
        ]);
        $enrollment->id = 1;

        $job = new SendEnrollmentNotification($enrollment);
        $exception = new \Exception('Test error');

        // Act & Assert - Não deve lançar exceção
        $job->failed($exception);
        
        $this->assertTrue(true); // Se chegou aqui, não lançou exceção
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
