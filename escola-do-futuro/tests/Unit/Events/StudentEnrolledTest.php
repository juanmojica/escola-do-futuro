<?php

namespace Tests\Unit\Events;

use Tests\TestCase;
use App\Events\StudentEnrolled;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Mockery;

class StudentEnrolledTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_enrollment()
    {
        // Arrange
        $enrollment = Mockery::mock(Enrollment::class);

        // Act
        $event = new StudentEnrolled($enrollment);

        // Assert
        $this->assertInstanceOf(StudentEnrolled::class, $event);
        $this->assertSame($enrollment, $event->enrollment);
    }

    /** @test */
    public function it_holds_enrollment_data()
    {
        // Arrange
        $enrollment = new Enrollment([
            'student_id' => 1,
            'course_id' => 1,
            'enrollment_date' => '2024-01-15',
            'status' => 'ativa',
        ]);
        $enrollment->id = 1;

        // Act
        $event = new StudentEnrolled($enrollment);

        // Assert
        $this->assertEquals(1, $event->enrollment->id);
        $this->assertEquals(1, $event->enrollment->student_id);
        $this->assertEquals(1, $event->enrollment->course_id);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
