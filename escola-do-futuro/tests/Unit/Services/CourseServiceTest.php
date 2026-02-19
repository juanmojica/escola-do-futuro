<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CourseService;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Exceptions\BusinessException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Mockery;

class CourseServiceTest extends TestCase
{
    protected $courseRepository;
    protected $courseService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->courseRepository = Mockery::mock(CourseRepositoryInterface::class);
        
        $this->courseService = new CourseService($this->courseRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_all_courses()
    {
        $coursesData = collect([
            (object) ['id' => 1, 'title' => 'PHP Básico', 'description' => 'Curso de PHP'],
            (object) ['id' => 2, 'title' => 'Laravel Avançado', 'description' => 'Curso de Laravel'],
        ]);

        $this->courseRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($coursesData);

        $result = $this->courseService->getAllCourses();

        $this->assertEquals($coursesData, $result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_get_paginated_courses()
    {
        $coursesData = collect([
            (object) ['id' => 1, 'title' => 'PHP Básico'],
        ]);

        $paginator = new LengthAwarePaginator(
            $coursesData,
            1,
            10,
            1
        );

        $this->courseRepository
            ->shouldReceive('paginate')
            ->with(10)
            ->once()
            ->andReturn($paginator);

        $result = $this->courseService->getPaginatedCourses(10);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    /** @test */
    public function it_can_get_paginated_courses_with_custom_per_page()
    {
        $paginator = new LengthAwarePaginator(
            collect([]),
            0,
            15,
            1
        );

        $this->courseRepository
            ->shouldReceive('paginate')
            ->with(15)
            ->once()
            ->andReturn($paginator);

        $result = $this->courseService->getPaginatedCourses(15);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->perPage());
    }

    /** @test */
    public function it_can_get_a_single_course()
    {
        $courseData = (object) [
            'id' => 1,
            'title' => 'PHP Básico',
            'description' => 'Curso introdutório de PHP',
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
        ];

        $this->courseRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($courseData);

        $result = $this->courseService->getCourse(1);

        $this->assertEquals($courseData, $result);
        $this->assertEquals('PHP Básico', $result->title);
    }

    /** @test */
    public function it_returns_null_when_course_not_found()
    {
        $this->courseRepository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        $result = $this->courseService->getCourse(999);

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_create_a_course()
    {
        $courseData = [
            'title' => 'Novo Curso de Laravel',
            'description' => 'Aprenda Laravel do zero',
            'start_date' => '2024-03-01',
            'end_date' => '2024-08-31',
        ];

        $createdCourse = (object) array_merge(['id' => 1], $courseData);

        $this->courseRepository
            ->shouldReceive('create')
            ->with($courseData)
            ->once()
            ->andReturn($createdCourse);

        $result = $this->courseService->createCourse($courseData);

        $this->assertEquals($createdCourse, $result);
        $this->assertEquals('Novo Curso de Laravel', $result->title);
        $this->assertEquals(1, $result->id);
    }

    /** @test */
    public function it_can_update_a_course()
    {
        $courseId = 1;
        $updateData = [
            'title' => 'PHP Avançado - Atualizado',
            'description' => 'Descrição atualizada',
        ];

        $updatedCourse = (object) [
            'id' => $courseId,
            'title' => 'PHP Avançado - Atualizado',
            'description' => 'Descrição atualizada',
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
        ];

        $this->courseRepository
            ->shouldReceive('update')
            ->with($courseId, $updateData)
            ->once()
            ->andReturn($updatedCourse);

        $result = $this->courseService->updateCourse($courseId, $updateData);

        $this->assertEquals($updatedCourse, $result);
        $this->assertEquals('PHP Avançado - Atualizado', $result->title);
    }

    /** @test */
    public function it_can_delete_a_course()
    {
        $courseId = 1;

        $this->courseRepository
            ->shouldReceive('delete')
            ->with($courseId)
            ->once()
            ->andReturn(true);

        $result = $this->courseService->deleteCourse($courseId);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_throws_exception_when_deleting_course_with_active_enrollments()
    {
        $courseId = 1;

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('Não é possível excluir um curso com matrículas ativas.');

        $this->courseRepository
            ->shouldReceive('delete')
            ->with($courseId)
            ->once()
            ->andThrow(new BusinessException('Não é possível excluir um curso com matrículas ativas.', 409));

        $this->courseService->deleteCourse($courseId);
    }

    /** @test */
    public function it_can_get_course_with_enrollments()
    {
        $courseId = 1;
        $courseWithEnrollments = (object) [
            'id' => 1,
            'title' => 'PHP Básico',
            'enrollments' => [
                (object) ['id' => 1, 'student_id' => 1, 'status' => 'active'],
                (object) ['id' => 2, 'student_id' => 2, 'status' => 'active'],
            ],
            'subjects' => [
                (object) ['id' => 1, 'title' => 'Introdução ao PHP'],
            ],
        ];

        $this->courseRepository
            ->shouldReceive('getCourseWithEnrollments')
            ->with($courseId)
            ->once()
            ->andReturn($courseWithEnrollments);

        $result = $this->courseService->getCourseWithEnrollments($courseId);

        $this->assertEquals($courseWithEnrollments, $result);
        $this->assertCount(2, $result->enrollments);
        $this->assertCount(1, $result->subjects);
    }

    /** @test */
    public function it_delegates_all_operations_to_repository()
    {        
        $this->courseRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect([]));

        $this->courseRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn((object) ['id' => 1]);

        $this->courseRepository
            ->shouldReceive('create')
            ->with(['title' => 'Test'])
            ->once()
            ->andReturn((object) ['id' => 1, 'title' => 'Test']);

        $this->courseService->getAllCourses();
        $this->courseService->getCourse(1);
        $this->courseService->createCourse(['title' => 'Test']);

        $this->assertTrue(true);
    }
}
