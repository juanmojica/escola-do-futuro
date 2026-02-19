<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\EnrollmentService;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Exceptions\BusinessException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class EnrollmentServiceTest extends TestCase
{
    protected $enrollmentRepository;
    protected $studentRepository;
    protected $enrollmentService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->enrollmentRepository = Mockery::mock(EnrollmentRepositoryInterface::class);
        $this->studentRepository = Mockery::mock(StudentRepositoryInterface::class);
        
        $this->enrollmentService = new EnrollmentService(
            $this->enrollmentRepository,
            $this->studentRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_all_enrollments()
    {
        // Arrange
        $enrollmentsData = collect([
            (object) ['id' => 1, 'student_id' => 1, 'course_id' => 1, 'status' => 'active'],
            (object) ['id' => 2, 'student_id' => 2, 'course_id' => 1, 'status' => 'active'],
        ]);

        $this->enrollmentRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($enrollmentsData);

        // Act
        $result = $this->enrollmentService->getAllEnrollments();

        // Assert
        $this->assertEquals($enrollmentsData, $result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_get_paginated_enrollments()
    {
        // Arrange
        $enrollmentsData = collect([
            (object) ['id' => 1, 'student_id' => 1, 'course_id' => 1],
        ]);

        $paginator = new LengthAwarePaginator(
            $enrollmentsData,
            1,
            10,
            1
        );

        $this->enrollmentRepository
            ->shouldReceive('paginate')
            ->with(10)
            ->once()
            ->andReturn($paginator);

        // Act
        $result = $this->enrollmentService->getPaginatedEnrollments(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    /** @test */
    public function it_can_get_paginated_enrollments_with_filters()
    {
        // Arrange
        $filters = ['status' => 'active', 'course_id' => 1];
        
        $paginator = new LengthAwarePaginator(
            collect([]),
            0,
            10,
            1
        );

        $this->enrollmentRepository
            ->shouldReceive('paginateWithFilters')
            ->with($filters, 10)
            ->once()
            ->andReturn($paginator);

        // Act
        $result = $this->enrollmentService->getPaginatedEnrollments(10, $filters);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /** @test */
    public function it_can_get_a_single_enrollment()
    {
        // Arrange
        $enrollmentData = (object) [
            'id' => 1,
            'student_id' => 1,
            'course_id' => 1,
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
        ];

        $this->enrollmentRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($enrollmentData);

        // Act
        $result = $this->enrollmentService->getEnrollment(1);

        // Assert
        $this->assertEquals($enrollmentData, $result);
    }

    /** @test */
    public function it_returns_null_when_enrollment_not_found()
    {
        // Arrange
        $this->enrollmentRepository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        // Act
        $result = $this->enrollmentService->getEnrollment(999);

        // Assert
        $this->assertNull($result);
    }

    /** @test */
    public function it_can_enroll_student_in_course()
    {
        // Arrange
        $studentId = 1;
        $courseId = 1;
        $data = ['enrollment_date' => '2024-01-15'];

        $this->enrollmentRepository
            ->shouldReceive('checkEnrollmentExists')
            ->with($studentId, $courseId)
            ->once()
            ->andReturn(false);

        $enrolledData = (object) [
            'id' => 1,
            'student_id' => $studentId,
            'course_id' => $courseId,
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
        ];

        $this->enrollmentRepository
            ->shouldReceive('enrollStudent')
            ->with($studentId, $courseId, $data)
            ->once()
            ->andReturn($enrolledData);

        // Act
        $result = $this->enrollmentService->enrollStudent($studentId, $courseId, $data);

        // Assert
        $this->assertEquals($enrolledData, $result);
        $this->assertEquals($studentId, $result->student_id);
        $this->assertEquals($courseId, $result->course_id);
    }

    /** @test */
    public function it_throws_exception_when_student_already_enrolled()
    {
        // Arrange
        $studentId = 1;
        $courseId = 1;

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('O estudante já está matriculado neste curso.');

        $this->enrollmentRepository
            ->shouldReceive('checkEnrollmentExists')
            ->with($studentId, $courseId)
            ->once()
            ->andReturn(true); // Já existe

        // Act
        $this->enrollmentService->enrollStudent($studentId, $courseId);
    }

    /** @test */
    public function it_can_update_enrollment()
    {
        // Arrange
        $enrollmentId = 1;
        $updateData = ['status' => 'completed'];

        $updatedEnrollment = (object) [
            'id' => $enrollmentId,
            'student_id' => 1,
            'course_id' => 1,
            'status' => 'completed',
        ];

        $this->enrollmentRepository
            ->shouldReceive('update')
            ->with($enrollmentId, $updateData)
            ->once()
            ->andReturn($updatedEnrollment);

        // Act
        $result = $this->enrollmentService->updateEnrollment($enrollmentId, $updateData);

        // Assert
        $this->assertEquals($updatedEnrollment, $result);
        $this->assertEquals('completed', $result->status);
    }

    /** @test */
    public function it_can_delete_enrollment()
    {
        // Arrange
        $enrollmentId = 1;

        $this->enrollmentRepository
            ->shouldReceive('delete')
            ->with($enrollmentId)
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->enrollmentService->deleteEnrollment($enrollmentId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_get_enrollments_by_student()
    {
        // Arrange
        $studentId = 1;
        $enrollmentsData = collect([
            (object) ['id' => 1, 'student_id' => 1, 'course_id' => 1, 'status' => 'active'],
            (object) ['id' => 2, 'student_id' => 1, 'course_id' => 2, 'status' => 'active'],
            (object) ['id' => 3, 'student_id' => 1, 'course_id' => 3, 'status' => 'completed'],
        ]);

        $this->enrollmentRepository
            ->shouldReceive('getEnrollmentsByStudent')
            ->with($studentId)
            ->once()
            ->andReturn($enrollmentsData);

        // Act
        $result = $this->enrollmentService->getEnrollmentsByStudent($studentId);

        // Assert
        $this->assertEquals($enrollmentsData, $result);
        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_returns_empty_collection_when_student_has_no_enrollments()
    {
        // Arrange
        $studentId = 99;

        $this->enrollmentRepository
            ->shouldReceive('getEnrollmentsByStudent')
            ->with($studentId)
            ->once()
            ->andReturn(collect([]));

        // Act
        $result = $this->enrollmentService->getEnrollmentsByStudent($studentId);

        // Assert
        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_can_get_enrollments_by_course()
    {
        // Arrange
        $courseId = 1;
        $enrollmentsData = collect([
            (object) ['id' => 1, 'student_id' => 1, 'course_id' => 1, 'status' => 'active'],
            (object) ['id' => 2, 'student_id' => 2, 'course_id' => 1, 'status' => 'active'],
        ]);

        $this->enrollmentRepository
            ->shouldReceive('getEnrollmentsByCourse')
            ->with($courseId)
            ->once()
            ->andReturn($enrollmentsData);

        // Act
        $result = $this->enrollmentService->getEnrollmentsByCourse($courseId);

        // Assert
        $this->assertEquals($enrollmentsData, $result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_returns_empty_collection_when_course_has_no_enrollments()
    {
        // Arrange
        $courseId = 99;

        $this->enrollmentRepository
            ->shouldReceive('getEnrollmentsByCourse')
            ->with($courseId)
            ->once()
            ->andReturn(collect([]));

        // Act
        $result = $this->enrollmentService->getEnrollmentsByCourse($courseId);

        // Assert
        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_delegates_all_operations_to_repository()
    {
        // Arrange & Act
        $this->enrollmentRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect([]));

        $this->enrollmentRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn((object) ['id' => 1]);

        $this->enrollmentRepository
            ->shouldReceive('checkEnrollmentExists')
            ->with(1, 1)
            ->once()
            ->andReturn(false);

        $this->enrollmentRepository
            ->shouldReceive('enrollStudent')
            ->with(1, 1, [])
            ->once()
            ->andReturn((object) ['id' => 1]);

        $this->enrollmentService->getAllEnrollments();
        $this->enrollmentService->getEnrollment(1);
        $this->enrollmentService->enrollStudent(1, 1);

        // Assert
        $this->assertTrue(true);
    }
}
