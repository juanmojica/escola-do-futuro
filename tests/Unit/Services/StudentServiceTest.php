<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\StudentService;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Exceptions\BusinessException;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Mockery;

class StudentServiceTest extends TestCase
{
    protected $studentRepository;
    protected $studentService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->studentRepository = Mockery::mock(StudentRepositoryInterface::class);
        
        $this->studentService = new StudentService($this->studentRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_all_students()
    {
        // Arrange
        $studentsData = collect([
            (object) ['id' => 1, 'user_id' => 1, 'birth_date' => '2000-01-01'],
            (object) ['id' => 2, 'user_id' => 2, 'birth_date' => '2001-06-15'],
        ]);

        $this->studentRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($studentsData);

        // Act
        $result = $this->studentService->getAllStudents();

        // Assert
        $this->assertEquals($studentsData, $result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_get_paginated_students()
    {
        // Arrange
        $studentsData = collect([
            (object) ['id' => 1, 'user_id' => 1],
        ]);

        $paginator = new LengthAwarePaginator(
            $studentsData,
            1,
            10,
            1
        );

        $this->studentRepository
            ->shouldReceive('paginate')
            ->with(10)
            ->once()
            ->andReturn($paginator);

        // Act
        $result = $this->studentService->getPaginatedStudents(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    /** @test */
    public function it_can_get_paginated_students_with_custom_per_page()
    {
        // Arrange
        $paginator = new LengthAwarePaginator(
            collect([]),
            0,
            25,
            1
        );

        $this->studentRepository
            ->shouldReceive('paginate')
            ->with(25)
            ->once()
            ->andReturn($paginator);

        // Act
        $result = $this->studentService->getPaginatedStudents(25);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(25, $result->perPage());
    }

    /** @test */
    public function it_can_get_a_single_student()
    {
        // Arrange
        $studentData = (object) [
            'id' => 1,
            'user_id' => 1,
            'birth_date' => '2000-05-15',
        ];

        $this->studentRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($studentData);

        // Act
        $result = $this->studentService->getStudent(1);

        // Assert
        $this->assertEquals($studentData, $result);
    }

    /** @test */
    public function it_returns_null_when_student_not_found()
    {
        // Arrange
        $this->studentRepository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        // Act
        $result = $this->studentService->getStudent(999);

        // Assert
        $this->assertNull($result);
    }

    /** @test */
    public function it_can_search_students()
    {
        // Arrange
        $search = 'João';
        $studentsData = collect([
            (object) ['id' => 1, 'name' => 'João Silva'],
            (object) ['id' => 2, 'name' => 'João Pedro'],
        ]);

        $this->studentRepository
            ->shouldReceive('searchByNameOrEmail')
            ->with($search)
            ->once()
            ->andReturn($studentsData);

        // Act
        $result = $this->studentService->searchStudents($search);

        // Assert
        $this->assertEquals($studentsData, $result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_get_student_with_courses()
    {
        // Arrange
        $studentId = 1;
        $studentWithCourses = (object) [
            'id' => 1,
            'user_id' => 1,
            'courses' => [
                (object) ['id' => 1, 'title' => 'PHP Básico'],
                (object) ['id' => 2, 'title' => 'Laravel'],
            ],
            'enrollments' => [
                (object) ['id' => 1, 'course_id' => 1, 'status' => 'active'],
            ],
        ];

        $this->studentRepository
            ->shouldReceive('getStudentWithCourses')
            ->with($studentId)
            ->once()
            ->andReturn($studentWithCourses);

        // Act
        $result = $this->studentService->getStudentWithCourses($studentId);

        // Assert
        $this->assertEquals($studentWithCourses, $result);
        $this->assertCount(2, $result->courses);
    }

    /** @test */
    public function it_throws_exception_when_getting_student_with_courses_not_found()
    {
        // Arrange
        $studentId = 999;

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('Aluno não encontrado.');
        $this->expectExceptionCode(404);

        $this->studentRepository
            ->shouldReceive('getStudentWithCourses')
            ->with($studentId)
            ->once()
            ->andReturn(null);

        // Act
        $this->studentService->getStudentWithCourses($studentId);
    }

    /** @test */
    public function it_can_get_student_by_user_id()
    {
        // Arrange
        $userId = 10;
        $studentData = (object) [
            'id' => 1,
            'user_id' => 10,
            'birth_date' => '2000-01-01',
        ];

        $this->studentRepository
            ->shouldReceive('findByUserId')
            ->with($userId)
            ->once()
            ->andReturn($studentData);

        // Act
        $result = $this->studentService->getStudentByUserId($userId);

        // Assert
        $this->assertEquals($studentData, $result);
        $this->assertEquals(10, $result->user_id);
    }

    /** @test */
    public function it_throws_exception_when_student_by_user_id_not_found()
    {
        // Arrange
        $userId = 999;

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('Você não possui um perfil de aluno associado.');
        $this->expectExceptionCode(403);

        $this->studentRepository
            ->shouldReceive('findByUserId')
            ->with($userId)
            ->once()
            ->andReturn(null);

        // Act
        $this->studentService->getStudentByUserId($userId);
    }

    /** @test */
    public function it_throws_exception_when_updating_non_existent_student()
    {
        // Arrange
        $studentId = 999;
        $updateData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
        ];

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('Aluno não encontrado.');
        $this->expectExceptionCode(404);

        $this->studentRepository
            ->shouldReceive('find')
            ->with($studentId)
            ->once()
            ->andReturn(null);

        // Act
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->studentService->updateStudent($studentId, $updateData);
    }

    /** @test */
    public function it_can_delete_a_student()
    {
        // Arrange
        $studentId = 1;

        $this->studentRepository
            ->shouldReceive('delete')
            ->with($studentId)
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->studentService->deleteStudent($studentId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function it_throws_exception_when_deleting_student_with_active_enrollments()
    {
        // Arrange
        $studentId = 1;

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('O Aluno não pode ser excluído pois possui matrículas ativas.');

        $this->studentRepository
            ->shouldReceive('delete')
            ->with($studentId)
            ->once()
            ->andThrow(new BusinessException('O Aluno não pode ser excluído pois possui matrículas ativas.', 409));

        // Act
        $this->studentService->deleteStudent($studentId);
    }

    /** @test */
    public function it_delegates_all_basic_operations_to_repository()
    {
        // Arrange & Act
        $this->studentRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect([]));

        $this->studentRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn((object) ['id' => 1]);

        $this->studentRepository
            ->shouldReceive('searchByNameOrEmail')
            ->with('test')
            ->once()
            ->andReturn(collect([]));

        $this->studentService->getAllStudents();
        $this->studentService->getStudent(1);
        $this->studentService->searchStudents('test');

        // Assert
        $this->assertTrue(true);
    }
}
